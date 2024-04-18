<?php
require_once "1db.php";

session_start();

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM user_tbl WHERE user_naam='" . $username . "' 
    AND user_password='" . $password . "'  ";

    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if ($row["user_type"] == "user") {
            $_SESSION["username"] = $username;
            header("location:1view.php");
        } elseif ($row["user_type"] == "admin") {
            $_SESSION["username"] = $username;
            header("location:2index.php");
        }
    } else {
        $message = "Username or password is incorrect";
    }
}
?>

<!-- HERE STARTS THE HTML CODING STUFF -->
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="logstyle.css">
    <title>CineSphere-login</title>
    <link rel="shortcut icon" href="uploads/popcorn.png">
</head>

<body>

<nav>
        <!-- logo -->
        <a href="#" class="logo">
            <img src="uploads/logo 2.png"/>
        </a>

        <!-- menu -->
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="movie.php">Movies</a></li>
            <li><a href="2login.php">Log in</a></li>
        </ul>

        <!-- Search -->
        <div class="search">
            <input type="text" placeholder="Search">
        </div>
    </nav>

    <center>

            <form action="#" method="POST">
        <div class="wrapper">
                 <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            
        </div>

            </form>
            
            <!-- Display message below the form -->
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <br><br>
        </div>
    </center>
</body>

</html>
