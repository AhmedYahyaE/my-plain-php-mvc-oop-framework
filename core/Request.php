<?php
namespace app\core;


class Request {

    public function HTTPRequestMethodType() { // this method is called from resolve() method in Router class through Application class
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGET(): bool { // check if the HTTP request type is 'GET' or not
        return $this->HTTPRequestMethodType() === 'GET';
    }

    public function isPOST(): bool { // check if the HTTP request type is 'POST' or not
        return $this->HTTPRequestMethodType() === 'POST';
    }


    public function HTTPRequestBody(): array { // prepares the request body (data sent to server) into an array    // HTTP request is composed of 3 components: request line, request header and request body (body is the data sent themselves like the submitted <form> HTML element data)
        $requestBody = [];

        if ($this->isGET()) { // if the HTTP request type is 'GET':
            foreach ($_GET as $key => $value) {
                $requestBody[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS); // filter_input() function checks if $_GET[$key] is sent to server through a 'GET' HTTP request method, and sanitizes it from harmful special characters
            }
        }

        if ($this->isPOST()) { // if the HTTP request type is 'POST':
            foreach ($_POST as $key => $value) {
                $requestBody[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); // filter_input() function checks if $_POST[$key] is sent to server through a 'POST' HTTP request method, and sanitizes it from harmful special characters
            }

            foreach ($_FILES as $key => $value) { // If the HTTP request type is 'POST', add the $_FILES array that contains uploaded files (if any)    // The pattern of $_FILES is:    $_FILES['inputFieldName']['tmp_name']
                $requestBody[$key] = $value;
            }
        }


        return $requestBody;
    }



    public function prepareURL() { // this method returns a pure URL without a Query String (if any), it checks the typed URL, if it doesn't have a Query String, it returns it as is, but if if finds that the URL has a Query String, it removes the '?' and what comes after it and return the pure URL without the Query String    // this method is called from resolve() method in Router.php class through Application class
        $current_url = $_SERVER['REQUEST_URI'];
        // Now, in case the $current_url has a Query String, we want to remove it, and return the pure URL:
        if (strpos($current_url, '?') === false) { // if the $current_url doesn't have a '?' which means it doesn't have a Query String, return it as is.    // Note that strpos() function returns 'false' if it doesn't find what you're looking for
            // echo 'Does NOT have a Query String<br>';
            return $current_url;
        } else { // if the $current_url has a '?' which means it has a Query String, take out the $current_url from its beginning to before the '?' sign only (take out the URL without the Query String)
            // echo 'Has a Query String<br>';
            return substr($current_url, 0, strpos($current_url, '?')); // take out the URL from position 0 till before the '?'
        }
    }

}