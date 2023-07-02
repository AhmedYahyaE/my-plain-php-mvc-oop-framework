<?php
// AuthenticationMiddleware.php class restricts access of the HTTP request to the controller    // It is used to create 'Protected Routes' (which are routes or pages that only an authenticated user can access), for example, to restrict access to 'profile.php' page unless the user is logged in (authenticated)
// Middleware cycle: Any controller can register a middleware object using registerAMiddlewareObject() method inside its constructor, then in Router.php class and JUST BEFORE calling a certain route dedicated controller class and method, we loop over that controller class registered middlewares and execute() them
// If we want to restrict access to the whole controller methods (actions), we don't pass in any methods (actions) as arguments to the new AuthenticationMiddleware() object constructor e.g. new AuthenticatinMiddleware(), but if we want to restrict access (protected route) to some certain page (or route) e.g. profile.php, we pass in the method (action) name (route or the page name) that renders the page to the constructor of the new AuthenticationMiddleware object constructor as an array e.g. new AuthenticationMiddleware(['profile'])
namespace app\core\middlewares;
use app\core\Application;



class AuthenticationMiddleware extends BaseMiddleware {
    public array $methods = []; // $methods (actions/pages/route) array are controller class methods which render pages (are the 'Protected Routes') that we want restrict access to (unless authenticated)    // value comes from the __construct() method of this class

    public function __construct(array $methods = []) { // If there's a one or more $method (action or page or route) passed in to here (as an ARRAY for sure), the middleware class gets applied on the passed in array of $method (actions/pages/routes), BUT, if there are no $methods (actions or pages or routes) passed in // e.g new AuthenticationMiddleware(), this means that this middleware will apply on the WHOLE controller class methods (actions/pages/routes) i.e. on ALL of the $methods (actions or pages or routes) of the controller class that's using that middleware inside its contsructor function    // this method is called inside controller classes while creating objects out of middleware classes (e.g. inside the __construct() method of AuthenticationController)    // Note: If you want to apply a middleware on multiple methods/actions in the controller, pass in the methods/actions names as an array to the middleware constructor function e.g.    $this->registerAMiddlewareObject(new AuthenticationMiddleware(['profile', 'register', 'login', 'logout']));
        $this->methods = $methods;
    }

    public function execute() { // run the middleware
        if (Application::$app->loggedInUser == null) { // if the there's no logged in user
            /* echo '<pre>', var_dump(Application::$app->baseController->currentlyUsedMethod), '</pre>';
            echo '<pre>', var_dump($this->methods), '</pre>';
            exit; */

            if (empty($this->methods) || in_array(Application::$app->baseController->currentlyUsedMethod, $this->methods)) { //    empty($this->methods)    as we said earlier, allows for applying the middleware on the ALL WHOLE controller class methods as there're are no $methods (actions or pages or routes are) passed in to the middleware, where a middleware object is used but no $methods are passed in (left blank) i.e. something like this:    $this->registerAMiddlewareObject(new AuthenticationMiddleware());
                // throw new \Exception('You don\'t have permission to access this page. This is a PROTECTED ROUTE! Please login! (From my Authentication Middleware!)', 403); // '403' HTTP response status code means 'Forbidden' request (indicates that the server understands the request but refuses to authorize it)

                echo '<b>This is a Protected Route! You must log in! Go to the Homepage and click Login and come back here again! (From my AuthenticationMiddleware.php)</b><br>';
                exit;
            }
        }
    }

}