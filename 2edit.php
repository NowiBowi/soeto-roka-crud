<?php
require_once '1db.php';

$id = "";
$titel = "";
$genre = "";
$regisseur = "";
$releasejaar = "";
$poster = "";

$errorMessage = "";
$successMessage = "";

$target_dir = "uploads/";

// Check if the directory exists, if not, create it
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);  // 0777 gives full permissions
}

$file = "";  // Initialize $file variable

if(isset($_POST['submit'])) {
    if(isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        // File path to store the uploaded file
        $file = $target_dir . basename($_FILES["poster"]["name"]);
        
        // Check if file already exists
        if (file_exists($file)) {
            $errorMessage = "File already exists.";
        } else {
            // Try to move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["poster"]["tmp_name"], $file)) {
                // File uploaded successfully
                $poster = basename($_FILES["poster"]["name"]);
            } else {
                $errorMessage = "There was an error uploading your file.";
            }
        }
    } else {
        $errorMessage = "Poster file is required!";
    }
}

// THIS IS THE UPDATE PART

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("location: 2index.php");
        exit;
    }
    $id = $_GET["id"];

    $sql = "SELECT * FROM films_tbl WHERE films_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titel = $row["titel"];
        $genre = $row["genre"];
        $regisseur = $row["regisseur"];
        $releasejaar = $row["releasejaar"];
        $poster = $row["poster"];
    } else {
        header("location: 2index.php");
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $titel = $_POST["titel"];
    $genre = $_POST["genre"];
    $regisseur = $_POST["regisseur"];
    $releasejaar = $_POST["releasejaar"];
    $poster = $file;  // Use the stored file path

    if (empty($titel) || empty($genre) || empty($regisseur) || empty($releasejaar) || empty($poster)) {
        $errorMessage = "All the fields are required!";
    } else {
        $sql = "UPDATE films_tbl 
                SET titel = ?, genre = ?, regisseur = ?, releasejaar = ?, poster = ? 
                WHERE films_id = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $titel, $genre, $regisseur, $releasejaar, $poster, $id);

        try {
            if ($stmt->execute()) {
                $successMessage = "Movie updated successfully";
                header("location: 2index.php");
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $errorMessage = "Duplicate entry: A movie with the same title already exists.";
            } else {
                $errorMessage = "Error: " . $e->getMessage();
            }
        }
    }
}
?>
    <!-- From now on the following code is HTML (with a bit of PHP) -->
<!DOCTYPE html>
<html lang="en">
<head>
<!-- THIS HTML CODE REQUIRES BOOTSTRAP -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <title>Edit movie data</title>
</head>
<body class="bg-secondary">
    <div class="container my-5">
        <h2>New movie</h2>
        <?php
        //THIS PHP CODE IS TO SHOW AN ERROR MESSAGE WHEN NOT ALL FIELDS ARE FILLED IN
        if( !empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert dismissable fade show' role='alert'>
             <strong>$errorMessage</strong>
             <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'> </button>
            </div>
            ";
        }

        ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $id; ?>" name="id">
            <!-- THIS CODE IS WHERE YOU ENTER NEW DATA IN THE DATABASE -->
            <div class="row-mb-3">
                <label class="col-sm-3 col-form-label">Titel:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="titel" value="<?php echo $titel; ?>">
                </div>
            </div>

            <!-- I'M GONNA PUT A DROPDOWN MENU FOR GENRE -->
            <div class=""> 
                <label class="col-sm-3 col-form-label">Genre:</label>
                <div class="col-sm-6">
                    <select name="genre" id="" class="form-select">
                        <?php
                        $sql = "SELECT * FROM genre_tbl";
                        $genreResult = mysqli_query($con, $sql);
                        while($g = mysqli_fetch_array($genreResult)){
                            $selected = ($g['genre_name'] == $genre) ? 'selected' : '';
                            echo "<option value='{$g['genre_name']}' $selected>{$g['genre_name']}</option>";                        
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row-mb-3">
                <label class="col-sm-3 col-form-label">Regisseur</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="regisseur" value="<?php echo $regisseur; ?>">
                </div>
            </div>

            <div class="row-mb-3">
                <label class="col-sm-3 col-form-label">Release Jaar</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="releasejaar" value="<?php echo $releasejaar; ?>">
                </div>
            </div>

            <div class="row-mb-3">
                <label class="col-sm-3 col-form-label">Current Poster:</label>
                    <div class="col-sm-6">
                        <img src="<?php echo $poster; ?>" width="200" alt="Current Movie Poster">
                    </div>
            </div>
<br>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="input-group">
                        <input type="file" class="form-control" id="poster" name="poster" aria-describedby="inputGroupFileAddon">
                    </div>
                </div>
            </div>

            <br>
            <br>

            <?php
            //THIS MESSAGE POPS UP WHEN ALL FIELDS ARE FILLED
            if(!empty($successMessage)){
                echo "
                <div class='row mb-3'>
                  <div class='offset-sm-3 col-sm-6'>
                      <div class='alert alert-success alert-dismissable fade show' role='alert'>
                         <strong>$successMessage</strong>
                          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'> </button>
                      </div>
                    </div>
                </div>
                 ";
                  
            }

            ?>

            <div class="row mb-3">
                <!-- THIS CODE IS JUST TO SUBMIT AND CANCEL THE DATA ENTRY -->
                <div class="col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="2index.php" class="btn btn-primary" role="button">Cancel</a>

                </div>

            </div>
        </form>

    </div>
    
</body>
</html>
