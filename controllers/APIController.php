<?php
namespace app\controllers;
use app\core\BaseController;
use app\models\ProductModel;
use app\core\Request;
use app\core\Response;
use app\core\Application;



class APIController extends BaseController {

    // Get ALL items Endpoint (GET Request) e.g. Route/URL like:    GET /api/products    Or    Get a single item Endpoint (GET request) e.g. Route/URL like:    GET /api/products/79
    public function index(Request $request, Response $response, string $modelName = null, int $id = null) { // all arguments were passed in from resolve() method inside Router.php class
        $this->currentlyUsedMethod     = __METHOD__; 
        $this->currentlyUsedController = self::class; 


        $modelName = '\app\models\\' . ucfirst(trim($modelName, 's')) . 'Model'; // Adding the namespace of models (i.e. \app\models\...)     // Removing the plural 's' from model name (e.g. from products to product)

        // Create an instance of the ProductModel
        $modelObject = new $modelName();

        if ($id) {
            $oneModelObject = $modelObject->readOrGetOneRecordForAPI( [$modelObject->databaseTablePrimaryKey() => $id] ); // get the product with the id provided in the GET request from the Query String Parameters in the URL
             echo json_encode($oneModelObject); // convert array to JSON
        } else {
            $allModelRecords = $modelObject->readRecords('', '', '', '', false, 1);
            echo json_encode($allModelRecords); // convert array to JSON
        }
    }

}