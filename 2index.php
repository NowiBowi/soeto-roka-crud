<?php 
require_once "1db.php";
require_once "1functions.php";

session_start();
if(!isset($_SESSION["username"])){
    header("location:2login.php");
}

$result = display_data();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Admin page</title>
</head>
<body class="bg-dark">
    <div class="container">
        <div class="row mt-5">
            <div class="col">
                <div class="card mt-5">
                    <div class="card-header">
                    <a href="1logout.php" class="btn btn-danger">Log out</a>

                        <h2 class="display-6 text-center">Welcome back, <?php echo $_SESSION["username"]; ?>!</h2>
                    </div>

                    <div class="container my-3">
                        <a href="2create.php" class="btn btn-primary" role="button">Add movie</a>
                    </div>

                    <div class="container">
                        <form action="" method="GET">
                            <input type="text" name="search" placeholder="Search movie titel">
                            <button name="submit">Search</button>
                        </form>
                    </div>
<br>
                    <?php
                    if(isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = $_GET['search'];
                        $sql = "SELECT * FROM films_tbl WHERE titel LIKE '%$search%'";
                        $result = mysqli_query($con, $sql);
                    }

                    if(mysqli_num_rows($result) > 0) {
                        echo "<table class='table table-bordered text-center'>
                            <tr>
                                <td>films_id</td>
                                <td>titel</td>
                                <td>Genre</td>
                                <td>Regisseur</td>
                                <td>Release Jaar</td>
                                <td>poster</td>
                                <td>Beoordeling</td>
                                <td>Edit</td>
                                <td>Delete</td>
                            </tr>";                    
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>" . $row['films_id'] . "</td>
                                <td>" . $row['titel'] . "</td>
                                <td>" . $row['genre'] . "</td>
                                <td>" . $row['regisseur'] . "</td>
                                <td>" . $row['releasejaar'] . "</td>
                                <td><img src='" . $row['poster'] . "' width='200' height='auto' alt='Movie Poster'></td>
                                <td>" . $row['beoordeling'] . "</td>
                                <td><a href='2edit.php?id=" . $row['films_id'] . "' class='btn btn-danger'>Edit</a></td>
                                <td><a href='2delete.php?id=" . $row['films_id'] . "' class='btn btn-danger'>Delete</a></td>
                                </tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "
                        <div class='alert alert-danger text-center mt-3'>
                        No results found
                        </div>";
                    }
                    ?>

                </div>
                <br>    
            </div>
        </div>
    </div>
</body>     
<footer></footer>
</html>
