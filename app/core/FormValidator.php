<?php
namespace app\core;

class FormValidator {

    /**
     * Check if a password meets the specified requirements.
     *
     * @param string $password The password to check.
     * @return bool True if the password meets the requirements, false otherwise.
     */
    public static function isValidPassword($password) {
        // Check if password length is between 8 and 64 characters
        if (strlen($password) < 8 || strlen($password) > 64) {
            return false;
        }

        // Check for at least one uppercase letter, one lowercase letter, one digit, and one special character
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            return false;
        }

        // Check for common patterns to avoid (e.g., sequential characters, repeated characters)
        if (preg_match('/(.)\1{2,}/', $password) || preg_match('/abc|bcd|cde|xyz/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Validate if the input is a correct email address.
     *
     * @param string $inputEmail The input to validate.
     * @return bool True if the input is a valid email address, false otherwise.
     */
    public static function isValidEmail(string $inputEmail): bool {

        if (empty($inputEmail)) {
            // Empty email is considered invalid
            return false;
        }
        $email = Helper::sanitizeInput($inputEmail);
        // Use filter_var with FILTER_VALIDATE_EMAIL flag to check email format
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate if the input is a correct phone number.
     *
     * @param string $inputPhoneNumber The phone number input to validate.
     * @return bool True if the input is a valid phone number, false otherwise.
     */
    public static function isValidPhoneNumber(string $inputPhoneNumber): bool {
        if (empty($inputPhoneNumber)) {
            // Empty phone number is considered invalid
            return false;
        }

        // Sanitize the input phone number (optional)
        $phoneNumber = Helper::sanitizeInput($inputPhoneNumber);

        // Regular expression pattern to match a valid phone number
        $pattern = "/^\+?(\d{1,3})?\s?\(?(\d{3})\)?[-.\s]?(\d{3})[-.\s]?(\d{4})$/";

        // Check if the input matches the pattern
        if (!preg_match($pattern, $phoneNumber)) {
            return false;
        }

        // Additional validation logic can be added here, such as checking specific country codes or area codes

        return true;
    }

    public static function isValidPersonName($name) {
        // Regular expression pattern to match valid person names
        $pattern = "/^[a-zA-Z' -]+$/";

        // Check if the input matches the pattern
        if (preg_match($pattern, $name)) {
            return true; // Valid name
        } else {
            return false; // Invalid name
        }
// Add more validation methods as needed
    }
/**
 * Validate registration form data.
 *
 * @param array|null $formData The form data to validate.
 * @return array An array containing error messages, if any.
 */
public static function validateRegistrationForm(?array $formData): array {
    $errors = [];

    // Check if form data is present
    if (empty($formData)) {
        return $errors;
    }

    // Validate first name
    if (!isset($formData['first_name']) || empty($formData['first_name'])) {
        $errors['first_name'] = "First name is required.";
    } elseif (!self::isValidPersonName($formData['first_name'])) {
        $errors['first_name'] = "Invalid first name.";
    }

    // Validate last name
    if (!isset($formData['last_name']) || empty($formData['last_name'])) {
        $errors['last_name'] = "Last name is required.";
    } elseif (!self::isValidPersonName($formData['last_name'])) {
        $errors['last_name'] = "Invalid last name.";
    }

    // Validate email
    if (!isset($formData['email']) || empty($formData['email'])) {
        $errors['email'] = "Email is required.";
    } elseif (!self::isValidEmail($formData['email'])) {
        $errors['email'] = "Invalid email address.";
    }

    // Validate phone number
    if (!isset($formData['telephone']) || empty($formData['telephone'])) {
        $errors['telephone'] = "Phone number is required.";
    } elseif (!self::isValidPhoneNumber($formData['telephone'])) {
        $errors['telephone'] = "Invalid phone number.";
    }

    // Validate city
    if (!isset($formData['city']) || empty($formData['city'])) {
        $errors['city'] = "City is required.";
    } elseif (!self::isValidPersonName($formData['city'])) {
        $errors['city'] = "Invalid city name.";
    }

    // Validate password
    if (!isset($formData['password']) || empty($formData['password'])) {
        $errors['password'] = "Password is required.";
    } elseif (!self::isValidPassword($formData['password'])) {
        $errors['password'] = "Invalid password format.";
    }

    // Validate password confirmation
    if (!isset($formData['pass_confirm']) || empty($formData['pass_confirm'])) {
        $errors['pass_confirm'] = "Password confirmation is required.";
    } elseif ($formData['password'] !== $formData['pass_confirm']) {
        $errors['pass_confirm'] = "Passwords do not match.";
    }

    return $errors;
}


}
