<?php
namespace app\forms;

use app\core\Helper;

$emailFieldDetails = [
    'required' => false,
    'label' => 'Email:',
    'type' => 'text',
    'name' => 'email', 
    'value' => Helper::getPostValue('email'),
    'error' => $errors['email'] ?? ''
];
$lpasswordFieldDetails = [
    'required' => false,
    'label' => 'Password:',
    'type' => 'text',
    'name' => 'password', 
    'value' => Helper::getPostValue('password') ?? '',
    'error' => $errors['password'] ?? ''
];

$customerLoginFormFields = [
    $emailFieldDetails,
    $lpasswordFieldDetails
    ];

