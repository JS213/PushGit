<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    

    <!-- CSS Link -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/layerslider.css">
    <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- agent profile size -->
    <style>
    .modal-agent-image-small {
        max-width: 150px;
        margin: 0 auto;
        display: block;
    }
    </style>


    <!-- Title -->
    <title>4Real State - Agents</title>
</head>

<body>

    <!-- Page Wrapper -->
    <div id="page-wrapper">
        <!-- Header Start -->
        <?php include("include/header.php"); ?>
        <!-- Header End -->

        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-secondary double-down-line text-center mb-5">Agents</h2>
                </div>
            </div>
            <div class="row">
                <?php 
                $query = mysqli_query($con, "SELECT * FROM user WHERE utype='agent'");
                while ($row = mysqli_fetch_array($query)) {
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="hover-zoomer bg-white shadow-one mb-4">
                        <div class="overflow-hidden">
                            <img src="admin/user/<?php echo $row['6'];?>" alt="aimage"> 
                        </div>
                        <div class="py-3 text-center">
                            <h5 class="text-secondary hover-text-success">
                                <a href="#" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#agentModal"
                                   data-agent-name="<?php echo $row['1']; ?>"
                                   data-agent-email="<?php echo $row['2']; ?>"
                                   data-agent-contact="<?php echo $row['3']; ?>"
                                   data-agent-role="<?php echo $row['5']; ?>"
                                   data-agent-image="admin/user/<?php echo $row['6']; ?>"
                                >
                                    <?php echo $row['1']; ?>
                                </a>
                            </h5>
                            <span>Real Estate - Agent</span>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Footer Start -->
        <?php include("include/footer.php"); ?>
        <!-- Footer End -->

        <!-- Scroll to top -->
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
    </div>
    <!-- Wrapper End -->

    <!-- Modal -->
    
        <div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="agentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agentModalLabel">Agent Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Updated Image with Bootstrap's 'img-fluid' class and custom 'modal-agent-image-small' class -->
                        <img id="modal-agent-image" src="" alt="Agent Image" class="img-fluid modal-agent-image-small mb-3">
                        <p><b>Name:</b> <span id="modal-agent-name"></span></p>
                        <p><b>Email:</b> <span id="modal-agent-email"></span></p>
                        <p><b>Contact:</b> <span id="modal-agent-contact"></span></p>
                        <p><b>Role:</b> <span id="modal-agent-role"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    <!-- JS Links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Modal showing function to populate agent details dynamically
        $('#agentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var name = button.data('agent-name'); 
            var email = button.data('agent-email'); 
            var contact = button.data('agent-contact'); 
            var role = button.data('agent-role'); 
            var image = button.data('agent-image'); 

            var modal = $(this);
            modal.find('#modal-agent-name').text(name);
            modal.find('#modal-agent-email').text(email);
            modal.find('#modal-agent-contact').text(contact);
            modal.find('#modal-agent-role').text(role);
            modal.find('#modal-agent-image').attr('src', image);
        });
    </script>
</body>
</html>
