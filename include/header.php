<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "realestatephp";    


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user type if user is logged in
$utype = null;
if (isset($_SESSION['uemail'])) {
    $uemail = $_SESSION['uemail'];

    // Prepare and execute query to get user type (utype)
    $stmt = $conn->prepare("SELECT utype FROM user WHERE uemail = ?");
    $stmt->bind_param("s", $uemail);
    $stmt->execute();
    $stmt->bind_result($utype);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<header id="header" class="transparent-header-modern fixed-header-bg-white w-100">
    <div class="main-nav secondary-nav hover-success-nav py-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <a class="navbar-brand position-relative" href="index.php">
                            <img class="nav-logo" src="images/logo/4real_white.png" alt="">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" 
                            data-target="#navbarSupportedContent" 
                            aria-controls="navbarSupportedContent" 
                            aria-expanded="false" 
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item dropdown"> 
                                    <a class="nav-link" href="index.php" role="button" aria-haspopup="true" aria-expanded="false">Home</a>
                                </li>
                                <li class="nav-item"> <a class="nav-link" href="about.php">About</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="contact.php">Contact</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="property.php">Properties</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="agent.php">Agent</a> </li>
                                
                                <?php if (isset($_SESSION['uemail'])) { ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account</a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"> <a class="nav-link" href="profile.php">Profile</a> </li>
                                        <?php if ($utype === 'agent') { ?>
                                            <li class="nav-item"> <a class="nav-link" href="feature.php">Your Property</a> </li>
                                        <?php } ?>
                                        <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a> </li>
                                    </ul>
                                </li>
                                <?php } else { ?>
                                <li class="nav-item"> <a class="nav-link" href="login.php">Login/Register</a> </li>
                                <?php } ?>
                            </ul>

                            <!-- Submit Property Button (Only for Agents) -->
                            <?php if ($utype === 'agent') { ?>
                                <a class="btn btn-success d-none d-xl-block" 
                                   style="border-radius:30px;" 
                                   href="submitproperty.php">
                                    Submit Property
                                </a>
                            <?php } ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
