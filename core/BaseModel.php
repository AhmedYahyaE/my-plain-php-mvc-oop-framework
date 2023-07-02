<?php
// An abstract base Model class that is inherited by all models
namespace app\core;


abstract class BaseModel {
    public string $method; // Which method of a child model class is used (comes from the child model method in-use)
    public string $model; // Which child model class is used (comes from the child model method in-use)
    
    // HTML <input> fields elements rules (e.g. required, email, text, number, ...etc):
    public CONST RULE_REQUIRED   = 'required';
    public CONST RULE_NUMBER     = 'number';     // like `price` must be a number
    public CONST RULE_MINIMUM    = 'minimum';    // minimum characters to be submitted in an <input> field
    public CONST RULE_MAXIMUM    = 'maximum';    // maximum characters to be submitted in an <input> field
    public CONST RULE_MUST_MATCH = 'must_match'; // submitted value of an <input> field must match (be the same as) the submitted value of another <input> field e.g. `Confirm password` <input> field submitted value must match the `Password` <input> field submitted value
    public CONST RULE_EMAIL      = 'email';      // submitted value of an <input> field must be a valid email address
    public CONST RULE_UNIQUE     = 'unique';     // e.g. The error: `username` ALREADY EXISTS! (like `email` must be UNIQUE (NOT ALREADY EXISTS!))

    public array $errors = []; // errors of HTML <input> fields (required, must be a valid email, ...)    // this property is used insdie validate() method in this class

    // METHODS WHICH ARE MANDATORY TO BE IMPLEMENTED BY CHILD MODEL CLASSES:
    abstract public function inputFieldsHTMLElementsRules(): array; // The rules desired for every <input> HTML field in the HTML <form> for child model

    // abstract public function readRecords($searchBarValue = '', $columnToSearchWith = ''); // This is JUST to avoid the IDE syntax Error: "Undefined method 'readRecords'.intelephense(1013)"
    abstract public function checkIfRecordAlreadyExists($inputFieldOrColumnName, $inputFieldSubmittedValueOrColumnValue); // This is JUST to avoid the IDE syntax Error: "Undefined method 'checkIfAlreadyExists'.intelephense(1013)"


    // takes submitted data from HTML <form> and loads them to the child model class properties (e.g. Submitted data from create.php HTML <form> and loading them to ProductModel.php class properties)
    public function loadRequestDataToModel(array $HTTPRequestBody) {
        foreach ($HTTPRequestBody as $inputFieldNameAttribute => $inputFieldEnteredValue) {
            // The ReflectionProperty PHP's built-in class reports information about class properties:
            $rp = new \ReflectionProperty(static::class, $inputFieldNameAttribute);

            if ($rp->getType()->getName() === 'float') { // To Type Casting the `price` <input> field in the ProductModel.php, because when the <form> is submitted with the `price` <input> field is left out empty, the <form> sends an empty string to server, which causes an error because the ProductModel class $price propery is 'float' and it receives an empty 'string'
                $inputFieldEnteredValue = (float) $inputFieldEnteredValue;
            }

            if (property_exists($this, $inputFieldNameAttribute)) { // If the array key (e.g. 'price' => 33.50) exists in the model as a class property (e.g. public float $price;), load the entered value to the $property
                $this->$inputFieldNameAttribute = $inputFieldEnteredValue; // Example:    $this->title = 'iPhone'
            }
        }
    }


    public function validate(): bool {
        // Logic Explanantion: We loop through all the rules specified in the child model class in the inputFieldsHTMLElementsRules() method returned array, and get every <input> field value that was submitted in the HTML <form> from the child model class properties values that were loaded from the <form> to the model properties using the loadRequestDataToModel() method,
        foreach ($this->inputFieldsHTMLElementsRules() as $propertyNameOrInputFieldNameAttribute => $rulesSpecifiedInModel) { // looping through the <input> fields that need RULES depending on the array returned from the inputFieldsHTMLElementsRules() method inside the child model, and then get those <input> fields values submitted from <form>
            $propertyValueOrInputFieldSubmittedValue = $this->$propertyNameOrInputFieldNameAttribute; // e.g. $this->'price' or $this->title    // Getting the values submitted in the <form> of the <input> fields that were specified in the child model class that need validation, those values were assigned by the loadRequestDataToModel() method to the model class properties    // e.g. 'price' => 55.30
            
            // Nested Loop:
            foreach ($rulesSpecifiedInModel as $ruleSpecifiedInModel) { // e.g. 'required', 'required', 'number'
                if (!is_string($ruleSpecifiedInModel)) { // Or the same: if (is_array($ruleSpecifiedInModel)) {    // if the rule is not a string (meaning, if the rule is an array)       // Or the same: if (is_array($ruleSpecifiedInModel)) {
                    $complexRuleSpecifiedInModel = $ruleSpecifiedInModel;
                    $ruleSpecifiedInModel = $ruleSpecifiedInModel[0];
                }

                // VALIDATION for every $ruleSpecifiedInModel we have in the child model by checking the submitted value:

                // validation for 'required' rule:
                if ($ruleSpecifiedInModel === self::RULE_REQUIRED && !$propertyValueOrInputFieldSubmittedValue) { // !$propertyValueOrInputFieldSubmittedValue means the user didn't enter a value in the <input> field    // Pseudocode explanation: if (the 'value' submitted from the <form> is empty, meaning user didn't enter the value, and the rule specified for the <input> field is 'required') { // then add to the $errors array}
                    $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute);
                }

                // validation for 'number' rule: (Note: In ProductModel, this error will NEVER appear because we Type Casting the entered value to 'float' inside the loadRequestDataToModel() in this class)
                if ($ruleSpecifiedInModel === self::RULE_NUMBER && !is_numeric($propertyValueOrInputFieldSubmittedValue)) { // !$propertyValueOrInputFieldSubmittedValue means the user didn't enter a value in the <input> field    // Pseudocode explanation: if (the 'value' submitted from the <form> is not a number and the rule specified for the <input> field is 'number') { // then add to $errors array}
                    $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute);
                }

                // validation for 'minimum' rule: (A complex rule e.g. [self::RULE_MINIMUM, 'minimum' => 3] ) 
                if ($ruleSpecifiedInModel === self::RULE_MINIMUM && strlen($propertyValueOrInputFieldSubmittedValue) < $complexRuleSpecifiedInModel['minimum']) { // !$propertyValueOrInputFieldSubmittedValue means the user didn't enter a value in the <input> field    // Pseudocode explanation: if (the 'value' submitted from the <form> is not a number and the rule specified for the <input> field is 'number') { // then add to $errors array}
                    $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute, $complexRuleSpecifiedInModel);
                }

                // validation for 'maximum' rule: (A complex rule e.g. [self::RULE_MINIMUM, 'maximum' => 4] ) 
                if ($ruleSpecifiedInModel === self::RULE_MAXIMUM && strlen($propertyValueOrInputFieldSubmittedValue) > $complexRuleSpecifiedInModel['maximum']) { // !$propertyValueOrInputFieldSubmittedValue means the user didn't enter a value in the <input> field    // Pseudocode explanation: if (the 'value' submitted from the <form> is not a number and the rule specified for the <input> field is 'number') { // then add to $errors array}
                    $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute, $complexRuleSpecifiedInModel);
                }

                // validation for 'must_match' rule: (A complex rule)
                if ($ruleSpecifiedInModel === self::RULE_MUST_MATCH && $propertyValueOrInputFieldSubmittedValue !== $this->{$complexRuleSpecifiedInModel['must_match']}) { // e.g. If the sumbmitted value of `Confirm password` doesn't match the submitted value of `Password` i.e. $this->password, then add to the $errors array
                    $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute, $complexRuleSpecifiedInModel);
                }

                // validation for 'email' rule:
                if ($ruleSpecifiedInModel === self::RULE_EMAIL && !filter_var($propertyValueOrInputFieldSubmittedValue, FILTER_VALIDATE_EMAIL)) {
                    $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute);
                }

                // validation for 'unique' rule: (WE MUST QUERY THE DATABASE HERE!)
                if ($ruleSpecifiedInModel === self::RULE_UNIQUE) {
                    if ($this->checkIfRecordAlreadyExists($propertyNameOrInputFieldNameAttribute, $this->$propertyNameOrInputFieldNameAttribute)) {
                        $this->addInputFieldErrorWithMessageToErrorsArray($ruleSpecifiedInModel, $propertyNameOrInputFieldNameAttribute, ['uniqueInputFieldName' => $propertyNameOrInputFieldNameAttribute]);
                    }
                }
            }

        }


        return empty($this->errors); // returns a Boolean (if there're errors or not in the array)
    }

    // The error message for every <input> field rule we have in the BaseModel
    public function errorMessagesForInputFieldsRules(): array {
        return [
            self::RULE_REQUIRED    => 'This field is required',
            self::RULE_NUMBER      => 'This field must be a number',
            self::RULE_MINIMUM     => 'Minimum length of this field must be <b>{minimum}</b>',  // {minimum}              is a placeholder that will be replaced inside addInputFieldErrorWithMessageToErrorsArray() method    // Placeholder spelling must match its spelling inside inputFieldsHTMLElementsRules() method
            self::RULE_MAXIMUM     => 'Minimum length of this field must be <b>{maximum}</b>',  // {minimum}              is a placeholder that will be replaced inside addInputFieldErrorWithMessageToErrorsArray() method    // Placeholder spelling must match its spelling inside inputFieldsHTMLElementsRules() method
            self::RULE_MUST_MATCH  => 'This field must be the same as <b>{must_match}</b>',     // {must_match}           is a placeholder that will be replaced inside addInputFieldErrorWithMessageToErrorsArray() method    // Placeholder spelling must match its spelling inside inputFieldsHTMLElementsRules() method
            self::RULE_EMAIL       => 'This field must be a valid email address',
            self::RULE_UNIQUE      => 'Record with this {uniqueInputFieldName} already exists!' // {uniqueInputFieldName} is a placeholder that will be replaced inside addInputFieldErrorWithMessageToErrorsArray() method    // Placeholder spelling must match its spelling inside inputFieldsHTMLElementsRules() method
        ];
    }


    public function addInputFieldErrorWithMessageToErrorsArray(string $ruleSpecifiedInModelToGetItsMessage, string $propertyNameOrInputFieldNameAttribute, array $complexRuleArrayToReplacePlaceholder = []) { // is called inside validate() method in every rule validation
        // Get the $ruleErrorMessage from the array returned from the errorMessagesForInputFieldsRules() method:
        $ruleErrorMessage = $this->errorMessagesForInputFieldsRules()[$ruleSpecifiedInModelToGetItsMessage];
        
        // Editing the error message in case of having a {{placeholder}} (in complex rules case): If the $ruleErrorMessage contains a placeholder ( {{text}} ) in errors like [self::RULE_MATCH, 'match' => 'password'], [self::RULE_MAX, 'max' => 24], ...etc, replace it with the right word:
        foreach ($complexRuleArrayToReplacePlaceholder as $placeholder => $replacementValue) {
            $ruleErrorMessage = str_replace("{{$placeholder}}", ucfirst($replacementValue), $ruleErrorMessage); // used ucfirst() to capitalize the `password` first letter to allow the error to show up properly in the front-end (register.php)
        }


        $this->errors[$propertyNameOrInputFieldNameAttribute][] = $ruleErrorMessage; // using extra square bracktes [] works fine, because we are using an 'index' so every value REPLACES its predecessor because of having THE SAME 'index' (in case of one $propertyNameOrInputFieldNameAttribute has multiple errors e.g. An `Email` field is REQUIRED and is VALID_EMAIL), as $ruleErrorMessage comes multiple times because the addInputFieldErrorWithMessageToErrorsArray() method is being called multiple times inside validate() method, so solution is to use extra square bracket [] to add the values themselves as AN ARRAY
    }


    public function directlyAddToErrorsArray(string $inputFieldName, string $errorMessage): void { // This method is called in LoginFormModel.php inside login() method
        $this->errors[$inputFieldName][] = $errorMessage;
    }

    // This method is used inside views to check the $errors array if a <form> <input> field has errors to show them to user using Bootstrap if there're any, or return 'false'
    public function inputFieldHasError($inputFieldName): array|false { // This method returns the errors for an $inputFieldName if there're any, Or returns 'false'
        // If there's an array index of the passed in $inputFieldName in the $errors array, this means there's an error for that $inputFieldName
        return $this->errors[$inputFieldName] ?? false; // Return the errors if there're any, or return 'false'
    }

}