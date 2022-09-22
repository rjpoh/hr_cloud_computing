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
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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


<?php
// Include config file
require_once "config.php";

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// AWS Info


// Define variables and initialize with empty values
$fname = $lname = $pskills = $location = $imageUpload = "";
$fname_err = $lname_err = $pskills_err = $location_err = $imageUpload_err = "";
 
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
         $imageUpload = "images/default-profile-icon.jpg";  
     } else{
         $imageUpload = "images/" . $input_imageUpload;
     }
    
    $bucketName = 'teherjie-bucket';
	$IAM_KEY = NULL;
	$IAM_SECRET = NULL;

try {
        // You may need to change the region. It will say in the URL when the bucket is open
        // and on creation.
        $s3 = S3Client::factory(
            array(
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),
                'version' => 'latest',
                'region'  => 'us-east-1'
            )
        );
    } catch (Exception $e) {
        // We use a die, so if this fails. It stops here. Typically this is a REST call so this would
        // return a json object.
        die("Error: " . $e->getMessage());
    }

// For this, I would generate a unqiue random string for the key name. But you can do whatever.
 
    $keyName = 'userProfileImg/'. $imageUpload;
    $pathInS3 = 'https://s3.us-east-1.amazonaws.com/' . $bucketName . '/' . $keyName;

    

    // Add it to S3
    try {
        
        // Uploaded:
        $file = $_FILES["imageUpload"]["tmp_name"];

        echo $file;

        $s3->putObject(
            array(
                'Bucket'=> $bucketName,
                'Key' =>  $keyName,
                'Body' => $file,
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'ContentType' => 'image/png'
            )
        );

    } catch (S3Exception $e) {
        die('s3Error:' . $e->getMessage() . ' ' . $file);
    } catch (Exception $e) {
        die('Error:' . $e->getMessage() . ' ' . $file);
    }


    echo 'Done';
    
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
            $param_image = "s3://teherjie-bucket/userProfileImg/" . $imageUpload;
            
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
