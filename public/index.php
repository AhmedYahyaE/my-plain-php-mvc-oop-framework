<?php
// Entry Point / Entry Script page (for "bootstrapping" the application) (All requests start here. EVERYTHING starts here. Web server root domain/directory/path here). index.php is set in an isolated private 'public' folder (Web Accessible Folder) for security ('public' folder doesn't contain any other files than index.php and the web server root directory is that public folder to prevent reaching; this is for security to prevent users from reaching to the project files)
// All the Application Routes
use app\core\Application;
use app\controllers\SiteController;
use app\controllers\ProductsController;
use app\core\CustomDotEnvFileReading;
use app\controllers\AuthenticationController;
use app\controllers\APIController;


// 'require'-ing my custom Autoloading.php Class:
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloading.php';

// Creating an instance of the global Application.php class (Service Containter):
$app = new Application(); // an instance of the major project Application class


// Application Routes

// ALIAS -es routes: '/' and '/index' and '/home' and '/homepage' are all the same ALIAS routes:
$app->router->storeGETRoutes('/'        , [SiteController::class, 'homePage']);
$app->router->storeGETRoutes('/index'   , [SiteController::class, 'homePage']);
$app->router->storeGETRoutes('/home'    , [SiteController::class, 'homePage']);
$app->router->storeGETRoutes('/homepage', [SiteController::class, 'homePage']);
$app->router->storeGETRoutes('/profile', [AuthenticationController::class, 'profile']);
$app->router->storeGETRoutes( '/register', [AuthenticationController::class, 'register']);
$app->router->storePOSTRoutes('/register', [AuthenticationController::class, 'register']);
$app->router->storeGETRoutes( '/login', [AuthenticationController::class, 'login']);
$app->router->storePOSTRoutes('/login', [AuthenticationController::class, 'login']);
$app->router->storeGETRoutes('/logout', [AuthenticationController::class, 'logout']);
$app->router->storeGETRoutes( '/products/index' , [ProductsController::class, 'index'] );
$app->router->storeGETRoutes( '/products/' , [ProductsController::class, 'index'] ); // Alias
$app->router->storeGETRoutes( '/products/select', [ProductsController::class, 'index'] );
$app->router->storeGETRoutes( '/products'       , [ProductsController::class, 'index'] );
$app->router->storeGETRoutes( '/products/create', [ProductsController::class, 'create']);
$app->router->storePOSTRoutes('/products/create', [ProductsController::class, 'create']);
$app->router->storeGETRoutes( '/products/show'  , [ProductsController::class, 'show']  );
$app->router->storeGETRoutes( '/products/update', [ProductsController::class, 'update']);
$app->router->storeGETRoutes( '/products/{id}'  , [ProductsController::class, 'update']);
$app->router->storePOSTRoutes('/products/update', [ProductsController::class, 'update']);
$app->router->storePOSTRoutes('/products/delete', [ProductsController::class, 'delete']);
$app->router->storeGETRoutes( '/products/delete', [ProductsController::class, 'delete']);
$app->router->storeGETRoutes( '/products/ajaxlivesearch' , [ProductsController::class, 'ajaxLiveSearch'] ); // AJAX Live Search


// APIs Endpoints:
$app->router->storeGETRoutes('/api/products', [APIController::class, 'index']);
$app->router->storeGETRoutes('/api/products/{id}', [APIController::class, 'index']);


// Run the whole application:
$app->run();