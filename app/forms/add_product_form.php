<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    $uploadDir = PRODUCT_IMAGE_UPLOAD_DIR;    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }   
    $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);
    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
        $this->registry['errorMessage'].= " Виникла проблема з завантаженням зображення.";
    }
}
?>
