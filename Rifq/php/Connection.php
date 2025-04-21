<?php
$connection = mysqli_connect("localhost", "root", "root", "Rifq");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
