<?php

session_start();
session_destroy();
header("location:2login.php");
?>