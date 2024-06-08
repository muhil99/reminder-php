<?php

$conn = mysqli_connect("database-1.cfn2upb3ncuy.ap-south-1.rds.amazonaws.com", "admin", "muhilanr", "events");

if (!$conn) {
    echo "Connection Failed";
}