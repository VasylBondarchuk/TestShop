<?php

class Helper {

    public static function urlBuilder($url, $content, $params = []): string {
        // Ensure $url starts with a slash
        $url = '/' . ltrim($url, '/');

        // Append query parameters, if any
        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }

        // Construct the anchor tag with the provided content
        return '<a href="' . route::getBP() . $url . '">' . $content . '</a>';
    }

    public static function redirect(string $path): void {
        // Validate path
        if (!is_string($path) || empty($path)) {
            throw new InvalidArgumentException("Invalid redirect path");
        }

        // Construct full URL
        $server_host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $url = $server_host . route::getBP() . $path;

        // Perform redirect
        header("Location: $url");
        exit(); // Ensure no further code execution
    }

    public static function sanitizeInput(string|array|null $inputData): mixed {
        // Check if input data is null
        if ($inputData === null) {
            return null;
        }

        // Initialize the variable to hold sanitized data
        $sanitizedInput = '';

        // If the input is an array, sanitize each element recursively
        if (is_array($inputData)) {
            $sanitizedInput = [];
            foreach ($inputData as $key => $value) {
                $sanitizedInput[$key] = self::sanitizeInput($value);
            }
        } else {
            // Trim whitespace from the beginning and end of the input
            $trimmedInputData = trim($inputData);
            // Remove backslashes
            $stripSlashesData = stripslashes($trimmedInputData);
            // Convert special characters to HTML entities to prevent XSS attacks
            $sanitizedInput = htmlspecialchars($stripSlashesData);
        }

        return $sanitizedInput;
    }

    /**
     * Get and sanitize a value from $_POST, handling both string and array values.
     *
     * @param string $field The field name.
     * @return mixed The sanitized value from $_POST or null if not found.
     */
    public static function getPostValue(string $field) {
        $rawValue = filter_input(INPUT_POST, $field, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if (is_array($rawValue)) {
            // Sanitize each element of the array
            return array_map('self::sanitizeInput', $rawValue);
        } else {
            $rawValue = filter_input(INPUT_POST, $field, FILTER_DEFAULT);
            // If not an array, sanitize the single value
            return self::sanitizeInput($rawValue);
        }
    }

    public static function isEmpty(string $fieldName): bool {
        return empty(self::getPostValue($fieldName));
    }

    public static function emptyFieldMessage(string $fieldName): string {
        return "The field '{$fieldName}' is required.";
    }

    /**
     * Get parameter value from the URL query string and sanitize it.
     *
     * @param string $paramName The name of the parameter to retrieve.
     * @return mixed|null The sanitized value of the parameter if found, or null if not found.
     */
    public static function getQueryParam(string $paramName) {
        // Get the value of the parameter from the URL query string
        $paramValue = filter_input(INPUT_GET, $paramName);

        // Sanitize the parameter value
        $sanitizedValue = self::sanitizeInput($paramValue);

        // Return the sanitized parameter value if found, otherwise return null
        return $sanitizedValue !== false ? $sanitizedValue : null;
    }

    public static function displayErrorMessage($input, $errorType) {
        switch ($errorType) {
            case 'empty':
                echo "Field {$input} is required";
                break;
            case 'numeric':
                echo "Field {$input} must be a number";
                break;
            case 'phone_format':
                echo "Phone number must contain only digits";
                break;
            case 'email_format':
                echo "Please enter a valid email address";
                break;
            default:
                echo "";
        }
    }

    public static function validateInput($input, $value) {
        switch ($input) {
            case 'sku':
            case 'name':
            case 'last_name':
            case 'first_name':
            case 'city':
                if (empty($value)) {
                    self::displayErrorMessage($input, 'empty');
                    return false;
                }
                break;
            case 'price':
            case 'qty':
                if (empty($value) || !is_numeric($value)) {
                    self::displayErrorMessage($input, 'numeric');
                    return false;
                }
                break;
            case 'last_name':
            case 'first_name':
            case 'city':
                if (!self::isUkrainian($value)) {
                    self::displayErrorMessage($input, 'ukrainian');
                    return false;
                }
                break;
            case 'telephone':
                if (!preg_match('/^\d+$/', $value)) {
                    self::displayErrorMessage($input, 'phone_format');
                    return false;
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    self::displayErrorMessage($input, 'email_format');
                    return false;
                }
                break;
            default:
                return true;
        }
        return true;
    }

    // Method to handle file uploads
    public static function handleFileUpload(): array {
        $filteredData = [];
        if (isset($_FILES['product_image'])) {
            $fileName = $_FILES['product_image']['name'];
            // Sanitize the file name
            $sanitizedFileName = self::sanitizeInput($fileName);
            // Store the sanitized file name
            $filteredData['product_image'] = $sanitizedFileName;
        }
        return $filteredData;
    }

// Method to retrieve form data
    public static function getFormData(array $columns): array {
        $filteredData = [];

        foreach ($_POST as $key => $value) {
            // Check if the form field corresponds to a database column
            if (in_array($key, $columns)) {
                // Sanitize input data before storing it
                $filteredData[$key] = self::sanitizeInput($value);
            }
        }
        // Handle file uploads separately
        $filteredData += self::handleFileUpload();
        return $filteredData;
    }

    public static function getSortParams(string $field): array {
        // Initialize sorting parameters array
        $sortParams = [];

        // Retrieve sorting parameter from POST data
        $sortParam = filter_input(INPUT_GET, $field);
        // Check if sorting parameter is set
        if ($sortParam) {
            // Split the sorting parameter into direction and property
            $sortParts = explode('_', $sortParam);

            // Extract sorting direction and property
            $sortOrder = $sortParts[0]; // "asc" or "desc"
            $sortField = $sortParts[1]; // "price" or "qty"
            // Add sorting parameter to the array
            $sortParams[$sortField] = $sortOrder;
        }

        return $sortParams;
    }

    /**
     * Helper method to display an error message if the form field is empty and the form is submitted.
     *
     * @param string $fieldName The name of the form field.
     * @param string $errorMessage The error message to display.
     * @return string The error message if the field is empty and the form is submitted, otherwise an empty string.
     */
    public static function displayErrorMessageIfEmpty(string $fieldName) {
        // Check if the form is submitted and the field is empty
        if (isset($_POST[$fieldName]) && Helper::isEmpty($fieldName)) {
            echo Helper::emptyFieldMessage($fieldName);
        }
        echo ''; // Return empty string if conditions are not met
    }
}
