<?php
// Middleware cycle: Any controller can register a middleware object using registerAMiddlewareObject() method inside its constructor, then in Router.php class and JUST BEFORE calling a certain route dedicated controller class and method, we loop over that controller class registered middlewares and execute() them
// This is the abstract base class for all middleware classes (inherited by all middleware classes), which contains the major methods and properties that are needed in ALL middleware classes
namespace app\core\middlewares;



abstract class BaseMiddleware {

    abstract public function execute(); // run the logic of the middleware

    // Middleware cycle: Any controller can register a middleware object using registerAMiddlewareObject() method inside its constructor, then in Router.php class and JUST BEFORE calling a certain route dedicated controller class and method, we loop over that controller class registered middlewares and execute() them
}