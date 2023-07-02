<?php
namespace app\models;
use app\core\database\DatabaseModel;



// ProductModel.php extends BOTH DatabaseModel AND BaseModel (DatabaseModel.php IN TURN extends BaseModel.php)
class ProductModel extends DatabaseModel {

    public ?int $id = null;
    public string $title = '';
    public ?string $description = '';
    public ?float $price = null;
    public ?array $uploadedImage; // the uploaded image file ITSELF
    public ?string $image_path = '';


    public function databaseName(): string {
        return 'my_plain_php_mvc_oop_framework';
    }

    public function databaseTableName(): string {
        return 'products';
    }

    public function databaseTablePrimaryKey(): string {
        return 'id';
    }

    public function databaseTableColumnNamesOrModelClassProperties(): array { // Only the <input> fields or model class properties/table column names that represent the database table columns that will be handled and filled by ourselves with data using PDO ($uploadedImage is NOT one of them because we store the uploaded file itself on the server, but we store its PATH ONLY in the database) ($id is not one of them because it's automatically generated in the database)
        return ['title', 'description', 'price', 'image_path'];
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
            'title' => [ self::RULE_REQUIRED, [self::RULE_MINIMUM, 'minimum' => 3] ], // Complex Rule spelling must match its spelling inside errorMessagesForInputFieldsRules() method
            'price' => [self::RULE_REQUIRED, self::RULE_NUMBER]
        ];
    }


    public function createRecord() { // The value of $image_path property is decided and assigned inside DatabaseModel inside the fileUploadToServer() method by the $filePathInDatabaseProperty variable
        $this->fileUploadToServer($this->uploadedImage, $this->image_path); // Handle image uploading to server in case of create, if any (if there's an image was uploaded)
        return parent::createRecord();
    }


    public function readRecords($searchBarValue = '', $columnToSearchWith = 'title', $orderByColumn = 'create_date', $ordering = 'DESC', bool $usePaginationOrNot = true, int $currentPageNumber = 1) { // $searchBarValue comes from the $loadRequestDataToModel() method insdie BaseModel.php    // $currentPageNumber is used for Pagination (value comes from controllers)    // A default value of $currentPageNumber is 1 (e.g. URL is:    GET /products?page=3    )
        return parent::readRecords($searchBarValue, $columnToSearchWith, $orderByColumn, $ordering, $usePaginationOrNot, $currentPageNumber); // Search in the 'title' column, order by the `create_date`, and the ordering is `DESC`
    }


    // READ one record (get one record by a certain column)
    public function readOrGetOneRecord(array $sqlWHEREclauseArray = []) { // e.g.    SELECT * FROM products WHERE id = 5 AND language = German;
        if ($sqlWHEREclauseArray) { // if there's something passed in to search with it
            return parent::readOrgetOneRecord($sqlWHEREclauseArray);
        } else { // there's nothing passed in to search with, then search with the table Primary Key (`id` most of times)
            return parent::readOrgetOneRecord(    [ $this->databaseTablePrimaryKey() => $this->{$this->databaseTablePrimaryKey()} ]   ); // The value of the $this->id comes from the loadRequestDataToModel() method in BaseModel.php
        }
    }


    public function updateRecord($primaryKeyValue) { // $primaryKeyValue comes from ProductsController
        $productObjectToBeUpdated = parent::readOrGetOneRecord([ $this->databaseTablePrimaryKey() => $primaryKeyValue ]);
        $this->fileUploadToServer($this->uploadedImage, $this->image_path); // Handle image uploading to server in case of UPDATE, if any

        if (!$this->image_path) { // If the user doesn't upload a new image and then submits the <form> (if they doesn't update the image), then make $this->image_path to be the old already existing products's image $image_path
            $this->image_path = $productObjectToBeUpdated->image_path;
        }

        return parent::updateRecord($primaryKeyValue);
    }


    public function deleteRecord($primaryKeyValue) {
        $productToBeDeletedObject = parent::readOrGetOneRecord([ $this->databaseTablePrimaryKey() => $primaryKeyValue ]);

        if ($productToBeDeletedObject->image_path) { // Check if the record (e.g. product) to be deleted has an uploaded image in the first place (by checking if it has an image path stored in database)
            $this->deleteFileFromServer($productToBeDeletedObject->image_path); // Delete the image when deleting a complete product (using the Delete Button)
        }

        return parent::deleteRecord($primaryKeyValue);
    }

}