<?php
include_once "connectdb.php";

session_start();
if ($_SESSION['useremail'] == "" or $_SESSION['role'] == 'User') {
    header('location:index.php');
}

$id=$_GET['id'];

$select = $pdo->prepare("select * from tbl_product where pid=".$id);

$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);

$response=$row;

header('Content-Type: application.json');

echo json_encode($response);

?>