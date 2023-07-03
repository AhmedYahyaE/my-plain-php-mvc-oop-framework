<?php
// A class that has all the $routes array, it takes the requested route (a controller and its method) from index.php through the Application class and calls a controller method
// It's very important to set the web server root directory to the folder that contains 'index.php' in order for resolve() method to work properly (i.e. Start your server from this folder: C:\Users\Monarch\app\public)
namespace app\core;


class Router {
    protected array $routes; // An array which contains all the project routes. Routes (HTTP Request method, controller, view) come from index.php (Entry Script)
    public Request  $request;  // passed in to Router constructor from the constructor of Application class
    public Response $response; // passed in to Router constructor from the constructor of Application class


    public function __construct(Request $request, Response $response) {
        $this->request  = $request; // passed from the Application class constructor
        $this->response = $response; // passed from the Application class constructor
    }


    // storing the routes of 'GET' HTTP Request method in the $routes['GET'] array    // this method is called from index.php (Entry Script)
    public function storeGETRoutes($path, $controllerAndMethodArray) {
        $this->routes['GET'][$path] = $controllerAndMethodArray;
        // echo '<pre>', var_dump($this->routes), '</pre>';
    }

    // storing the routes of 'POST' HTTP Request method in the $routes['POST'] array    // this method is called from index.php (Entry Script)
    public function storePOSTRoutes($path, $controllerAndMethodArray) {
        $this->routes['POST'][$path] = $controllerAndMethodArray;
        // echo '<pre>', var_dump($this->routes), '</pre>';
    }


    // It's very important to set the web server root directory to the folder that contains 'index.php' in order for resolve() method to work properly (i.e. Start your server from this folder: C:\Users\Monarch\app\public)
    public function resolve() { // this method is called from run() method in Application class
        $HTTPRequestMethodType = $this->request->HTTPRequestMethodType(); // What is the HTTTP request method of the route?
        $prepared_url = $this->request->prepareURL();
        $controllerAndMethodArray = $this->routes[$HTTPRequestMethodType][$prepared_url] ?? false; // decide which $controllerAndMethodArray    // check if the URL that the user typed is stored into our $routes array or not, if it exists, take its value (the corresponding method of a controller), if it doesn't exist, make its value 'false'

        if ($controllerAndMethodArray === false) { // if the $prepared_url doesn't exist in our $routes array
            // Note: We need to handle MVC routes (URLs) if they have query string parameters: e.g.    /products/53    which is    /products/{id}
            // Reformed the Regular Expression to be able to handle MVC routes with query string parameters like    /api/products/27    too:
            if (preg_match_all('/^\/(\w+)\/(\w+\/)?(\d+)$/', $prepared_url, $matches)) { // e.g.    /products/53    or    /api/products/72    where ^ denotes the beginning position of the string, $ denotes the end position of string, () denotes capturing groups to be able to get them later, \w+ denotes one word character or more, \d+ denotes one digint or more, \ escapes is used to escape characters that have significant meanings to regular expression such as delimiters '/' and metacharacters                    // echo '<pre>', var_dump($matches), '</pre>';
                $regexPath = preg_replace('/^(\/(?:\w+)\/(?:\w+\/)?)(?:\d+)$/', '$1{id}', $prepared_url); // e.g. From    products/53    to    products/{id}    Or from    /api/products/27    to    /api/products/{id}

                $controllerAndMethodArray = $this->routes[$HTTPRequestMethodType][$regexPath] ?? false;

                if ($controllerAndMethodArray) {
                    // Start of Activating middlewares script (JUST BEFORE calling the route's dedicated controller and method):
                    // Middlewares: A middleware is a special class that lives between the HTTP request and the controller, so a middleware can restrict the access of the HTTP request to the controller, or modify the return and response.
                    // Middleware cycle: Any controller can register a middleware object using registerAMiddlewareObject() method inside its constructor, then in Router.php class and JUST BEFORE calling a certain route dedicated controller class and method, we loop over that controller class registered middlewares and execute() them
                    Application::$app->baseController->currentlyUsedController = $controllerAndMethodArray[0];
                    Application::$app->baseController->currentlyUsedMethod     = $controllerAndMethodArray[1];

                    // DocBlock: (to let IDE be able to reference $currentlyUsedControllerObject)
                    /** @var \app\core\BaseController $currentlyUsedControllerObject */
                    $currentlyUsedControllerObject = new Application::$app->baseController->currentlyUsedController;
                    // echo '<pre>', var_dump($currentlyUsedControllerObject), '</pre>';
                    foreach ($currentlyUsedControllerObject->getRegisteredMiddlewareObjectsArray() as $middlewareObject) {
                        $middlewareObject->execute();
                    }
                    // End of Activating middlwares script

                    // return call_user_func([new $controllerAndMethodArray[0](), $controllerAndMethodArray[1]], $this->request, $this->response, $matches[2]); // Passing in $matches which is the `id`    // Passing in $request and $response objects to whatever methods of controllers to allow them to use inside their controllers
                    return call_user_func([new $controllerAndMethodArray[0](), $controllerAndMethodArray[1]], $this->request, $this->response, trim($matches[2][0], '/'), $matches[3][0]); // Amended from $matches[2] to $matches[3] to enable us to handle routes like    GET /api/products/27    // Passing in trim($matches[2][0], '/') which is the model name (e.g. products)    // Passing in $matches[3][0] which is the `id`    // Passing in $request and $response objects to whatever methods of controllers to allow them to use inside their controllers
                }
            }

            return call_user_func([new \app\controllers\SiteController(), 'notFound404Page']);

        } else { // if the $prepared_url exists in our $routes array, call the corresponding method of the controller

            preg_match_all('/^\/(?:\w+)\/(\w+)$/', $prepared_url, $matches);

            // Start of Activating middlewares script (JUST BEFORE calling the route's dedicated controller and method):
            // Middlewares: A middleware is a special class that lives between the HTTP request and the controller, so a middleware can restrict the access of the HTTP request to the controller, or modify the return and response.
            // Middleware cycle: Any controller can register a middleware object using registerAMiddlewareObject() method inside its constructor, then in Router.php class and JUST BEFORE calling a certain route dedicated controller class and method, we loop over that controller class registered middlewares and execute() them
            Application::$app->baseController->currentlyUsedController = $controllerAndMethodArray[0];
            Application::$app->baseController->currentlyUsedMethod     = $controllerAndMethodArray[1];
            // echo '<pre>', var_dump(Application::$app->baseController), '</pre>';

            // DocBlock: (to let IDE be able to reference $currentlyUsedControllerObject)
            /** @var \app\core\BaseController $currentlyUsedControllerObject */
            $currentlyUsedControllerObject = new Application::$app->baseController->currentlyUsedController;
            // echo '<pre>', var_dump($currentlyUsedControllerObject), '</pre>';
            foreach ($currentlyUsedControllerObject->getRegisteredMiddlewareObjectsArray() as $middlewareObject) {
                $middlewareObject->execute();
            }
            // End of Activating middlwares script

            return call_user_func([new $controllerAndMethodArray[0](), $controllerAndMethodArray[1]], $this->request, $this->response, $matches[1][0] ?? null); // Passing in $matches[1] which is the model name in case that the prepared_url is like '/api/products/'    // Passing in $request and $response objects to whatever methods of controllers to allow them to use inside their controllers
        }
    }

}