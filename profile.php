<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

// Redirect to login if not logged in
if(!isset($_SESSION['uemail']))
{
    header("location:login.php");
}

// Initialize variables for messages
$error = '';
$msg = '';

// Handle Feedback Form Submission
if(isset($_POST['insert']))
{
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $uid = $_SESSION['uid'];
    
    if(!empty($name) && !empty($phone) && !empty($content))
    {
        $sql = "INSERT INTO feedback (uid, fdescription, status) VALUES ('$uid', '$content', '0')";
        $result = mysqli_query($con, $sql);
        if($result){
            $msg = "<p class='alert alert-success'>Feedback Sent Successfully</p>";
        }
        else{
            $error = "<p class='alert alert-warning'>Feedback Not Sent Successfully</p>";
        }
    }
    else{
        $error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
    }
}

// Handle Profile Picture Update
if(isset($_POST['updateProfile'])) {
    // Directory where the profile pictures will be saved
    $targetDir = "admin/user/";
    
    // Get user ID from session
    $uid = $_SESSION['uid'];
    
    // Check if file was uploaded without errors
    if(isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0){
        $fileName = basename($_FILES['profilePicture']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Allowed file types
        $allowedTypes = array('jpg','jpeg','png','gif');
        
        if(in_array(strtolower($fileType), $allowedTypes)){
            // Optionally, you can rename the file to avoid conflicts
            $newFileName = 'profile_' . $uid . '.' . $fileType;
            $newFilePath = $targetDir . $newFileName;
            
            // Move the file to the target directory
            if(move_uploaded_file($_FILES['profilePicture']['tmp_name'], $newFilePath)){
                // Update the user's profile picture in the database
                $sqlUpdate = "UPDATE user SET uimage = '$newFileName' WHERE uid = '$uid'";
                if(mysqli_query($con, $sqlUpdate)){
                    $msg = "<p class='alert alert-success'>Profile picture updated successfully.</p>";
                } else {
                    $error = "<p class='alert alert-warning'>Failed to update profile picture in the database.</p>";
                }
            } else {
                $error = "<p class='alert alert-warning'>Failed to upload the file.</p>";
            }
        } else {
            $error = "<p class='alert alert-warning'>Only JPG, JPEG, PNG, & GIF files are allowed.</p>";
        }
    } else {
        $error = "<p class='alert alert-warning'>Please select a valid image file.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Meta Tags -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="images/logo/4real_state.png">
    
    <!--	Fonts
    =========================================================-->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    
    <!--	Css Link
    =========================================================-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/layerslider.css">
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    
    <!--	Title
    =========================================================-->
    <title>4Real State - Profile</title>
</head>
<body>

<!--	Page Loader
=============================================================
<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
    <div class="d-flex justify-content-center y-middle position-relative">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
</div>
--> 


<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
        <?php include("include/header.php");?>
        <!--	Header end  -->
        
        <!--	Banner   --->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Profile</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!--	Banner   --->
         
         
        <!--	Submit property   -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-secondary double-down-line text-center">Profile</h2>
                    </div>
                </div>
                <div class="dashboard-personal-info p-5 bg-white">
                    <form action="#" method="post">
                        <h5 class="text-secondary border-bottom-on-white pb-3 mb-4">Feedback Form</h5>
                        <?php echo $msg; ?><?php echo $error; ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="user-id">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Full Name" >
                                </div>                
                                
                                <div class="form-group">
                                    <label for="phone">Contact Number</label>
                                    <input type="number" name="phone" class="form-control" placeholder="Enter Phone" maxlength="10" >
                                </div>

                                <div class="form-group">
                                    <label for="about-me">Your Feedback</label>
                                    <textarea class="form-control" name="content" rows="7" placeholder="Enter Text Here...." ></textarea>
                                </div>
                                <input type="submit" class="btn btn-info mb-4" name="insert" value="Send Feedback">
                            </div>
                        </form>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-5 col-md-12">
                                <?php 
                                    $uid = $_SESSION['uid'];
                                    $query = mysqli_query($con, "SELECT * FROM `user` WHERE uid='$uid'");
                                    while($row = mysqli_fetch_array($query))
                                    {
                                ?>
                                <div class="user-info mt-md-50">
                                    <img src="admin/user/<?php echo ($row['6']); ?>" alt="userimage" id="profileImage" height="200" width="170">
                                    <div class="mb-4 mt-3">
                                        <!-- Optional: Additional content -->
                                    </div>
                                    
                                    <div class="font-18">
                                        <div class="mb-1 text-capitalize"><b>Name:</b> <?php echo ($row['1']); ?></div>
                                        <div class="mb-1"><b>Email:</b> <?php echo ($row['2']); ?></div>
                                        <div class="mb-1"><b>Contact:</b> <?php echo ($row['3']); ?></div>
                                        <div class="mb-1 text-capitalize"><b>Role:</b> <?php echo ($row['5']); ?></div>
                                    </div>
                                    
                                    <!-- Change Profile Button -->
                                    <button type="button" class="btn btn-info mt-3" data-toggle="modal" data-target="#changeProfileModal">
                                        Change Profile Picture
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    
                </div>            
            </div>
        </div>
        <!--	Submit property   -->
        
        
        <!--	Footer   start-->
        <?php include("include/footer.php");?>
        <!--	Footer   end-->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End --> 

<!-- Change Profile Picture Modal -->
<div class="modal fade" id="changeProfileModal" tabindex="-1" role="dialog" aria-labelledby="changeProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="profile.php" method="post" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changeProfileModalLabel">Change Profile Picture</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <!-- Display any messages -->
          <?php echo $msg; ?><?php echo $error; ?>

          <div class="form-group">
            <label for="profilePicture">Select New Profile Picture</label>
            <input type="file" class="form-control-file" id="profilePicture" name="profilePicture" accept="image/*" required>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="updateProfile" class="btn btn-primary">Upload</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!--	Js Link
============================================================ -->
<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 

<script src="js/custom.js"></script>

<!-- Optional: Include Bootstrap JS and dependencies -->
<!-- Ensure Bootstrap JS is included after jQuery -->
<script>
    // Compare Functionality
    let compareList = JSON.parse(localStorage.getItem('compareList')) || [];

    // Add property to compare list using event delegation
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('compare-btn')) {
            const propertyId = e.target.dataset.id;
            const propertyName = e.target.dataset.name;
            const propertyPrice = e.target.dataset.price;

            // Check if property is already in the compare list
            if (compareList.some(property => property.id === propertyId)) {
                alert('This property is already added to the comparison list.');
                return;
            }

            // Add property to the list
            compareList.push({ id: propertyId, name: propertyName, price: propertyPrice });
            localStorage.setItem('compareList', JSON.stringify(compareList));
            alert('Property added to the comparison list.');
        }

        // Handle "View Comparison" button
        if (e.target && e.target.classList.contains('view-compare')) {
            if (compareList.length < 2) {
                alert('Please add at least two properties to compare.');
                return;
            }

            const compareIds = compareList.map(property => property.id).join(',');
            window.location.href = `compare.php?ids=${compareIds}`;
        }
    });

    // Optional: Clear comparison list on comparison page or provide a clear button
</script>
</body>
</html>
