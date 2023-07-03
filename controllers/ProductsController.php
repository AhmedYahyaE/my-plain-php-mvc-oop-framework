<?php
// The controller of a some certain 'Section' in my website 
namespace app\controllers;
use app\core\BaseController;
use app\models\ProductModel;
use app\core\Request;
use app\core\Response;
use app\core\Application;
use app\core\middlewares\AuthenticationMiddleware;



class ProductsController extends BaseController {
    public function __construct() {
        // Protected Routes: Apply the AuthenticationMiddleware (Protecting Routes) to the controller's methods/actions: create, update and delete i.e. User must log in to access these routes/methods/actions
        $this->registerAMiddlewareObject(new AuthenticationMiddleware(['create', 'update', 'delete']));
    }


    // SELECT database operation & Search bar page (index.php)
    public function index(Request $request, Response $response) {
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('productsLayout.php');

        // Create an instance of the ProductModel
        $productModelObject = new ProductModel();

        $page = 1; // We give $page a default value of 1    // URL is:    GET /products?page=1
        if ($request->isGET() && array_key_exists('page', $request->HTTPRequestBody()) && is_numeric($request->HTTPRequestBody()['page']) && $request->HTTPRequestBody()['page'] != '') { // if (in_array('page', $_GET)) {    // Because 'page' number in the query string parameters will come from the <a> anchor links of Bootsrap Paginaton in `products` index.php    // e.g. GET /products?page=3    //    $request->HTTPRequestBody()['page'] != ''    means it's not blank    // array_key_exists('page', $request->HTTPRequestBody())    means If the 'page' (the word itself) query string parameter is not provided in the URL
            $page = $request->HTTPRequestBody()['page'];
            $page = $page == 0 ? 1 : $page; // if $page is equal to zero 0 (e.g. GET /proudcts?page=0), convert it to one 1 (e.g. GET /proudcts?page=0)
        }

        // Sorting (ordering: ASC or DESC of the index.php page)
        $sortingOptionsArray = ['ASC', 'DESC'];
        
        if ($request->isGET() && array_key_exists('sorting', $request->HTTPRequestBody()) && in_array($request->HTTPRequestBody()['sorting'], $sortingOptionsArray)) { // e.g. GET /products?sorting=ASC
            $sorting = $request->HTTPRequestBody()['sorting'];
        }

        // Application language:
        $languagesArray = ['English', 'Arabic'];

        if ($request->isGET() && array_key_exists('language', $request->HTTPRequestBody()) && in_array($request->HTTPRequestBody()['language'], $languagesArray)) { // e.g. GET /products?language=arabic
            if ($request->HTTPRequestBody()['language'] === 'Arabic') {
                Application::$app->session->setSomethingInSession('language', 'Arabic');
                $response->redirectTo('/products/index');
                exit;
            } else {
                Application::$app->session->removeSomethingFromSession('language');
                $response->redirectTo('/products/index');
                exit;
            }
        }

        if (Application::$app->languages->appLanguage === 'Arabic') {
            $languageFile = Application::$app->languages->loadLanguageFile('products' . DIRECTORY_SEPARATOR . 'index.php');
        }

        $allProductsOrSearchResults = $productModelObject->readRecords($request->HTTPRequestBody()['searchBar'] ?? '', 'title', 'create_date', $sorting ?? 'DESC', true, $page ?? 1); // if there's no page number, we pass a default value of 1 (URL: GET /products?page=1)    // Show all records, unless in case that the search bar is used, then show the searched value results only


        return $this->render('products' . DIRECTORY_SEPARATOR . 'index.php', $this->layout, [
            'searchBarValue'             => $request->HTTPRequestBody()['searchBar'] ?? '', // Pass in the value that's the user is searching for, to be used in the search <form> <input> field "value" attribute in order for the <input> to keep/retain data
            'allProductsOrSearchResults' => $allProductsOrSearchResults,
            'productModelObject'         => $productModelObject, // Pass in the $productModelObject to let the view (`products` index.php) access the pagination properties of the DatabaseModel class
            'languageFile'               => $languageFile ?? null // pass in the language file to the view
        ]);
    }


    // Render create.php page (GET method)    Or    HTML Form submission inside create.php page (POST method)
    public function create(Request $request, Response $response) { // $request and $response objects got passed in from call_user_func() function inside the resolve() function inside Router.php class
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('productsLayout.php');


        // Create an instance of the ProductModel:
        $productModelObject = new ProductModel();
        
        // If the HTTP request type is 'POST', this means the create <form> is submitted and we need to deal with the database through the $productModelObject to INSERT data into database
        if ($request->isPost()) {
            $productModelObject->loadRequestDataToModel($request->HTTPRequestBody());

            if ($productModelObject->validate() && $productModelObject->createRecord()) { // If all are SUCCESSFUL where the validation passes and data is saved into database                
                // Set a Session Flash Message (its frontend <div> is in layout (i.e. productsLayout.php)):
                Application::$app->session->setFlashMessageInSession('createProductSuccessFlashMessage', 'Product created successfully!');
                $response->redirectTo('/products/index'); // redirect to the homePage.php    // the same as:    $response->redirectTo('/products');  or  $response->redirectTo('/products/select');
                exit;
            }
        }


        // If the HTTP request type is GET, meaning render the create.php page:
        return $this->render('products' . DIRECTORY_SEPARATOR . 'create.php', $this->layout, [
            'productModelObject' => $productModelObject // Pass in the $productModelObject object to view (create.php page) to let the <form> <input> fields in view (the HTML "value" attribute of the <input>) keep/retain the <form> submitted data values in case there're errors upon submitting the <form> (and to avoid errors resulting from echo -ing a not yet created variables (in the view i.e. create.php or update.php))
        ]);

    }


    // Render update.php page (GET method)    Or    HTML Form submission inside update.php page (POST method)
    public function update(Request $request, Response $response, string $modelName = null, int $regexID = null) { // $request, $response objects and $regexID (in case that the URL is like /products/53 ) got passed in from call_user_func() function inside the resolve() function inside Router.php class    // $modelName is irrelevant (not important) here, but it's important for all the methods inside the APIController
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('productsLayout.php');
        
        if ($regexID) { // If the URL is like    /products/53    and Router.php class used regular expression and preg_match_all() method to pass in the id (e.g. 53) throug call_user_func() method
            $response->redirectTo('/products/update?id=' . $regexID);
            exit; 
        }

        // Create an instance of the ProductModel
        $productModelObject = new ProductModel();
        $productModelPrimaryKey = $productModelObject->databaseTablePrimaryKey(); // e.g. 'id'

        // Important Note: That $_GET['id'] comes from EITHER inside the GET request from the anchor link e.g.    <a href="update?id=76">     of the Update/Edit button in index.php page (or from the redirectTo in case the URL is like /products/53 and using regex, check the code above if ($regexID))    Or    comes from inside the POST request from the "action" attribute of the update <form method="POST"> of POST method inside update.php page e.g.    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ? >" method="POST">    i.e.    <form action="<?php echo update?id=76 ? >" method="POST">    WHERE IN THIS VERY CASE, the <form> submits BOTH POST & GET requests AT THE SAME TIME (SIMULTANEOUSLY), you can verify this using the $_REQUEST Superglobal which combines both $_GET & $_POST arrays
        $id = $_GET['id'] ?? null;

        // Important Note: That $_GET['id'] comes from EITHER inside the GET request from the anchor link e.g    <a href="update?id=76">     of the Update/Edit button in index.php page    Or    comes from insdie the POST request from the "action" attribute of the update <form> inside update.php page e.g.    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ? >" method="POST">    i.e.    <form action="<?php echo update?id=76 ? >" method="POST">    WHERE IN THIS VERY CASE, the <form> submits BOTH POST & GET requests AT THE SAME TIME (SIMULTANEOUSLY)
        if (!$_GET['id']) { // If the URL doesn't contain an id of the product that they want to UPDATE like in e.g. http://localhost:8000/products/update  or   http://localhost:8000/products/update?id=  or  http://localhost:8000/products/update?id  or  http://localhost:8000/products/update?i  or  http://localhost:8000/products/update?
            $response->redirectTo('/products/index');
            exit; 
        }

        $productModelObject->loadRequestDataToModel($request->HTTPRequestBody()); // Load the HTTP request body to the ProductModel first, in order for it to be able to grab the $id that was provided in the URL or the GET request in general (from the anchor link e.g    <a href="/products/update?id=<?php echo htmlspecialchars($product['id']) ? >"><a/>), and to be able to show "value" HTML attribute in the HTML <form> <input> fields

        // Get the product with the id that was sent in the GET HTTP request:
        $oneProductObject = $productModelObject->readOrGetOneRecord( [$productModelObject->databaseTablePrimaryKey() => $id] ); // Later on, I had to explicitly pass in the $id to readOrGetOneRecord() function because in POST request case (when the update.php <form> is submitted, there's no 'id' can be obtained through the $request->HTTPRequestBody() method, because it grabs POST array elements only, and not $_GET array elements like the 'id' that comes from the URL query string that we need, and this would cause a bug in which the update doesn't happen because (check) in the next couple lines of code, we check if it doesn't find the $oneProductObject, we get redirected to /products/index, hence the update operation doesn't occur)    // Item id was passed to the loadRequestDataToModel() in BaseModel which is naturally inherited by the ProductModel    // get the product with the id provided in the GET request from the Query Parameters String in the URL

        // If we go to database, and don't find a product with the id that was sent in the request
        if (!$oneProductObject) { // $oneProductObject = $productModelObject->readOrGetOneRecord( [$productModelObject->databaseTablePrimaryKey() => $id] ); // Later on, I had to explicitly pass in the $id to readOrGetOneRecord() function because in POST request case (when the update.php <form> is submitted, there's no 'id' can be obtained through the $request->HTTPRequestBody() method, because it grabs POST array elements only, and not $_GET array elements like the 'id' that comes from the URL query string that we need, and this would cause a bug in which the update doesn't happen because (check) in the next couple lines of code, we check if it doesn't find the $oneProductObject, we get redirected to /products/index, hence the update operation doesn't occur)    // Item id was passed to the loadRequestDataToModel() in BaseModel which is naturally inherited by the ProductModel    // get the product with the id provided in the GET request from the Query Parameters String in the URL
            $response->redirectTo('/products/index');
            exit; 
        }

        if ($request->isPost()) { // If the HTTP request type is 'POST', this means the update <form> is submitted and we need to deal with the database through the $productModelObject to INSERT data into database
            $productModelObject->loadRequestDataToModel($request->HTTPRequestBody()); // Load the POST request submitted data in the <input> fields to model

            if ($productModelObject->validate() && $productModelObject->updateRecord($id)) { // If all are SUCCESSFUL where the validation passes and data is saved into database
                // Set an Update Session Flash Message (its frontend <div> is in layout (i.e. productsLayout.php)):
                Application::$app->session->setFlashMessageInSession('updateProductSuccessFlashMessage', 'Product updated successfully!');
                $response->redirectTo('/products/index');
                exit;
            } else { // Validation or Saving data into database FAILS
                return $this->render('products' . DIRECTORY_SEPARATOR . 'update.php', $this->layout, [
                    'productModelObject' => $productModelObject // Pass in the $productModelObject object to view (update.php page) to let the <form> <input> fields in view (the HTML "value" attribute of the <input>) keep/retain the <form> submitted data values in case there're errors upon submitting the <form> (and to avoid errors resulting from echo -ing a not yet created variables (in the view i.e. create.php or update.php))
                ]);
            }
        }

        // If the HTTP request type is GET, meaning render the update.php page:
        return $this->render('products' . DIRECTORY_SEPARATOR . 'update.php', $this->layout, [
            'productModelObject' => $oneProductObject // Pass in the $productModelObject object (as $oneProductObject, NOT as a $productModelObject) to view (update.php) to show information of the item we want to update in e.g.    update.php/id=5    , to be able to show the item information from the database in the HTML "value" attributes in the <input> fields
        ]);
    }


    // delete a record (product)
    public function delete(Request $request, Response $response) { // $request and $response objects got passed in from call_user_func() function inside the resolve() function inside Route.php class
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 


        // Create an instance of the ProductModel
        $productModelObject = new ProductModel();

        if (!$request->HTTPRequestBody()[$productModelObject->databaseTablePrimaryKey()]) { // if the $id is not provided (whatever it's a POST or GET request type), redirect to index page    // is the same as:    if (!id) {
            $response->redirectTo('/products/index');
            exit; 
        }

        // The Delete POST request comes from the Delete Button <form method="POST"> in index.php page, which sends the product id as a type="hidden" <input> field    e.g.    <input type="hidden" name="id" value="<?php echo $product['id'] ? >">
        if ($request->isPOST()) {
            if ($request->HTTPRequestBody()[$productModelObject->databaseTablePrimaryKey()]) { // If the Delete Button <form method="POST"> sent the id in the hidden <input> field
                $productModelObject->deleteRecord($request->HTTPRequestBody()[$productModelObject->databaseTablePrimaryKey()]); // Pass the id value to the delete method

                // Set a Delete Session Flash Message (its frontend <div> is in layout (i.e. productsLayout.php)):
                Application::$app->session->setFlashMessageInSession('deleteProductSuccessFlashMessage', 'Product Deleted Successfully!');
                $response->redirectTo('/products/index');
                exit; 
            }
        }
    }


    // SELECT database operation & AJAX Live Search Bar page (index.php)
    public function ajaxLiveSearch(Request $request) {
        $this->currentlyUsedMethod     = __METHOD__;
        $this->currentlyUsedController = self::class; 
        $this->setLayout('productsLayout.php');


        // Create an instance of the ProductModel
        $productModelObject = new ProductModel();
        $allProductsOrSearchResults = $productModelObject->readRecords($request->HTTPRequestBody()['ajaxsearchvalue'] ?? ''); // Show all records, unless in case that the search bar is used, then show the searched value results only
        $AJAXLiveSearchResultsArray = [];

        foreach ($allProductsOrSearchResults as $searchResultProduct) {
            $AJAXLiveSearchResultsArray[] = $searchResultProduct; // convert array to JSON string
        }

        return json_encode($AJAXLiveSearchResultsArray);
    }

}