<?php
$host="localhost";
$user="raju";
$password="1234";
$database="mobile-mansion";
$conn=mysqli_connect($host,$user,$password,$database);
if(!$conn){
	die("Connection failed:".
mysqli_connect_error());
}
?>