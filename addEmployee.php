<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$fname = $lname = $pskills = $location = "";
$fname_err = $lname_err = $pskills_err = $location_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_fname = trim($_POST["fname"]);
    if(empty($input_fname)){
        $name_err = "Please enter your first name.";
    } else{
        $fname = $input_fname;
    }
    
    // Validate last name
    $input_lname = trim($_POST["lname"]);
    if(empty($input_lname)){
        $lname_err = "Please enter last name.";     
    } else{
        $lname = $input_lname;
    }
    
    // Validate primary skills
    $input_pskills = trim($_POST["pskills"]);
    if(empty($input_pskills)){
        $pskills_err = "Please enter the primary skills.";     
    } else{
        $pskills = $input_pskills;
    }

     // Validate location
     $input_location = trim($_POST["location"]);
     if(empty($input_location)){
         $location_err = "Please enter the primary skills.";     
     } else{
         $location = $input_location;
     }
 
     // Validate image
     $input_imageUpload = trim($_POST["imageUpload"]);
     if(empty($input_imageUpload)){
         $imageUpload = "images/default-profile-icon.jpg"  ;  
     } else{
         $imageUpload = "images/" . $input_imageUpload;
     }
    
    // Check input errors before inserting in database
    if(empty($fname_err) && empty($lname_err) && empty($pskills_err) && empty($location_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (fname, lname, primarySkills, location, photoURL) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, 'sssss', $param_fname, $param_lname, $param_pskills, $param_location, $param_image);
            
            // Set parameters
            $param_fname = $fname;
            $param_lname = $lname;
            $param_pskills = $pskills;
            $param_location = $location;
            $param_image = "image.jpg";
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                        <h2 class="mt-5">Create Record</h2>
                        <p>Please fill this form and submit to add employee record to the database.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="fname" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fname; ?>">
                                <span class="invalid-feedback"><?php echo $name_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="lname" class="form-control <?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lname; ?>">
                                <span class="invalid-feedback"><?php echo $lname_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Primary Skills</label>
                                <input type="text" name="pskills" class="form-control <?php echo (!empty($pskills_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pskills; ?>">
                                <span class="invalid-feedback"><?php echo $pskills_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control <?php echo (!empty($location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $location; ?>">
                                <span class="invalid-feedback"><?php echo $location_err;?></span>
                            </div>
                            <div class="form-group">
                                  <label>Image</label>
                                  <input type="file" name="imageUpload" class="form-control <?php echo (!empty($imageUpload_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $imageUpload; ?>">
                                  <span class="invalid-feedback"><?php echo $imageUpload_err;?></span>
                            </div>
                            
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                        </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
