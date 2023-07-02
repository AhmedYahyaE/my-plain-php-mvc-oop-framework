<?php
// An abstract base Controller class that is inherited by all controllers
namespace app\core;
use app\core\middlewares\BaseMiddleware;



class BaseController {
    public string $currentlyUsedMethod; // Which method (action) of a child controller is used currently (comes from the child controller method in-use)    // Value can come from every method in a controller class, or Router.php class inside resolve() method will assign it to the Application.php $baseController class property ONLY and not to the BaseController class property
    public string $currentlyUsedController; // Which child controller is used currently (comes from the child controller method in-use)    // Value can come from the __construct() method of this class, or from every method in a controller class, or Router.php class inside resolve() method will assign it to the Application.php $baseController class property ONLY and not to the BaseController class property

    public string $layout; // comes from setLayout() method called inside controller methods in-use

    // DocBlock:
    /** @var \app\core\middlewares\BaseMiddleware[] $middlewareObjectsArray is an ARRAY of BaseMiddleware class objects (ALL middleware classes extends the BaseMiddleware class) */
    protected array $middlewareObjectsArray = []; // an array of all of the different middleware classes OBJECTS used in all/any controller classes
    // Middleware cycle: Any controller can register a middleware object using registerAMiddlewareObject() method inside its constructor, then in Router.php class and JUST BEFORE calling a certain route dedicated controller class and method, we loop over that controller class registered middlewares and execute() them


    protected function setLayout($layout) {
        $this->layout = $layout;
    }

    protected function render($view, $layout, $dataPassedInToView = []) { // this method is called inside all controllers    // $dataPassedInToView is data passed in from controller to view
        return Application::$app->view->renderView($view, $this->layout, $dataPassedInToView);
    }

    public function registerAMiddlewareObject(BaseMiddleware $middlewareObject) { // This method accepts a BaseMiddleware object as an argument    // this setter function is called inside any controller classes that need to use different kinds of middlewares
        $this->middlewareObjectsArray[] = $middlewareObject;
    }

    public function getRegisteredMiddlewareObjectsArray(): array { // Getter function
        return $this->middlewareObjectsArray;
    }

}