<?php
 require '../includes/config.inc.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="index.html"><img  style="width:80px; height:80px;" src="assets/images/logo/nile_logo.png" alt="Logo" srcset=""></a><br>
                            <span style="font-size:16px; color:#1D4DA1;"><?php echo "Welcome back ". $_SESSION['fname']. " ". $_SESSION['lname']; ?><span>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <?php 
                                 echo  "<li class='sidebar-title'>Student</li>";

                        ?>
                       

                        <li class="sidebar-item active ">
                            <a href="dashboard.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Components</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="component-alert.html">Alert</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-badge.html">Badge</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-breadcrumb.html">Breadcrumb</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-button.html">Button</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-card.html">Card</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-carousel.html">Carousel</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-dropdown.html">Dropdown</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-list-group.html">List Group</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-modal.html">Modal</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-navs.html">Navs</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-pagination.html">Pagination</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-progress.html">Progress</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-spinner.html">Spinner</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="component-tooltip.html">Tooltip</a>
                                </li>
                            </ul>
                        </li> -->
<!-- 
                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Extra Components</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="extra-component-avatar.html">Avatar</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-sweetalert.html">Sweet Alert</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-toastify.html">Toastify</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-rating.html">Rating</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="extra-component-divider.html">Divider</a>
                                </li>
                            </ul>
                        </li> -->

                        <!-- <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Layouts</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="layout-default.html">Default Layout</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="layout-vertical-1-column.html">1 Column</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="layout-vertical-navbar.html">Vertical with Navbar</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="layout-horizontal.html">Horizontal Menu</a>
                                </li>
                            </ul>
                        </li> -->
                        <!--<li class="sidebar-title">Forms &amp; Registrations</li>-->

                        <!--<li class="sidebar-item  has-sub">-->
                        <!--    <a href="#" class='sidebar-link'>-->
                        <!--        <i class="bi bi-hexagon-fill"></i>-->
                        <!--        <span>Student Management</span>-->
                        <!--    </a>-->
                        <!--    <ul class="submenu ">-->
                        <!--        <li class="submenu-item ">-->
                        <!--            <a href="form-element-input.php">Register Student</a>-->
                        <!--        </li>-->
                                <!-- <li class="submenu-item ">
                                    <a href="form-element-input-group.html">Input Group</a>
                                </li> -->
                                <!-- <li class="submenu-item ">
                                    <a href="form-element-select.html">Select</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-radio.html">Radio</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-checkbox.html">Checkbox</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-element-textarea.html">Textarea</a>
                                </li> -->
                        <!--    </ul>-->
                        <!--</li>-->

                        <!--<li class="sidebar-item  ">-->
                        <!--    <a href="form-layout.php" class='sidebar-link'>-->
                        <!--        <i class="bi bi-file-earmark-medical-fill"></i>-->
                        <!--        <span>Add Room</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <!-- <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-pen-fill"></i>
                                <span>Form Editor</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="form-editor-quill.html">Quill</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-editor-ckeditor.html">CKEditor</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-editor-summernote.html">Summernote</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-editor-tinymce.html">TinyMCE</a>
                                </li>
                            </ul>
                        </li> -->

                        <!--<li class="sidebar-item  ">-->
                        <!--    <a href="table.php" class='sidebar-link'>-->
                        <!--        <i class="bi bi-grid-1x2-fill"></i>-->
                        <!--        <span>All Rooms</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <!--<li class="sidebar-item  ">-->
                        <!--    <a href="allocate_room.php" class='sidebar-link'>-->
                        <!--        <i class="bi bi-file-earmark-spreadsheet-fill"></i>-->
                        <!--        <span>Allocate Room</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <!--<li class="sidebar-item  ">-->
                        <!--    <a href="empty_room.php" class='sidebar-link'>-->
                        <!--        <i class="bi bi-file-earmark-spreadsheet-fill"></i>-->
                        <!--        <span>Empty Room</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li class="sidebar-item  ">
                            <a href="ui-widgets-chatbox.php" class='sidebar-link'>
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                                <span>ChatBot</span>
                            </a>
                        </li>
                        <li class="sidebar-item  ">
                            <a href="ui-widgets-pricing.php" class='sidebar-link'>
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                                <span>Apply for a room</span>
                            </a>
                        </li>
                        
                         <li class="sidebar-item  ">
                            <a href="ui-widgets-todolist.php" class='sidebar-link'>
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                                <span>Status</span>
                            </a>
                        </li>
                        
                         <li class="sidebar-item  ">
                            <a href="rating.php" class='sidebar-link'>
                                <i class="bi bi-file-earmark-medical-fill"></i>
                                <span>Your Review</span>
                            </a>
                        </li>
                        <!--<li class="sidebar-title">Hostel Management</li>-->

                        <!--<li class="sidebar-item  has-sub">-->
                        <!--    <a href="#" class='sidebar-link'>-->
                        <!--        <i class="bi bi-pentagon-fill"></i>-->
                        <!--        <span>Modules</span>-->
                        <!--    </a>-->
                        <!--    <ul class="submenu ">-->
                        <!--        <li class="submenu-item ">-->
                        <!--            <a href="ui-widgets-chatbox.php">Chat</a>-->
                        <!--        </li>-->
                        <!--        <li class="submenu-item ">-->
                        <!--            <a href="ui-widgets-pricing.php">Apply for a room</a>-->
                        <!--        </li>-->
                        <!--        <li class="submenu-item ">-->
                        <!--            <a href="ui-widgets-todolist.php">Status</a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->

                        <!-- <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-egg-fill"></i>
                                <span>Icons</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="ui-icons-bootstrap-icons.html">Bootstrap Icons </a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-icons-fontawesome.html">Fontawesome</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-icons-dripicons.html">Dripicons</a>
                                </li>
                            </ul>
                        </li> -->

                        <!--<li class="sidebar-item  has-sub">-->
                        <!--    <a href="#" class='sidebar-link'>-->
                        <!--        <i class="bi bi-bar-chart-fill"></i>-->
                        <!--        <span>Analytics</span>-->
                        <!--    </a>-->
                        <!--    <ul class="submenu ">-->
                        <!--        <li class="submenu-item ">-->
                        <!--            <a href="ui-chart-chartjs.php">Charts</a>-->
                        <!--        </li>-->
                        <!--        <li class="submenu-item ">-->
                        <!--            <a href="ui-chart-apexcharts.php">Apexcharts</a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->

                        <!-- <li class="sidebar-item  ">
                            <a href="ui-file-uploader.html" class='sidebar-link'>
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                <span>File Uploader</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-map-fill"></i>
                                <span>Maps</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="ui-map-google-map.html">Google Map</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="ui-map-jsvectormap.html">JS Vector Map</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Pages</li>

                        <li class="sidebar-item  ">
                            <a href="application-email.html" class='sidebar-link'>
                                <i class="bi bi-envelope-fill"></i>
                                <span>Email Application</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="application-chat.html" class='sidebar-link'>
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Chat Application</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="application-gallery.html" class='sidebar-link'>
                                <i class="bi bi-image-fill"></i>
                                <span>Photo Gallery</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="application-checkout.html" class='sidebar-link'>
                                <i class="bi bi-basket-fill"></i>
                                <span>Checkout Page</span>
                            </a>
                        </li> -->

                        <!-- <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-person-badge-fill"></i>
                                <span>Authentication</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="auth-login.html">Login</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="auth-register.html">Register</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="auth-forgot-password.html">Forgot Password</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-x-octagon-fill"></i>
                                <span>Errors</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="error-403.html">403</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="error-404.html">404</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="error-500.html">500</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Raise Support</li>

                        <li class="sidebar-item  ">
                            <a href="https://zuramai.github.io/mazer/docs" class='sidebar-link'>
                                <i class="bi bi-life-preserver"></i>
                                <span>Documentation</span>
                            </a>
                        </li>

                        <li class="sidebar-item  ">
                            <a href="https://github.com/zuramai/mazer/blob/main/CONTRIBUTING.md" class='sidebar-link'>
                                <i class="bi bi-puzzle"></i>
                                <span>Contribute</span>
                            </a>
                        </li> -->

                        <li class="sidebar-item  ">
                            <a href="../includes/logout.inc.php" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
