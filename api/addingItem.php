<?php
    include_once("../inc/crud.php");
    date_default_timezone_set('Europe/Vienna');

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $itemCategory = $_POST['itemCategory'];
    $itemName = $_POST['itemName'];
    $itemQuantity = $_POST['itemQuantity'];
    $itemDate = date("Y-m-d");
    $itemTime = date("H:i:s");

    $crud = new Crud();
    $query = "INSERT INTO `items`
            (`firstname`, `lastname`, `item_category`, `item_name`, `item_quantity`, `item_date`, `item_time`) 
            VALUES ('$firstname','$lastname','$itemCategory','$itemName','$itemQuantity','$itemDate','$itemTime')"; 
    $result = $crud->execute($query);
?>