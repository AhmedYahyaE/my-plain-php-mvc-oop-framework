<?php
// This model class is used JUST ONLY for a user login (loggin in a user) .. Check AuthenticationController and login.php
// This model class properties matches the login.php page <input> fields names
namespace app\models;
use app\core\database\DatabaseModel;
use app\core\Application;


// LoginFormModel.php extends BOTH DatabaseModel AND BaseModel (because DatabaseModel.php IN TURN extends BaseModel.php)
class LoginFormModel extends DatabaseModel { // Note: DatabaseModel IN TURN extends BaseModel class    
    public string $email    = '';
    public string $password = '';


    public function databaseName(): string {
        return 'my_plain_php_mvc_oop_framework';
    }
    public function databaseTableName(): string {
        return 'users';
    }
    public function databaseTablePrimaryKey(): string {
        return 'id';
    }
    public function databaseTableColumnNamesOrModelClassProperties(): array {
        return ['email', 'password'];
    }


    // The desired VALIDATION rules for every <input> field in the HTML <form>:
    public function inputFieldsHTMLElementsRules(): array {
        $this->method = __METHOD__;  // setting the $method property of the parent BaseModel.php class
        $this->model  = self::class; // setting the $model  property of the parent BaseModel.php class

        /* Pattern is:
                [
                    input field name Or $property' => [Rules]
                ]
        */

        // Note: Complex Rule spelling must match its spelling inside errorMessagesForInputFieldsRules() method
        return [
            'email'           => [  self::RULE_REQUIRED,  self::RULE_EMAIL/*,  self::RULE_LOGIN*/ ],
            'password'        => [  self::RULE_REQUIRED/*,  self::RULE_PASSWORD_LOGIN*/  ]
        ];
    }


    public function login(): bool { // this method is called from inside login() method in AuthenticationController.php
        // Create an instance of the UserModel class to query the database to search for a user in the `users` database table with the same credentials (match them) that the user trying to login has submitted:
        $userModelObject = new UserModel();
        $loggingInUser   = $userModelObject->readOrGetOneRecord(['email' => $this->email]);

        if (!$loggingInUser) { // If the email of the user submitted trying to login in doesn't exist in our database table
            $this->directlyAddToErrorsArray('email', 'User with this email does NOT exist');
            return false;
        }

        // If the user trying to login submitted email exists in our `users` database table:
        if (!password_verify($this->password, $loggingInUser->password)) { // If the user trying to login submitted password doesn't match the password of the found user's email in the `users` database table
            $this->directlyAddToErrorsArray('password', 'Password is incorrect'); 
            return false;
        }

        // Setting the logged in user primary key e.g. `id` value in $_SESSION to be used in Application.php class to set the $loggedInUser property:
        Application::$app->session->setSomethingInSession('loggedInUserID', $loggingInUser->{$this->databaseTablePrimaryKey()});
        
        return true;
    }

}