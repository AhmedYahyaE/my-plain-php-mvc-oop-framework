<?php
namespace app\models;
use app\core\database\DatabaseModel;


// UserModel.php extends BOTH DatabaseModel AND BaseModel (DatabaseModel.php IN TURN extends BaseModel.php)
class UserModel extends DatabaseModel { // Note: DatabaseModel IN TURN extends BaseModel class
    // The `status` column in database which denotes the user status whether active, inactive or deleted:
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const STATUS_DELETED  = 2;


    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public string $firstname = '';
    public string $lastname = '';
    public int    $status = self::STATUS_INACTIVE;


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
        return ['email', 'password', 'firstname', 'lastname', 'status'];
    }



    // The desired VALIDATION rules for every <input> field in the HTML <form>:
    public function inputFieldsHTMLElementsRules(): array {
        $this->method = __METHOD__;
        $this->model  = self::class;

        /* Pattern is:
                [
                    input field name Or $property' => [Rules]
                ]
        */
        return [
            'email'           => [  self::RULE_REQUIRED,  self::RULE_EMAIL,  self::RULE_UNIQUE  ],
            'password'        => [  self::RULE_REQUIRED,  [self::RULE_MINIMUM, 'minimum' => 8],  [self::RULE_MAXIMUM, 'maximum' => 15]  ], // Complex Rule spelling must match its spelling inside errorMessagesForInputFieldsRules() method
            'confirmPassword' => [  self::RULE_REQUIRED,  [self::RULE_MUST_MATCH, 'must_match' => 'password']  ], // Complex Rule spelling must match its spelling inside errorMessagesForInputFieldsRules() method
            'firstname'       => [  self::RULE_REQUIRED  ],
            'lastname'        => [  self::RULE_REQUIRED  ]
        ];
    }


    public function createRecord() {
        $this->status   = self::STATUS_INACTIVE; // A default value
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Storing the HASHED password in the database table
        
        return parent::createRecord();
    }


    public function readRecords($searchBarValue = '', $columnToSearchWith = '', $orderByColumn = '', $ordering = '',  bool $usePaginationOrNot = true, int $currentPageNumber = 1) { // $searchBarValue comes from the $loadRequestDataToModel() method insdie BaseModel.php
        return parent::readRecords($searchBarValue, 'title', 'create_date', 'DESC'); // Search in the 'title' column, order by the `create_date`, and the ordering is `DESC`
    }


    public function readOrGetOneRecord(array $sqlWHEREclauseArray = []) { // e.g.    SELECT * FROM products WHERE id = 5 AND language = German;
        if ($sqlWHEREclauseArray) { // if there's something passed in to search with it
            return parent::readOrgetOneRecord($sqlWHEREclauseArray);
        } else { // there's nothing passed in to search with, then search with the table Primary Key (`id` most of times)
            return parent::readOrgetOneRecord([$this->databaseTablePrimaryKey() => $this->{$this->databaseTablePrimaryKey()}]); // The value of the $this->id comes from the loadRequestDataToModel() method in BaseModel.php
        }
    }


    public function deleteRecord($primaryKeyValue) {
        $productToBeDeletedObject = parent::readOrGetOneRecord([ $this->databaseTablePrimaryKey() => $primaryKeyValue ]);

        if ($productToBeDeletedObject->image_path) { // Check if the record (e.g. product) to be deleted has an uploaded image in the first place (by checking if it has an image path stored in database) (Because a record (e.g. product) can be without an uploaded image in the first place. This check is important to avoid errors)
            $this->deleteFileFromServer($productToBeDeletedObject->image_path); // Delete the image when deleting a complete product (using the Delete Button)
        }

        return parent::deleteRecord($primaryKeyValue);
    }

}