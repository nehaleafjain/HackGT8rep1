<?php
// Initialize the session
error_reporting(E_ALL);
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty valuesheroku logs -t --app
$new_drug_name = $drug_name = "";
$new_drug_name_err = $drug_name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new drug
    if(empty(trim($_POST["new_drug_name"]))){
        $new_drug_name_err = "Please enter the new drug.";     
    } elseif(strlen(trim($_POST["new_drug_name"])) < 6){
        $new_drug_name_err = "Drug must have atleast 6 characters.";
    } else{
        $new_drug_name = trim($_POST["new_drug_name"]);
    }
    
    // Validate confirm drag
    if(empty(trim($_POST["drug_name"]))){
        $drug_name_err = "Please confirm the drug.";
    } else{
        $drug_name = trim($_POST["drug_name"]);
        if(empty($new_drug_name_err) && ($new_drug_name != $drug_name)){
            $drug_name_err = "Drug did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_drug_name_err) && empty($drug_name_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET Drugs = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_Drugs, $param_id);
            
            // Set parameters
            $param_Drugs = $new_drug_name;
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Drugs updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Drugs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Drugs</h2>
        <p>Please fill out this form to reset your drugs.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>New Drugs</label>
                <input type="Drugs" name="new_drug_name" class="form-control <?php echo (!empty($new_drug_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_drug_name; ?>">
                <span class="invalid-feedback"><?php echo $new_drug_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Drugs</label>
                <input type="Drugs" name="drug_name" class="form-control <?php echo (!empty($drug_name_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $drug_name_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="index.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>