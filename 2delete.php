<?php

if(isset($_GET["id"])) {
    require_once "1db.php";
    $id = $_GET["id"];

    $sql = "DELETE FROM films_tbl WHERE films_id=$id";
    $con->query($sql);
}
header("location: 2index.php");
exit;

?>