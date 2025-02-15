<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = 'Kieuviet2004@';
$database = 'toy_store';

$conn = new mysqli($host, $user, $password, $database);
if($conn->connect_error){
    die('Connection failed: '.$conn->connect_error);
}

