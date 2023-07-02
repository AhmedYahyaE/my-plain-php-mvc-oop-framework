<?php
// Application.php Class is the "Service Container" of the application
// The main class - All project classes connect to each other through the Application.php class (like the "Service Container" in Laravel)
// Other classes can access each other through Application class in two ways: using the static $app property which is defined in the Application class constructor, or in the constructor of the Application class, we pass in to every constructor of any class the object of a class that it specifically needs i.e. Dependency Injection (e.g. we passed a Request class object and a Response class object to the Router class constructor)
namespace app\core;
use app\core\database\Database;
use app\models\UserModel;



class Application {
    public static Application $app; // An object of this Application class (global "Service Container" ojbect), it's static in order to be used GLOBALLY by all project classes to connect to each other through it
    public string $appRootPath; // the main folder of the project    // comes from the constructor
    public Request $request;
    public Response $response;
    public View $view;
    public Router $router;
    public Session $session;
    public Database $database; // Database connection
    public BaseController $baseController; // Value comes from __construct() method of this class, then Router.php class assign values
    public Languages $languages;
    public ?UserModel $loggedInUser; // the logged in user object    // Using the nullable operator '?' because it can be null when the user logs out (is a guest)    // this property is initialized inside the __construct() method



    public function __construct() {
        $this->appRootPath = dirname(__DIR__); // the main folder of the project

        $this->request        = new Request();
        $this->response       = new Response();
        $this->view           = new View();
        $this->router         = new Router($this->request, $this->response); // pass a Request class object and a Response class object to the Router class constructor, Or other way to go instead of this, Router class can use the static $app property to access the Request and Response class this way: Application::$app->request->HTTPRequestMethodType();
        $this->session        = new Session();
        $this->database       = new Database();
        $this->baseController = new BaseController();

        self::$app = $this; // An object/instance of this Application class to be used GLOBALLY in the projet
        $this->languages = new Languages();



        // Setting the logged in user $loggedInUser property depending on $_SESSION (if it exists in $_SESSION) (to be able to use it globally across the application):
        if ($this->session->getSomethingFromSession('loggedInUserID')) { // Which was set in $_SESSION by login() method inside LoginFormModel.php
            $userModelObject = new UserModel(); // creating a UserModel class object to query the database to grab the logged in user by its `id` that is stored in $_SESSION

            // Grabbing the logged in user from the database by its primary key `id`, that is stored in the $_SESSION:
            $this->loggedInUser = $userModelObject->readOrGetOneRecord([  $userModelObject->databaseTablePrimaryKey() => $this->session->getSomethingFromSession('loggedInUserID')  ]); // e.g.    readOrGetOneRecord('id' => 8);

        } else { // the user `id` is not in the $_SESSION, which means they're NOT logged in
            $this->loggedInUser = null;
        }
    }



    public function run() { // Run the whole application (this method is called from index.php (Entry Script))
        echo $this->router->resolve();
    }

}