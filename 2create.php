<?php
$titel = "";
$genre = "";
$regisseur = "";
$releasejaar = "";
$poster = "";

$errorMessage = "";
$successMessage = "";


require_once '1db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titel = $_POST["titel"];
    $genre = $_POST["genre"];
    $regisseur = $_POST["regisseur"];
    $releasejaar = $_POST["releasejaar"];
    $poster = $_POST["poster"];

    do {
        if( empty($titel) || empty($genre) || empty($regisseur) || empty($releasejaar) || empty($poster)){
            $errorMessage = "All the fields are required!";
            break;
        } 
        //THE YOUTUBE VID SAID THAT THIS IS TO ADD NEW DATA??
        $sql = "INSERT INTO films_tbl (titel, genre, regisseur, releasejaar, poster)" . 
                "VALUES ('$titel', '$genre','$regisseur','$releasejaar','$poster')";
                //THE FOLLOWING CODE (TRY & CATCH) IS ADDED (NOT INCLUDED IN YT VID). THX TO CHATGPT :)
                try {
                    $result = $con->query($sql);
                    if ($result) {
                        $successMessage = "Movie added successfully";
                        header("location: 2index.php");
                        exit;
                    }
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1062) { // Error code for duplicate entry
                        $errorMessage = "Duplicate entry: A movie with the same title already exists.";
                    } else {
                        $errorMessage = "Error: " . $e->getMessage();
                    }
                }    } while (false);
}
//After this php code, the HTML code will start
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- THIS HTML CODE REQUIRES BOOTSTRAP -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <title>Add new movie</title>
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

        <form method="POST">
            <!-- THIS CODE IS WHERE YOU ENTER NEW DATA IN THE DATABASE -->
            <div class="row-mb-3">
                <label class="col-sm-3 col-form-label">Titel:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="titel" value="<?php echo $titel; ?>">
                </div>
            </div>
            
            <!-- DROPDOWN FOR THE GENRE THINGY -->
            <div class="row-mb-3"> 
                <label class="col-sm-3 col-form-label">Genre:</label>
                <div class="col-sm-6">
                    <select name="genre" id="" class="form-select">
                    <option value="" disabled selected hidden style="font-style:italic; color:gray;">Select genre</option>
                        <?php
                        $sql = "SELECT * FROM genre_tbl";
                        $genre = mysqli_query($con,$sql);
                        while($g = mysqli_fetch_array($genre)){

                        ?>
                        <option value="<?php echo $g['genre_name']?>"><?php  echo $g['genre_name']?></option>
                        
                        <?php }?>
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

<!-- HERE I WOULD LIKE TO SUBMIT AN IMAGE AND THEN DISPLAY -->
            <div class="row-mb-3">
                <label class="col-sm-3 col-form-label">Poster:</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="poster" value="<?php echo $poster; ?>">
                </div>
            </div>
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="2index.php" class="btn btn-primary" role="button">Cancel</a>

                </div>

            </div>
        </form>

    </div>
    
</body>
</html>
