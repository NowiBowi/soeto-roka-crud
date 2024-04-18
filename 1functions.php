<?php

require_once '1db.php';
    
function display_data() {
    global $con;
    $query = "SELECT * FROM films_tbl";
    $result = mysqli_query($con,$query);
    return $result;
}
?>