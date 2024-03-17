<?php

class FormGenerator {

    /**
     * Generate a form field.
     *
     * @param array $fieldDetails An array containing details of the field.
     * Each element should have keys: label, name, value, error, and optional required.
     * @return string The HTML markup for the field.
     */
    public static function generateField(array $fieldDetails): string {
        $html = '<label for="' . $fieldDetails['name'] . '">' . $fieldDetails['label'] . '</label>';
        $required = isset($fieldDetails['required']) && $fieldDetails['required'] ? 'required' : '';
        switch ($fieldDetails['type']) {
            case 'text':
                $html .= '<input type="text" name="' . $fieldDetails['name'] . '" value="' . htmlspecialchars($fieldDetails['value']) . '" ' . $required . '>';
                break;
            case 'checkbox':
                $checked = $fieldDetails['value'] ? 'checked' : '';
                $html .= '<input type="checkbox" name="' . $fieldDetails['name'] . '" value="1" ' . $checked . ' ' . $required . '>';
                break;
            case 'radio':
                $checked = $fieldDetails['value'] ? 'checked' : '';
                $html .= '<input type="radio" name="' . $fieldDetails['name'] . '" value="' . $fieldDetails['value'] . '" ' . $checked . ' ' . $required . '>';
                break;
            case 'select':
                $html .= '<select name="' . $fieldDetails['name'] . '" ' . ($fieldDetails['multiple'] ? 'multiple' : '') . ' ' . $required . '>';
                foreach ($fieldDetails['options'] as $optionValue => $optionLabel) {
                    $selected = in_array($optionValue, (array) $fieldDetails['value']) ? 'selected' : ''; // Check if the option value is in the selected array
                    $html .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionLabel . '</option>';
                }
                $html .= '</select>';
                break;
            case 'textarea':
                $html .= '<textarea rows="5" cols="55" name="' . $fieldDetails['name'] . '" ' . $required . '>' . htmlspecialchars($fieldDetails['value']) . '</textarea>';
                break;
// Add cases for other input types as needed
        }
        if (!empty($fieldDetails['error'])) {
            $html .= '<span class="error">' . $fieldDetails['error'] . '</span>';
        }
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
