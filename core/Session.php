<?php
namespace app\core;


class Session {
    protected const FLASH_MESSAGES_KEY = 'flash_messages';
    // The idea of a Session Flash Message is that a Flash Message session is AVAIALABLE FOR ONLY ONE HTTP REQUEST, and then gets deleted by the magic __destruct() method
    // Session Flash Messages life cycle/story: Session flash messages are set in controllers using setFlashMessageInSession() method, marked not to be removed 'false', then the __construct() method starts the session using session_start() and changes them and marks them to be removed 'true', then those Flash Messages are viewed in views e.g. productsLayout.php using the getFlashMessageFromSession() method, then the __destruct() method upon the end of the script checks the flash messages that are marked to be removed 'true' then unsets them form the $_SESSION array using unset()


    public function __construct() {
        session_start(); // Start the Session

        $allFlashMessages = $_SESSION[self::FLASH_MESSAGES_KEY] ?? [];
        
        foreach ($allFlashMessages as $flashMessageKey => &$flashMessageValues) { // foreach loops change $value only 'By Value', so in such case, 'removeOrNot' won't change value from 'false' to 'true', so you must use the '&' operator to change or assign $value 'By Reference'
            $flashMessageValues['removeOrNot'] = true; // If the $flashMessageValueIndices is not assigned by reference, the value won't change to 'true'. That's because foreach loops change $value only 'By Value', so you must use the '&' to change $value 'By Reference'
        }

        $_SESSION[self::FLASH_MESSAGES_KEY] = $allFlashMessages;
    }


    // The Destructor Function In General: is called when deleting or destroying an object Or the script is stopped or exited (PHP will automatically call this function at the end of the script)
    public function __destruct() { // Because a Session Flash Message is available for only one HTTP request, we need to delete the flash message at the end of the request using __destruct() method
        $allFlashMessages = $_SESSION[self::FLASH_MESSAGES_KEY] ?? [];
        foreach ($allFlashMessages as $flashMessageKey => &$flashMessageValues) { // Assigning the foreach $value 'By Reference' because foreach loops creates its own copy of the array and don't affect the original array
            if ($flashMessageValues['removeOrNot']) { // Or the same as: if ($flashMessageValue['removeOrNot'] == true) {
                unset($allFlashMessages[$flashMessageKey]); // Unset() the whole Flash Message associative array    e.g.    [    'login_success_message' => ['removeOrNot' => false, 'flashMessageText' => $flahsMessageText (the messsage itself)    ]
            }
        }

        $_SESSION[self::FLASH_MESSAGES_KEY] = $allFlashMessages; // We write this line of code because the foreach loop changed ONLY the $allFlashMessages variable copy of the array, but not the actual $_SESSION[self::FLASH_MESSAGES_KEY] itself    // Another way to go was to assign $_SESSION[self::FLASH_MESSAGES_KEY] to $allFlashMessages By Reference    i.e.    $allFlashMessages = &$_SESSION[self::FLASH_MESSAGES_KEY] ?? [];
    }


    public function setFlashMessageInSession($flashMessageKey, $flashMessageText) {
        /* Pattern is:
            $_SESSION['flash_messages'] => [
                    'login_success_message' => [
                        'removeOrNot'      => false,
                        'flashMessageText' => $flahsMessageText (the messsage itself)
                    ]
            ]
        */
        $_SESSION[self::FLASH_MESSAGES_KEY][$flashMessageKey] = [
            'removeOrNot'      => false, // We set the default to be false, meaning we don't remove the flash message, then the __construct() method marks it to be removed by changing the value to true for the __destruct() method to remove it
            'flashMessageText' => $flashMessageText
        ];
    }


    public function getFlashMessageFromSession($flashMessageKey) {
        /* Pattern is:
            $_SESSION['flash_messages'] => [
                    'login_success_message' => [
                        'removeOrNot'      => false,
                        'flashMessageText' => $flahsMessageText (the messsage itself)
                    ]
            ]
        */
        return $_SESSION[self::FLASH_MESSAGES_KEY][$flashMessageKey]['flashMessageText'] ?? '';
    }


    public function setSomethingInSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function getSomethingFromSession($key) { // this method is called inside isUserAGuest() method in LoginFormModel.php
        return $_SESSION[$key] ?? false;
    }

    public function removeSomethingFromSession($key) {
        unset($_SESSION[$key]);
    }

}