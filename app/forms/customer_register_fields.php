<?php

$firstNameFieldDetails = [
    'required' => true,
    'label' => 'First name:',
    'type' => 'text',
    'name' => 'first_name', 
    'value' => Helper::getPostValue('first_name') ?? '',
    'error' => Helper::isEmpty('first_name') ? Helper::emptyFieldMessage('first_name') : ''
];
$lastNameFieldDetails = [
    'required' => true,
    'label' => 'Last name:',
    'type' => 'text',
    'name' => 'last_name', 
    'value' => Helper::getPostValue('last_name') ?? '',
    'error' => Helper::emptyFieldMessage('last_name')
];

$phoneNumberFieldDetails = [
    'required' => true,
    'label' => 'Phone number:',
    'type' => 'text',
    'name' => 'telephone', 
    'value' => Helper::getPostValue('telephone') ?? '',
    'error' => Helper::emptyFieldMessage('telephone')
];

$emailFieldDetails = [
    'required' => true,
    'label' => 'Email:',
    'type' => 'text',
    'name' => 'email', 
    'value' => Helper::getPostValue('email') ?? '',
    'error' => Helper::emptyFieldMessage('email')
];
$cityFieldDetails = [
    'required' => true,
    'label' => 'City:',
    'type' => 'text',
    'name' => 'city', 
    'value' => Helper::getPostValue('city') ?? '',
    'error' => Helper::emptyFieldMessage('city')
];

$passwordFieldDetails = [
    'required' => true,
    'label' => 'Password:',
    'type' => 'text',
    'name' => 'password', 
    'value' => Helper::getPostValue('password') ?? '',
    'error' => Helper::emptyFieldMessage('password')
];

$passwordConfirmationFieldDetails = [
    'required' => true,
    'label' => 'Confirm password:',
    'type' => 'text',
    'name' => 'pass_confirm', 
    'value' => Helper::getPostValue('pass_confirm') ?? '',
    'error' => Helper::emptyFieldMessage('pass_confirm')
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

