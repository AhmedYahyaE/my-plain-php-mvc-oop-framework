<?php
// This controller class is responsible for anything that needs AUTHENTICATION like registration and logging in (i.e. Register a new user / create an account in register.php and logging in in login.php)
namespace app\controllers;
use app\core\BaseController;
use app\core\Request;
use app\core\Response;
use app\models\UserModel;
use app\core\Application;
use app\models\LoginFormModel;
use app\core\middlewares\AuthenticationMiddleware;



class AuthenticationController extends BaseController {
    // Applying the AuthenticationMiddleware on the AuthenticationController
    public function __construct() {
        // If we want to restrict access to the whole controller methods (actions), we don't pass in any methods (actions) as arguments to the new AuthenticationMiddleware() object constructor e.g. new AuthenticatinMiddleware(), but if we want to restrict access (protected route) to some certain page (or route) e.g. profile.php, we pass in the method (action) name (route or the page name) that renders the page to the constructor of the AuthenticationMiddleware object as an array e.g. new AuthenticationMiddleware(['profile'])
        $this->registerAMiddlewareObject(new AuthenticationMiddleware(['profile'])); // We pass in the profile() method (action) that renders the profile.php page as an array to the new AuthenticationMiddleware object constructor function    // Note: If you want to apply a middleware on multiple methods/actions in the controller, pass in the methods/actions names as an array to the middleware constructor function e.g.    $this->registerAMiddlewareObject(new AuthenticationMiddleware(['profile', 'register', 'login', 'logout']));
    }


    // Render register.php page (GET method)    Or    HTML Form submission inside register.php page (POST method)
    public function register(Request $request, Response $response) { // $request and $response objects got passed in from call_user_func() function inside the resolve() function inside Router.php class
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('mainLayout.php');


        // Create an instance of the UserModel:
        $userModelObject = new UserModel();
        
        if ($request->isPost()) {
            // echo 'HTTP request type is \'POST\' from <b>ProductsController.php</b><br>';
            $userModelObject->loadRequestDataToModel($request->HTTPRequestBody());

            if ($userModelObject->validate() && $userModelObject->createRecord()) { // If all are SUCCESSFUL where the validation passes and data is saved into database
                // Set Session Flash Message (its frontend <div> is in layout (i.e. productsLayout.php)):
                Application::$app->session->setFlashMessageInSession('registerANewUserSuccessFlashMessage', 'Thanks for registration with us! Please Use your new credentials: email and password to login!');
                $response->redirectTo('/');
                exit;
            }
        }


        return $this->render('register.php', $this->layout, [
            'userModelObject' => $userModelObject // Pass in the $userModelObject object to view (register.php) to let the <form> <input> fields in view (the HTML "value" attribute of the <input>) keep/retain the <form> submitted data values in case there're errors upon submitting the <form> (and to avoid errors resulting from echo -ing a not yet created variables (in the view i.e. register.php))
        ]);
    }


    // Render login.php page (GET method)    Or    HTML Form submission inside login.php page (POST method)
    public function login(Request $request, Response $response) { // $request and $response objects got passed in from call_user_func() function inside the resolve() function inside Router.php class
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('mainLayout.php');


        // Create an instance of the loginFormModelObject:
        $loginFormModelObject = new LoginFormModel();
        
        if ($request->isPost()) { // If the login.php <form> is submitted:
            $loginFormModelObject->loadRequestDataToModel($request->HTTPRequestBody());

            if ($loginFormModelObject->validate() && $loginFormModelObject->login()) { // If all are SUCCESSFUL where the validation passes and data is saved into database
                // Set a Session Flash Message (its frontend <div> is in layout (i.e. productsLayout.php)):
                Application::$app->session->setFlashMessageInSession('successfulLoginFlashMessage', 'You\'re logged in!');
                $response->redirectTo('/');
                return;
                exit;
            }
        }


        return $this->render('login.php', $this->layout, [
            'loginFormModelObject' => $loginFormModelObject // Pass in the $loginFormModelObject object to view (register.php) to let the <form> <input> fields in view (the HTML "value" attribute of the <input>) keep/retain the <form> submitted data values in case there're errors upon submitting the <form> (and to avoid errors resulting from echo -ing a not yet created variables (in the view i.e. register.php))
        ]);
    }


    public function logout(Request $request, Response $response) { // $request and $response objects got passed in from call_user_func() function inside the resolve() function inside Router.php class
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        // $this->setLayout('mainLayout.php');


        Application::$app->session->removeSomethingFromSession('loggedInUserID'); // Remove the $loggedInUser from $_SESSION
        Application::$app->loggedInUser = null; // Assign the $loggedInUser property of the Application.php class to 'null'
        Application::$app->session->setFlashMessageInSession('successfulLogoutFlashMessage', 'You\'ve logged out! Thanks for visiting us!'); // Set a successful logout message
        $response->redirectTo('/');
        exit; 
    }


    public function profile() { // profile.php page is a 'Protected Route', that's why we placed the profile() method inside the AuthenticationController, and not inside the SiteController, to apply the AuthenticationMiddleware    // Render register.php page (GET method)
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('mainLayout.php');


        return $this->render('profile.php', $this->layout);
    }

}