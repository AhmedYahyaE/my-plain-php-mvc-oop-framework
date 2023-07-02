<?php
// Choosing the language based on $_SESSION
namespace app\core;
use app\core\Application;



class Languages {
    public string $appLanguage = 'English'; // comes from $_SESSION from this class's constructor function


    public function __construct() {
        if (Application::$app->session->getSomethingFromSession('language') === 'Arabic') {
            $this->appLanguage = Application::$app->session->getSomethingFromSession('language'); // Set the    public string $appLanguage    property
        }
    }


    public function loadLanguageFile(string $languageFile): array { // this method is called in controllers
        return require_once '..' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . $this->appLanguage . DIRECTORY_SEPARATOR . $languageFile;
    }
}