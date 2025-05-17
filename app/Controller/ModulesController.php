<?php
namespace App\Controller;
use App\Helpers\ModuleManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use ZipArchive;

class ModulesController extends Controller
{
    public function showModules()
    {
        $modules = ModuleManager::listAllModules();
        return view('admin.modules.modules-list', compact('modules'));
    }

    protected string $modulePath = 'Modules';

    public function activate($moduleName)
    {
        return $this->toggleModule($moduleName, true);
    }

    public function deactivate($moduleName)
    {
        return $this->toggleModule($moduleName, false);
    }

    public function delete($moduleName)
    {
        $path = base_path("{$this->modulePath}/$moduleName");

        if (!File::exists($path)) {
            return back()->with('error', 'Module not found.');
        }

        File::deleteDirectory($path);

        return back()->with('success', "Module '$moduleName' deleted.");
    }

    private function toggleModule($moduleName, bool $status)
    {
        $specPath = base_path("{$this->modulePath}/$moduleName/spec.yml");
        if (!File::exists($specPath)) {
            return back()->with('error', 'Module spec not found.');
        }

        $spec = Yaml::parseFile($specPath);
        $spec['enabled'] = $status;

        File::put($specPath, Yaml::dump($spec, 4));

        $state = $status ? 'activated' : 'deactivated';
        return back()->with('success', "Module '$moduleName' $state.");
    }

    public function upload(Request $request)
    {
        $request->validate([
            'module_zip' => 'required|file|mimes:zip',
        ]);

        $zip = new ZipArchive();
        $path = $request->file('module_zip')->getPathname();

        if ($zip->open($path) === true) {
            // Hole den Modul-Ordnernamen (z. B. "Blog/")
            $rootName = $zip->getNameIndex(0);
            $rootName = trim(Str::before($rootName, '/'));

            if (!$rootName) {
                return back()->withErrors(['ZIP hat kein gültiges Wurzelverzeichnis']);
            }

            $extractPath = base_path('Modules/' . $rootName);

            // Wenn bereits vorhanden: Abbruch
            if (File::exists($extractPath)) {
                return back()->withErrors(['Modul existiert bereits']);
            }

            // Entpacken
            $zip->extractTo(base_path('Modules'));
            $zip->close();

            // Validieren: spec.yml vorhanden?
            $specPath = $extractPath . '/spec.yml';
            if (!File::exists($specPath)) {
                File::deleteDirectory($extractPath);
                return back()->withErrors(['spec.yml fehlt im Modul']);
            }

            // Spezifikation parsen
            $spec = Yaml::parseFile($specPath);
            if (!isset($spec['name'])) {
                File::deleteDirectory($extractPath);
                return back()->withErrors(['spec.yml ist unvollständig (name fehlt)']);
            }

            return back()->with('success', 'Modul erfolgreich installiert: ' . $spec['name']);
        }

        return back()->withErrors(['ZIP konnte nicht geöffnet werden']);
    }
}
