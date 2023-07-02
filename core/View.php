<?php
// Rendering views happen here, that's why any view page is a View.php class object (    $this    variable inside any view refers to a View.php class object)
namespace app\core;


class View {
    public string $title; // the <title> HTML tag which is echo-ed in any layout page (e.g. mainLayout.php), and is assigned in every view page


    public function renderView($view, $layout, $dataPassedInToView) { // this method is called from BaseController.php class    // $dataPassedToView is data passed in from controller to view
        // IMPORTANT NOTE: calling the renderViewOnly() method must be BEFORE calling the renderLayoutOnly() method because we echo the $title property insdie the $layout, and $title value is assigned inside every $view, so if we call the renderLayoutOnly() method before renderViewOnly() method, by then $title would have not been assiged yet, and this results in an error:
        $viewFileContent   = $this->renderViewOnly($view, $dataPassedInToView); // getting the buffered $view ($view is the {{content}} placeholder inside the $layout)
        $layoutFileContent = $this->renderLayoutOnly($layout); // getting the buffered $layout

        // Replacing the buffered $layout with the buffered $view ($view is the {{content}} placeholder inside the $layout e.g. Check productsLayout.php and mainLayout.php):
        return str_replace('{{content}}', $viewFileContent, $layoutFileContent); // Note: echo-ing the view happens inside run() method inside Application.php class
    }


    public function renderViewOnly($view, $dataPassedInToView) { // This methods prepares $dataPassedInToView passed in from controller to view, and renders the $view without $layout    // this method is called from this class inside renderView() method    // $dataPassedInToView is data passed in from controller to view
        foreach($dataPassedInToView as $key => $value) {
            $$key = $value;
        }
        
        // Storing the $view ($view is the {{content}} placeholder inside the $layout) in a BUFFER in order NOT for the $view to be printed out on the screen immediately when we require_once- it, and then we would be able to replace the {{content}} placeholder inside $layout with it: 
        ob_start();
        require_once Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view;
        return ob_get_clean();
    }


    public function renderLayoutOnly($layout) { // this method is called from this class inside renderView() method
        $testPassedIndataToLayout = 'testPassedIndata';
        ob_start();
        require_once Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout;
        return ob_get_clean();
    }


    public function renderViewDirectly($viewPathInsideViewsFolder) { // render a view completely (with its own layout and view)    // this method is used inside the notFound404Page() method inside SiteController.php class
        return require_once Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $viewPathInsideViewsFolder;
    }

}