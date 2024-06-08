<?php
// Database configuration
$dbHost     = "database-1.cfn2upb3ncuy.ap-south-1.rds.amazonaws.com";
$dbUsername = "admin";
$dbPassword = "muhilanr";
$dbName     = "events";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


