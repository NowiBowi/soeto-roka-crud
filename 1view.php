<?php 

session_start();
if(!isset($_SESSION["username"])){
    header("location:2login.php");
}

require_once "1db.php";
require_once "1functions.php";


$result = display_data();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>User page</title>
</head>
<body class="bg-dark">
    <div class="container">
        <div class="row mt-5">
            <div class="col">
                <div class="card mt-5">
                    <div class="card-header">
                    <a href="1logout.php" class="btn btn-danger">Log out</a>

                        
                        <h2 class="display-6 text-center">Welcome back, <?php echo $_SESSION["username"]; ?>!</h2>
    <br>
                    </div>
<br>
                    <div class="container">
                        <form action="" method="GET">
                            <input type="text" name="search" placeholder="Search movie">
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
                                <td>Film Titel</td>
                                <td>Genre</td>
                                <td>Regisseur</td>
                                <td>Release Jaar</td>
                                <td>poster</td>
                                <td>Beoordeling</td>
                            </tr>";                    
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>" . $row['titel'] . "</td>
                                <td>" . $row['genre'] . "</td>
                                <td>" . $row['regisseur'] . "</td>
                                <td>" . $row['releasejaar'] . "</td>
                                <td><img src='" . $row['poster'] . "' width='250' height='auto' alt='Movie Poster'></td>
                                <td>" . $row['beoordeling'] . "</td>
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
