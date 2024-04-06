<?php
use app\core\Helper;

$firstNameFieldDetails = [
    'required' => false,
    'label' => 'First name:',
    'type' => 'text',
    'name' => 'first_name', 
    'value' => Helper::getPostValue('first_name'),
    'error' => $errors['first_name'] ?? ''
];
$lastNameFieldDetails = [
    'required' => false,
    'label' => 'Last name:',
    'type' => 'text',
    'name' => 'last_name', 
    'value' => Helper::getPostValue('last_name') ?? '',
    'error' => $errors['last_name'] ?? ''
];

$phoneNumberFieldDetails = [
    'required' => false,
    'label' => 'Phone number:',
    'type' => 'text',
    'name' => 'telephone', 
    'value' => Helper::getPostValue('telephone') ?? '',
    'error' => $errors['telephone'] ?? ''
];

$emailFieldDetails = [
    'required' => false,
    'label' => 'Email:',
    'type' => 'text',
    'name' => 'email', 
    'value' => Helper::getPostValue('email') ?? '',
    'error' => $errors['email'] ?? ''
];
$cityFieldDetails = [
    'required' => false,
    'label' => 'City:',
    'type' => 'text',
    'name' => 'city', 
    'value' => Helper::getPostValue('city') ?? '',
    'error' => $errors['city'] ?? ''
];

$passwordFieldDetails = [
    'required' => false,
    'label' => 'Password:',
    'type' => 'text',
    'name' => 'password', 
    'value' => Helper::getPostValue('password') ?? '',
    'error' => $errors['password'] ?? ''
];

$passwordConfirmationFieldDetails = [
    'required' => false,
    'label' => 'Confirm password:',
    'type' => 'text',
    'name' => 'pass_confirm', 
    'value' => Helper::getPostValue('pass_confirm') ?? '',
    'error' => $errors['pass_confirm'] ?? ''
];

$customerRegisterFormFields = [
    $firstNameFieldDetails,
    $lastNameFieldDetails,
    $phoneNumberFieldDetails,
    $emailFieldDetails,
    $cityFieldDetails,
    $passwordFieldDetails,
    $passwordConfirmationFieldDetails
    ];

