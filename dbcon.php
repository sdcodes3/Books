<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "books";

$con = mysqli_connect($hostname,$username,$password,$database);
if(!$con) {
    die("Error Connecting with the database!! <br>");
}

?>