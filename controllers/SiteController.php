<?php
// General site (application) controller which is responsible for the general web pages (not specific website sections pages like homePage.php).    // AuthenticationController is responsible for Authentication in login.php and register.php
namespace app\controllers;
use app\core\BaseController;
use app\core\Application;


class SiteController extends BaseController {
    public function homePage() {
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('mainLayout.php'); // setting the $layout property of the parent BaseController.php class if you want to specify a certain layout (otherwise it's 'mainLayout.php' as a default value if left unspecified)    // you can remove this line of code as mainLayout.php is set as a default value in BaseController.php


        return $this->render('homePage.php', $this->layout, ['testDataPassedInToView' => 'This is test data in a Variable Variables form to be passed to view']); // You can pass data from controller to view from here as a third parameter (using Variable Variables or compact() method) in the render() method
    }


    public function notFound404Page() { // this method is called from resolve() method in Router class
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        // No layout!

        Application::$app->response->setHTTPResponseStatusCode(404);
        return Application::$app->view->renderViewDirectly('404 page' . DIRECTORY_SEPARATOR . 'index.html'); // UNLIKE all other methods, here we render a complete view directly and not through the BaseController.php (a view which has its OWN layout and view)
    }

}