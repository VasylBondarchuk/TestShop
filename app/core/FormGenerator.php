<?php

class FormGenerator {

    /**
     * Generate a form field.
     *
     * @param array $fieldDetails An array containing details of the field.
     *                            Each element should have keys: label, name, value, error, and optional required.
     * @return string The HTML markup for the field.
     */
    public static function generateField(array $fieldDetails): string {
    $html = '<label for="' . $fieldDetails['name'] . '">' . $fieldDetails['label'] . '</label>';
    $required = isset($fieldDetails['required']) && $fieldDetails['required'] ? 'required' : '';
    $value = isset($fieldDetails['value']) ? htmlspecialchars((string) $fieldDetails['value']) : ''; // Cast value to string before passing to htmlspecialchars
    $error = isset($fieldDetails['error']) ? '<span class="error">' . $fieldDetails['error'] . '</span>' : ''; // Get the error message from the fieldDetails array
    switch ($fieldDetails['type']) {
        case 'text':
            $html .= '<input type="text" name="' . $fieldDetails['name'] . '" value="' . $value . '" ' . $required . '>';
            break;
        case 'checkbox':
            $checked = $value ? 'checked' : '';
            $html .= '<input type="checkbox" name="' . $fieldDetails['name'] . '" value="1" ' . $checked . ' ' . $required . '>';
            break;
        case 'radio':
            $checked = $value ? 'checked' : '';
            $html .= '<input type="radio" name="' . $fieldDetails['name'] . '" value="' . $value . '" ' . $checked . ' ' . $required . '>';
            break;
        case 'select':
            $html .= '<select name="' . $fieldDetails['name'] . '" ' . ($fieldDetails['multiple'] ? 'multiple' : '') . ' ' . $required . '>';
            foreach ($fieldDetails['options'] as $optionValue => $optionLabel) {
                $selected = in_array($optionValue, (array) $value) ? 'selected' : ''; // Check if the option value is in the selected array
                $html .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionLabel . '</option>';
            }
            $html .= '</select>';
            break;
        case 'textarea':
            $html .= '<textarea rows="5" cols="55" name="' . $fieldDetails['name'] . '" ' . $required . '>' . $value . '</textarea>';
            break;
        // Add cases for other input types as needed
    }
    $html .= $error; // Append the error message
    $html .= '</br></br>';
    return $html;
}


    /**
     * Generate form fields based on an array of field details.
     *
     * @param array $fields An array of field details arrays.
     * @return string The HTML markup for the form fields.
     */
    public static function generateFields(array $fields): string {
        $html = '';
        foreach ($fields as $field) {
            $html .= self::generateField($field);
        }
        return $html;
    }
}