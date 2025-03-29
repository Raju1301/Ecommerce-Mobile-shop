<?php
$servername = "localhost";
$username = "Emobiles";
$password = "Raju@2005/1301";
$conn=new mysqli($servername,$username,$password);
if($conn->connect_error)
{
	die("Connection Failed:".$conn->connect_error);
}
$sql="Create Database Ecommerce_db";
if($conn->query($sql)===TRUE)
{
	echo "Database 'Ecommerce_db'created successfully!";
}else
{
	echo "Error creating database:".
	$conn->error;
}
$conn->close();
?>