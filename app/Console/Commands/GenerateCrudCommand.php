<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateCrudCommand extends Command
{
    /**
     * Formatte les règles de validation pour l'affichage dans le contrôleur
     *
     * @param array $rules
     * @return string
     */
    protected function formatValidationRules(array $rules)
    {
        if (empty($rules)) {
            return '[]';
        }

        $formatted = "[\n";
        foreach ($rules as $field => $rule) {
            $formatted .= "            '" . addslashes($field) . "' => '" . addslashes($rule) . "',\n";
        }
        $formatted .= "        ]";

        return $formatted;
    }
    protected $signature = 'make:crud {model?}';
    protected $description = 'Generate CRUD operations for a model';

    public function handle()
    {
        $modelsPath = app_path('Models');
        $modelFiles = File::files($modelsPath);
        
        $models = collect($modelFiles)
            ->map(fn($file) => pathinfo($file)['filename'])
            ->filter(fn($model) => $model !== 'Model')
            ->values()
            ->toArray();

        if (empty($models)) {
            $this->error('No models found in app/Models directory!');
            return;
        }

        $selectedModel = $this->argument('model') 
            ?: $this->choice('Select a model to generate CRUD for', $models);

        if (!in_array($selectedModel, $models)) {
            $this->error("Model {$selectedModel} not found!");
            return;
        }

        if ($this->generateController($selectedModel) !== false) {
            $this->generateRoutes($selectedModel);
            $this->info("CRUD pour {$selectedModel} généré avec succès !");
        }
        //$this->info("N'oubliez pas d'ajouter les routes dans votre fichier routes/api.php si nécessaire.");
    }

    
    protected function generateRoutes($modelName)
    {
        $routeName = Str::kebab(Str::plural($modelName));
        $controllerName = "{$modelName}Controller";
        $routesPath = base_path('routes/api.php');
        $currentContent = file_get_contents($routesPath);
        
        // Vérifier si la route existe déjà
        $routePattern = "Route::apiResource\\(['\"]{$routeName}['\"]";
        if (preg_match("/{$routePattern}/", $currentContent)) {
            $this->info("Les routes pour {$modelName} existent déjà dans routes/api.php");
            return;
        }
        
        $routeContent = "\n\n// Routes pour {$modelName}\n";
        $routeContent .= "Route::apiResource('{$routeName}', \\App\\Http\\Controllers\\{$controllerName}::class);\n";
        
        // Ajouter les routes personnalisées si nécessaire
        // Exemple : Route::post('{$routeName}/{id}/action', [{$controllerName}::class, 'action']);
        
        file_put_contents($routesPath, $routeContent, FILE_APPEND);
    }

    protected function generateController($modelName)
    {
        $controllerName = "{$modelName}Controller";
        $modelVarName = lcfirst($modelName);
        $modelPlural = Str::plural($modelVarName);
        $controllerPath = app_path("Http/Controllers/{$controllerName}.php");
        
        // Vérifier si le contrôleur existe déjà
        if (file_exists($controllerPath)) {
            if (!$this->confirm("Le contrôleur {$controllerName} existe déjà. Voulez-vous l'écraser ?")) {
                $this->info("Génération du contrôleur annulée.");
                return false;
            }
        }
        
        // Règles de validation
        $rulesString = "[\n                //Compléter les règles de validation\n            ]";

        $stub = <<<EOT
<?php

namespace App\Http\Controllers;

use App\\Models\\{$modelName};
use Illuminate\\Http\\Request;

class {$controllerName} extends Controller
{
    public function index()
    {
        try {
            \${$modelPlural} = {$modelName}::all();
            return response()->json([
                'success' => true,
                'data' => \${$modelPlural},
                'message' => 'Liste des objets {$modelName} récupérée avec succès'
            ]);
        } catch (\\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la récupération de la liste des objets {$modelName}',
                'errors' => [\$e->getMessage()]
            ], 500);
        }
    }

    public function store(Request \$request)
    {
        try {
            \$validated = \$request->validate({$rulesString});

            \${$modelVarName} = {$modelName}::create(\$validated);
            
            return response()->json([
                'success' => true,
                'data' => \${$modelVarName},
                'message' => 'Objet {$modelName} créé avec succès'
            ], 201);

        } catch (\\Illuminate\\Validation\\ValidationException \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => \$e->errors()
            ], 422);
        } catch (\\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la création de l\'objet {$modelName}',
                'errors' => [\$e->getMessage()]
            ], 500);
        }
    }

    public function show({$modelName} \${$modelVarName})
    {
        try {
            return response()->json([
                'success' => true,
                'data' => \${$modelVarName},
                'message' => 'Objet {$modelName} récupéré avec succès'
            ]);
        } catch (\\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la récupération de l\'objet {$modelName}',
                'errors' => [\$e->getMessage()]
            ], 500);
        }
    }

    public function update(Request \$request, {$modelName} \${$modelVarName})
    {
        try {
            \$validated = \$request->validate({$rulesString});

            \${$modelVarName}->update(\$validated);
            
            return response()->json([
                'success' => true,
                'data' => \${$modelVarName},
                'message' => 'Objet {$modelName} mis à jour avec succès'
            ]);

        } catch (\\Illuminate\\Validation\\ValidationException \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => \$e->errors()
            ], 422);
        } catch (\\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la mise à jour de l\'objet {$modelName}',
                'errors' => [\$e->getMessage()]
            ], 500);
        }
    }

    public function destroy({$modelName} \${$modelVarName})
    {
        try {
            \${$modelVarName}->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Objet {$modelName} supprimé avec succès'
            ]);
        } catch (\\Exception \$e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la suppression de l\'objet {$modelName}',
                'errors' => [\$e->getMessage()]
            ], 500);
        }
    }
}
EOT;

        if (!file_exists(dirname($controllerPath))) {
            mkdir(dirname($controllerPath), 0777, true);
        }

        file_put_contents($controllerPath, $stub);
    }
}