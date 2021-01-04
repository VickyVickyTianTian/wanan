<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php

require_once("controllerUserData.php");
require_once("dotenv.php");

$email    = $_SESSION['email'];
$password = $_SESSION['password'];
if ($email&& $password) {
    $sql     = "SELECT * FROM users WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status     = $fetch_info['status'];
        $code       = $fetch_info['code'];
        if ($status == "verified") {
            if ($code) {
                header('Location: reset-code.php');
            }
        } else {
            header('Location: user-otp.php');
        }
    }
} else {
    header('Location: login.php');
}
if ($fetch_info['kyc_status'] === "completed") {
    header('Location: dashboard.php');

}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>WAN - All in WAN</title>
    <!-- Custom CSS -->
    <link href="assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- bootstrap 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
    <!-- link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
        wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/js/plugins/piexif.min.js" type="text/javascript"></script>
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
        This must be loaded before fileinput.min.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- popper.min.js below is needed if you use bootstrap 4.x. You can also use the bootstrap js
       3.3.x versions without popper.min.js. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
        dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/themes/fa/theme.js"></script>

    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <!-- apps -->
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <script src="assets/extra-libs/c3/d3.min.js"></script>
    <script src="assets/extra-libs/c3/c3.min.js"></script>
    <script src="assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
    <script src="dist/js/pages/dashboards/dashboard1.min.js"></script>

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-brand">
                        <!-- Logo icon -->
                        <a href="index.php">
                            <b class="logo-icon">
                                <!-- Dark Logo icon -->
                                <img src="assets/images/logo-icon3.gif" alt="homepage" class="dark-logo" />
                                <!-- Light Logo icon -->
                                <img src="assets/images/logo-icon3.gif" alt="homepage" class="light-logo" />
                            </b>
                            <!--End Logo icon -->
                            <!-- Logo text -->
                            <span class="logo-text">
                                <!-- dark Logo text -->
                                <img src="assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                                <!-- Light Logo text -->
                                <img src="assets/images/logo-light-text.png" class="light-logo" alt="homepage" />
                            </span>
                        </a>
                    </div>
                    <!-- End Logo -->
                    <!-- Toggle which is visible on mobile only -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                        <li class="nav-item dropdown">
                            <h3 class="text-dark font-weight-medium mb-1">Welcome <?=$fetch_info['username']?>!</h3>
                        </li>
                    </ul>
                    <!-- Right side toggle and nav items -->
                    <ul class="navbar-nav float-right">
                    <li class="nav-item d-none d-md-block">
                            <a class="nav-link" href="javascript:void(0)">
                                <div class="customize-input">
                                    <select
                                        class="custom-select form-control bg-white custom-radius custom-shadow border-0">
                                        <option selected>EN</option>
                                        <!-- language
                                        <option value="1">AB</option>
                                        <option value="2">AK</option>
                                        <option value="3">BE</option>
                                        -->
                                    </select>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="assets/images/users/profile-pic.jpg" alt="user" class="rounded-circle"
                                    width="40">
                                <span class="ml-2 d-none d-lg-inline-block">
                                    <span>Hello,</span>
                                    <span class="text-dark"><?=$fetch_info['username'] ?></span>
                                    <i data-feather="chevron-down" class="svg-icon"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <a class="dropdown-item" href="logout.php"><i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                        <!-- User profile -->
                    </ul>
                </div>
            </nav>
        </header>

        <!-- End Topbar header -->

        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="index.php" aria-expanded="false">
                                <i data-feather="home" class="feather-icon"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">WAN Account</span></li>

                        <li class="sidebar-item"> <a class="sidebar-link" href="account.php"
                                aria-expanded="false"><i data-feather="book" class="feather-icon"></i><span
                                    class="hide-menu">My Account
                                </span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="card.php"
                                aria-expanded="false"><i data-feather="credit-card" class="feather-icon"></i><span
                                    class="hide-menu">My Card</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="transaction.php"
                                aria-expanded="false"><i class="fas fa-database"></i></i><span
                                    class="hide-menu">My Transaction</span></a></li>

                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">User Setting</span></li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="profile.php"
                                aria-expanded="false"><i class="icon-question"></i></i><span
                                    class="hide-menu">My Profile
                                </span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="setting.php"
                                aria-expanded="false"><i class="icon-question"></i></i><span
                                    class="hide-menu">Account Setting
                                </span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="logout.php"
                                aria-expanded="false"><i class="icon-question"></i></i><span
                                    class="hide-menu">Log Out
                                </span></a>
                        </li>

                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">Help Center</span></li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="faq.php"
                                aria-expanded="false"><i class="icon-question"></i></i><span
                                    class="hide-menu">FAQ
                                </span></a>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="ticket.php"
                                aria-expanded="false"><i class="fas fa-ticket-alt"></i></i><span
                                    class="hide-menu">Submit Ticket</span></a></li>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->


        <!-- Page wrapper  -->

        <div class="page-wrapper">

            <!-- Bread crumb and right sidebar toggle -->

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><h4><a href="index.html">KYC Completion</a></h4>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <!-- Start Sales Charts Section -->
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-10">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Complete KYC to unlock features</h4>
                                <p>KYC verification is expected to be completed within 1 to 2 working days. Please check your status timely.</p>
                            </div>

                        <form action="kyc.php" method="POST" name="form_individual" autocomplete="">
                            <div class="col-md-12 col-lg-12">
                            <div class="card-body">
                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-2">

                                    <li class="nav-item">
                                        <a href="#individual" data-toggle="tab" aria-expanded="false"
                                            class="nav-link rounded-0 active">
                                            <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Individual</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#corporate" data-toggle="tab" aria-expanded="true"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Corporate</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" data-toggle="tab" aria-expanded="true"
                                            class="nav-link rounded-0">
                                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block"></span>
                                        </a>
                                    </li>
                                </ul>
<br>

                                <div class="tab-content">
                                    <div class="tab-pane show active" id="individual">

        <div class="col-md-12 col-lg-8">
            <div class="form-group">
               <label class="text-dark">Full Name (as per Passport)</label>
               <input class="form-control" type="text" name="Fullname1" placeholder="Enter English Name" required >
            </div>
            <div class="form-group">
               <label class="text-dark">Full Name (as per IC)</label>
               <input class="form-control" type="text" name="Fullname2" placeholder="Enter Chinese Name" required >
            </div>
            <div class="form-group">
                <label class="text-dark">Passport No.</label>
                <input class="form-control" type="text" name="Fullname1" placeholder="Enter Passport No." required>
            </div>
            <div class="form-group">
                <label class="text-dark">IC No.</label>
                <input class="form-control" type="text" name="Fullname1" placeholder="Enter IC. No." required>
            </div>

        <div class="form-group">
           <label class="text-dark">Passport Photo</label>
            <div class="file-loading">
                <input id="input-passport-photo" name="file-upload[]" type="file" accept="image/*" multiple>
            </div>
        </div>

        <div class="form-group">
           <label class="text-dark">IC Photo</label>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="IC1" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
            <br>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="IC2" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div  class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
            <br>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="IC3" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div  class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
<br>
            <div class="col-lg-12 text-center mt-2">
                <input class="btn btn-block btn-dark" type="submit" name="submit_kyc" value="Submit">
            </div>
        </div>
    </form>
        </div>
                    </div>

                                    <div class="tab-pane " id="corporate">

<div class="col-md-12 col-lg-8">
            <div class="form-group">
               <label class="text-dark">Company Name (please provide the company for account withdrawal)</label>
               <input class="form-control" type="text" name="Fullname1" placeholder="Enter company Chinese Name (if any)" required >
            </div>
            <div class="form-group">
               <label class="text-dark">Company Name (English)</label>
               <input class="form-control" type="text" name="Fullname2" placeholder="Enter company English Name" required>
            </div>
            <div class="form-group">
                <label class="text-dark">Company Registration No.</label>
                <input class="form-control" type="text" name="Fullname1" placeholder="Enter company registration number " required>
            </div>
            <div class="form-group">
           <label class="text-dark">Company Certificate</label>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="CompanyCertificate" id="inputGroupFile01">
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
            <br>
            <hr>
            <br>
            <div class="form-group">


               <label class="text-dark">Director's Full Name (as per Passport)</label>
               <input class="form-control" type="text" name="Fullname1" placeholder="Enter English Name" required >
            </div>
            <div class="form-group">
               <label class="text-dark">Director's Full Name (as per IC)</label>
               <input class="form-control" type="text" name="Fullname2" placeholder="Enter Chinese Name" required >
            </div>
            <div class="form-group">
                <label class="text-dark">Director's Passport No.</label>
                <input class="form-control" type="text" name="Fullname1" placeholder="Enter Passport No." required>
            </div>
            <div class="form-group">
                <label class="text-dark">Director's IC No.</label>
                <input class="form-control" type="text" name="Fullname1" placeholder="Enter IC. No." required>
            </div>

        <div class="form-group">
           <label class="text-dark">Director's Passport Photo</label>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="Passport1" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
            <br>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="Passport2" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div  class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
        </div>

        <div class="form-group">
           <label class="text-dark">Director's IC Photo</label>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="IC1" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
            <br>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="IC2" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div  class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
            <br>
            <div class="input-group">
                <div class="custom-file">
                     <input type="file" class="custom-file-input" name="IC3" id="inputGroupFile01" required>
                     <label class="custom-file-label" for="inputGroupFile04">Choose file</label>&nbsp&nbsp
                </div>&nbsp&nbsp
                <div  class="input-group-append">
                     <button class="btn btn-outline-secondary" type="button">Upload</button>
                </div>
            </div>
<br>
            <div class="col-lg-12 text-center mt-2">
                                    <input class="btn btn-block btn-dark" type="submit" name="submit_kyc" value="Submit">
                                </div>
        </div>
    </form>
        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            <!-- forms--->
                        </form>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-1"></div>
                </div>
            </div>
            </div>
            </div>
              </div>
            <!-- End Container fluid  -->
            <!-- footer -->
            <footer class="footer text-center text-muted">
                All Rights Reserved by WAN Group.
            </footer>
            </footer>
            <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- End Wrapper -->
    <!-- All Jquery -->

    <script>
        $(document).ready(function() {
            const $passportEL = $("#input-passport-photo");
            $passportEL.fileinput({
                allowedFileExtensions: ['jpg', 'png', 'gif'],
                uploadUrl: "/fileupload.php",
                uploadAsync: true,
                deleteUrl: "/filedelete.php",
                showUpload: false, // hide upload button
                overwriteInitial: false, // append files to initial preview
                minFileCount: 1,
                maxFileCount: 5,
                browseOnZoneClick: true,
                initialPreviewAsData: true,
            }).on("filebatchselected", function(event, files) {
                $passportEL.fileinput("upload");
            });
        });
    </script>
</body>

</html>
