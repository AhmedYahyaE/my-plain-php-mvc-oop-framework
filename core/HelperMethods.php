<?php
// A class that contains generic miscellaneous functions/methods to be used anywhere in your project 
namespace app\core;


class HelperMethods {
    public static function generateRandomString(int $generatedStringDesiredLengthNumber): string { // $generatedStringDesiredLengthNumber is the length of we want for the generated string
        $generatedString = '';
        $charactersToFormStringFrom = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for ($i = 0; $i < $generatedStringDesiredLengthNumber; $i++) {
            $charactersToFormStringFromIndexNumber = rand(0, strlen($charactersToFormStringFrom) - 1); // This is the random index number for the the main string we have ($charactersToFormStringFrom), so its index minimum number will be 0, and its index maximum number must not exceed its length at all (N.B. index = length - 1 )
            $generatedString .= $charactersToFormStringFrom[$charactersToFormStringFromIndexNumber]; // fill the empty string with characters
        }

        return $generatedString;
    }

}