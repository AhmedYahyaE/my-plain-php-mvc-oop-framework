<?php
// This abstract class is to be inherited by all models to deal with the database operations through it
// All models extends this model class, and this model class IN TURN extends the BaseModel.php class, meaning all models extends BOTH BaseModel.php and DatabaseModel.php classes
// Any model in my projects extends BOTH DatabaseModel class AND BaseModel class
// This class contains the main database methods that is COMMON to ALL models (e.g. ProductModel.php)
namespace app\core\database;
use app\core\BaseModel;
use app\core\Application;
use app\core\HelperMethods;
use \PDO;



abstract class DatabaseModel extends BaseModel {
    // Pagination properties (used by readRecords() method inside this class):
    public int $currentPageNumber = 1; // default value of 1
    public int $recordsLimitNumberPerPage = 5; // default value of
    public int $numberOfPageLinkButtonsWeDesireToShow = 4; // default value of
    public int $totalNumberOfRecords;
    public int $numberOfPages = 1; // must be at least one 1 page    // default value of 1
    public int $SQLOffset;


    // Database Information of the model:
    abstract public function databaseName(): string;
    abstract public function databaseTableName(): string;
    abstract public function databaseTablePrimaryKey(): string;
    abstract public function databaseTableColumnNamesOrModelClassProperties(): array; // the <input> fields or model class properties that represent the database table columns



    public function fileUploadToServer($uploadedFileProperty, &$filePathInDatabaseProperty) { // this method is called in models which have an <input> field for uploading files:
        // Handling uploaded file, if any: First, check if the model/table/<form> has an <input> field for uploading files or not in the first place, then second, check if there's a file is uploaded through that <input type="file> or not (left out blank):
        if (empty($this->errors)) { // Before anything, make sure there'r no errors:
            if ($uploadedFileProperty) { // Check if the model/table/<form> has an <input> field for uploading files or not IN THE FIRST PLACE    // Check if a file is uploaded or not (because the file/image is not required to submit the <form>, where a user can create an item without uploading an image/file, because in case of an item UPDATE, the user can update anything but doesn't update the file/image, so then the $uploadedFileProperty would be empty)
                if ($uploadedFileProperty['tmp_name']) { // Check if there's a file is uploaded from the <form>    // e.g.   $this->uploadedImage['tmp_name']
                    // Create a directory/folder for storing images on the server inside the Web Accessible Folder i.e. 'public' folder to be able to show them to users (like CSS, JS, ...)
                    if (!is_dir(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploadedFiles')) { // If there is no 'uploadedFiles' folder yet devoted for uploaded files (at the beginning of the project while there's no single file uploaded yet)
                        mkdir(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploadedFiles'); // Create the 'uploadedFiles' folder for the first time (at the beginning of the project)
                    }

                    // In case of UPDATE operation: Check if there's an already existing file path stored already for that uploaded file (in case of UPDATE operation, not CREATE), then delete the existing old image from server before creating (inserting the new one) (Note: Folders containing old images are left and don't get deleted!)
                    if ($filePathInDatabaseProperty) { // In UPDATE case, if there's an already existing file path for the file stored in the database table, delete that old file
                        $folderContainingTheOldImageName = dirname(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $filePathInDatabaseProperty);
                        
                        // Note: Folders containing old images are left and don't get deleted!
                        unlink(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $filePathInDatabaseProperty); // Delete the existing old file from the server (from the 'public\uploadedFiles' folder)
                        rmdir($folderContainingTheOldImageName); // Delete the folder that was containing the old ALREADY DELETED image
                    }

                    $filePathInDatabaseProperty = 'uploadedFiles' . DIRECTORY_SEPARATOR . HelperMethods::generateRandomString(8) . DIRECTORY_SEPARATOR . $uploadedFileProperty['name']; // Assign the new file path for the model class property that represent the file path database column that will be inserted into database table (e.g.    $this->image_path = 'uploadedImages\Cf9ZtdIj\some_image.jpg';    )

                    // Note: We store images in a path with the pattern of: app\public\uploadedFiles . $filePathInDatabaseProperty
                    mkdir(dirname(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $filePathInDatabaseProperty)); // Create a folder/directory for the new uploaded image (WHETHER the operation is CREATE or UPDATE) with a path that is the SAME as the file path that we previously assigned for the model class property that represent the file path that will be stored into database table    // The function dirname() is used to get the parent folder path that contains the uploaded file
                    move_uploaded_File($uploadedFileProperty['tmp_name'], Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $filePathInDatabaseProperty); // Move the uploaded file from the temporary path where the web server stored the file into ($_FILES['fileInputFieldNameAttribute]['tmp_name']), to the path we assigned before and rename the file (BOTH MOVE AND RENAME SIMULTANEOUSLY!!)
                
                } else { // There is no file uploaded (whether in create or update cases)

                }
            } else { // the model/table/<form> does NOT have an <input> field for uploading files
                echo 'Model/table/form doesn NOT have input field for uploading files. from <b>DatabaseModel.php</b><br>';
            }
        }
    }



    // This method deletes the uploaded file when the user deletes a complete record (using the Delete Button) (e.g. delete a produtct)
    public function deleteFileFromServer($productToBeDeletedPath) {
        if ($productToBeDeletedPath) { // First, check if the there's a file path stored in database for that file to be deleted
            $folderContainingTheOldImageName = dirname(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $productToBeDeletedPath); // the folder that contains the uploaded file that is to be deleted (that folder has a random name from the generateRandomString() method)

            if (file_exists(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $productToBeDeletedPath)) { // Check if the uploaded file exists
                unlink(Application::$app->appRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $productToBeDeletedPath); // delete the file
                if (is_dir($folderContainingTheOldImageName)) { // Check if the folder that contained the deleted uploaded file exists (that folder has a random name from the generateRandomString() method)
                    rmdir($folderContainingTheOldImageName);
                }
            }
        }
    }


    // CREATE
    public function createRecord() {
        $columnNames = $this->databaseTableColumnNamesOrModelClassProperties(); // e.g.    [ 'title', 'description', 'price', 'image_path']
        $statement = Application::$app->database->pdo_connection->prepare("INSERT INTO {$this->databaseName()}.{$this->databaseTableName()} (" . implode(', ', $columnNames) . ") VALUES (" . implode(', ', array_map(fn ($columnName) => ":$columnName", $columnNames)) . ");");
        
        // Binding the Named Parameters:
        foreach ($columnNames as $columnName) {
            $statement->bindValue(":$columnName", $this->$columnName); // e.g.    $statement->bindValue(:title, 'Chemistry')
        }

        $statement->execute();

        return true;
    }



    // READ (and SEARCH)
    public function readRecords($searchBarValue = '', $columnToSearchWith = 'title', $orderByColumn = 'create_date', $ordering = 'DESC', bool $usePaginationOrNot = true, int $currentPageNumber = 1) { // e.g.    SELECT * FROM products WHERE title LIKE iphone ORDER BY create_date DESC;    // $currentPageNumber is used for Pagination (value comes from controllers)    // A default value of $currentPageNumber is 1 (e.g. URL is:    GET /products?page=3    )
        // Pagination script (using SQL LIMIT & OFFSET):

        // Getting the total number of records in the database table:
        $statement = Application::$app->database->pdo_connection->prepare("SELECT COUNT({$this->databaseTablePrimaryKey()}) AS totalNumberOfRecords FROM {$this->databaseName()}.{$this->databaseTableName()}"); // e.g.    SELECT COUNT(id) FROM products;    // I used 'Aliasing' using 'AS' keyword
        $statement->execute();

        $this->totalNumberOfRecords = (int) $statement->fetch(\PDO::FETCH_ASSOC)['totalNumberOfRecords']; // the array index name is the same as the 'Aliasing' name used in the SQL query    // (int) Type Casting to guarantee value is always integer

        $this->numberOfPages = ceil($this->totalNumberOfRecords / $this->recordsLimitNumberPerPage); // e.g. if, for example, result is 2.1, it'll be 3
        $this->numberOfPages = $this->numberOfPages == 0 ? 1 : $this->numberOfPages; // We need to have at least 1 page to show records (event if there aren't any)    // $numberOfPages can be 0 zero if, from the previous equation, $totalNumberOfRecords is equal to 0 zero    // 'false' here means 'Do Nothing'

        $currentPageNumber = $currentPageNumber > $this->numberOfPages ? $this->numberOfPages : $currentPageNumber; // $currentPageNumber (comes from $_GET in the URL query string parameters) can never be more than $numberOfPages
        $this->currentPageNumber = $currentPageNumber; // ASSIGN the $currentPageNumber which comes from the HTTP request from the controller to the DatabaseModel class    public int $currentPageNumber    property


        // Note: There are TWO ways to implement pagination (using SQL LIMIT & OFFSET, or using SQL LIMIT only):

        // First Way (using SQL LIMIT & OFFSET):
        // OFFSET = Lenght - 1    (just like arrayIndex = arrayLength - 1)
        // In the first page ($currentPageNumber = 1), OFFSET is (0-9). The second one ($currentPageNumber = 2), OFFSET is (10-19). The third one ($currentPageNumber = 3), OFFSET is (20-29), ... etc
        $this->SQLOffset = $currentPageNumber == 1 ? 0 : ($currentPageNumber - 1) * $this->recordsLimitNumberPerPage; // SQL OFFSET starts from zero 0, not from one 1    // e.g. If the limit is 10, page=1 records OFFSET is 0 and has records 0-9, page=2 records OFFSET is 10 has records 10-19, page=3 records OFFSET is 20 has records 20-29    // if the $currentPageNumber = 1, this means the OFFSET must be 0 zero, because in the first page, a SQL query will start from the beginning zero 0 OFFSET

        // Second Way (using SQL LIMIT only):
        $limitStart = $currentPageNumber == 1 ? 0 : ($currentPageNumber - 1) * $this->recordsLimitNumberPerPage; // identical to $SQLOffset
        $limitEnd   = $this->recordsLimitNumberPerPage;



        // THERE ARE TWO CASES HERE FOR select() METHOD: FIRST, IF THERE'S A SEARCH, THEN GET/SHOW ONLY WHAT IS SEARCHED FOR ONLY FROM THE DATABASE TABLE, OR SECOND, IF THERE'S NO SEARCH, THEN GET/SHOW EVERTHING IN THE DATABASE TABLE:
        if ($searchBarValue) { // If the user uses the search bar (whether direct search or AJAX Live Search), SELECT ONLY what they are searching for:
            // Important Note: In PDO, you mustn't bind (use named parameters like :car) with table names, table column names, ORDERBY clause, or ASC/DESC, because named parameters are automatically quoted by PDO and those aforementioned things shouldn'd be quoted in an SQL statement.
            $statement = Application::$app->database->pdo_connection->prepare("SELECT * FROM {$this->databaseName()}.{$this->databaseTableName()} WHERE $columnToSearchWith LIKE :searchBarValue ORDER BY $orderByColumn $ordering"); // e.g.    SELECT * FROM products WHERE title LIKE iphone ORDER BY create_date DESC;

            // Binding the Named Parameters (placeholders):
            $statement->bindValue(':searchBarValue', "%$searchBarValue%"); // The '%' percent sign is a SQL wildcard which means: zero or more characters

        } elseif ($usePaginationOrNot == false) { // e.g. The    GET /api/products    API Endpoint to get ALL items
            $statement = Application::$app->database->pdo_connection->prepare("SELECT * FROM {$this->databaseName()}.{$this->databaseTableName()}");
        } else { // If user doesn't use the search bar, then SELECT EVERYTHING in the table and implement Pagination:
            // First Way (using SQL LIMIT & OFFSET):
            $statement = Application::$app->database->pdo_connection->prepare("SELECT * FROM {$this->databaseName()}.{$this->databaseTableName()} ORDER BY $orderByColumn $ordering LIMIT :recordsLimitNumberPerPage OFFSET :SQLOffset"); // using $ordering
            $statement->bindValue(':recordsLimitNumberPerPage', $this->recordsLimitNumberPerPage, PDO::PARAM_INT);
            $statement->bindValue(':SQLOffset'                , $this->SQLOffset                , PDO::PARAM_INT);
        }

        $statement->execute();


        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    // READ one record (get one record by a certain column)
    public function readOrGetOneRecord(array $sqlWHEREclauseArray) { // e.g.    SELECT * FROM products WHERE id = 5 AND language = German;     // The value of the $this->columnName comes from the loadRequestDataToModel() method in BaseModel.php
        // FORMATTING THE SQL WHERE CLAUSE INTO THIS PATTERN:  // e.g.    SELECT * FROM products WHERE id = 5 AND language = German;
        $columnNames = array_keys($sqlWHEREclauseArray); // e.g.    [ 'id', 'language' ]
        $sqlWHEREclauseArrayToSQL = implode(' AND ', array_map(fn($columnName) => "$columnName = :$columnName" , $columnNames)); // e.g.   ' id = :id AND language = :language '
        $statement = Application::$app->database->pdo_connection->prepare("SELECT * FROM {$this->databaseName()}.{$this->databaseTableName()} WHERE $sqlWHEREclauseArrayToSQL;");

        // Binding the Named Parameters:
        foreach ($sqlWHEREclauseArray as $columnName => $searchedForValue) {
            $statement->bindValue(":$columnName", $searchedForValue); // e.g. $statement->bindValue(id, 5);
        }

        $statement->execute();

        return $statement->fetchObject(static::class); // Late Static Bindings (e.g. static::class is app\models\ProductModel)
    }



    // The only difference between this method and readOrGetOneRecord() is that it fetchs \PDO::FETCH_ASSOC to json_encode it (because fetchObject(static::class) of readOrGetOneRecord() method returns an object (not array) which contains extra data that shouldn't be previewd to general users by JSON)
    public function readOrGetOneRecordForAPI(array $sqlWHEREclauseArray) { // e.g.    SELECT * FROM products WHERE id = 5 AND language = German;     // The value of the $this->columnName comes from the loadRequestDataToModel() method in BaseModel.php
        // FORMATTING THE SQL WHERE CLAUSE INTO THIS PATTERN:  // e.g.    SELECT * FROM products WHERE id = 5 AND language = German;
        $columnNames = array_keys($sqlWHEREclauseArray); // e.g.    [ 'id', 'language' ]
        $sqlWHEREclauseArrayToSQL = implode(' AND ', array_map(fn($columnName) => "$columnName = :$columnName" , $columnNames)); // e.g.    ' id = :id AND language = :language '
        $statement = Application::$app->database->pdo_connection->prepare("SELECT * FROM {$this->databaseName()}.{$this->databaseTableName()} WHERE $sqlWHEREclauseArrayToSQL;");
        
        // Binding the Named Parameters:
        foreach ($sqlWHEREclauseArray as $columnName => $searchedForValue) {
            $statement->bindValue(":$columnName", $searchedForValue); // e.g. $statement->bindValue(id, 5);
        }

        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC); // Late Static Bindings (e.g. static::class is app\models\ProductModel)    // We fetch an object of the model class (e.g. ProductModel)    // fetch(\PDO::FETCH_OBJ) won't do, because it returns an object with the right properties values but out of stdClass, and we can't use an object out of this class to show the item information in the update.php page inside the HTML "value" attributes of the <input> fields
    }


    public function updateRecord($primaryKeyValue) { // $primaryKeyValue comes from ProductsController
        $columnNames = $this->databaseTableColumnNamesOrModelClassProperties(); // e.g    [ title, description, price, image_path]
        $statement = Application::$app->database->pdo_connection->prepare("UPDATE {$this->databaseName()}.{$this->databaseTableName()} SET " . implode(', ', array_map(fn($columnName) => "$columnName = :$columnName", $columnNames)) . " WHERE {$this->databaseTablePrimaryKey()} = :{$this->databaseTablePrimaryKey()};");

        // Binding the Named Parameters of the columns to UPDATE:
        foreach ($columnNames as $columnName) {
            $statement->bindValue(":$columnName", $this->$columnName); // e.g.    $statement->bindValue(:title, 'Chemistry')
        }

        // Binding the Named Parameters of the $primaryKeyValue to search with (After the WHERE clause): // e.g.    WHERE id = :id    i.e.    id = 5
        $statement->bindValue(":{$this->databaseTablePrimaryKey()}", $primaryKeyValue);
        $statement->execute();

        return true;
    }


    public function deleteRecord($primaryKeyValue) { // e.g. 29
        $statement = Application::$app->database->pdo_connection->prepare("DELETE FROM {$this->databaseName()}.{$this->databaseTableName()} WHERE {$this->databaseTablePrimaryKey()} = :{$this->databaseTablePrimaryKey()};");
        
        $statement->bindValue(":{$this->databaseTablePrimaryKey()}", $primaryKeyValue);
        $statement->execute();

        return true;
    }


    // Check if there's a record in the database table with a certain <input> field/column/property value: (This method is called inside BaseModel.php for the 'UNIQUE' validation rule)
    public function checkIfRecordAlreadyExists($inputFieldOrColumnName, $inputFieldSubmittedValueOrColumnValue) {
        $statement = Application::$app->database->pdo_connection->prepare("SELECT $inputFieldOrColumnName FROM {$this->databaseName()}.{$this->databaseTableName()} WHERE $inputFieldOrColumnName = :$inputFieldOrColumnName"); // SELECT-ing the $inputFieldOrColumnName ONLY for a BETTER PERFORMANCE because we don't need to select anything in the first place, we'll just use the PDOStatement::rowCount() method only to know whether the record exist or not
        $statement->bindValue(":$inputFieldOrColumnName", $inputFieldSubmittedValueOrColumnValue);
        $statement->execute();
    
        if ($statement->rowCount() !== 0) { // means the record already exists
            return true;
        } else { // the record does NOT exist
            return false;
        }
    }

}