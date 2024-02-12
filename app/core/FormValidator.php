<?php

class FormValidator {

    /**
     * Validate if the input is a correct email address.
     *
     * @param string $inputEmail The input to validate.
     * @return bool True if the input is a valid email address, false otherwise.
     */
    public static function isValidEmail(string $inputEmail) : bool {  
    
    if (empty($inputEmail)) {
        // Empty email is considered invalid
        return false;
    }        
    $email = Helper::sanitizeInput($inputEmail); 
    // Use filter_var with FILTER_VALIDATE_EMAIL flag to check email format
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Method to validate phone number input.
     *
     * @param string $inputPhoneNumber The phone number input to validate.
     * @return string|null Returns an error message if the input is invalid, null otherwise.
     */
    public static function validatePhoneNumber(string $inputPhoneNumber): ?string {
       
        if (empty($inputPhoneNumber)) {
        // Empty email is considered invalid
        return false;
        }     
        
        $phoneNumber = Helper::sanitizeInput($inputPhoneNumber);

        // Check if the input is empty
        if (empty($phoneNumber)) {
            return "Phone number cannot be empty.";
        }

        // Check if the input contains only digits
        if (!ctype_digit($phoneNumber)) {
            return "Invalid input. Phone number must contain only digits.";
        }

        // Additional validation logic can be added here, such as checking the length or format of the phone number
        // If the input passes all validation checks, return null (indicating no error)
        return null;
    }
// Add more validation methods as needed
}




