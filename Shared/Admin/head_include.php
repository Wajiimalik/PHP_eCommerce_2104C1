<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>J Store Admin | <?php echo $title; ?></title>
    <link rel="icon" href="../Templates/Admin/img/j_store_small-logo.png" type="image/png">

    <!-- scripts for head -->
    <script>
        <?php
        if (isset($headScript) && !empty($headScript)) {
            echo $headScript;
        }
        ?>
    </script>

    <!-- link elements for head -->
    <?php
    if (isset($headList) && !empty($headList)) {
        foreach ($headList as $fileName) {
            echo $fileName;
        }
    }
    ?>

    <!-- internal style -->
    <style type="text/css">
        <?php
        if (isset($style) && !empty($style)) {
            echo $style;
        }
        ?>
    </style>
</head>

<body class="crm_body_bg">


    <nav class="sidebar">
        <div class="logo d-flex justify-content-center p-1">
            <a class="large_logo p-0" href="index.php"><img src="../Templates/Admin/img/j_store-logo.png" alt="" style="height: 70px;"></a>
            <a class="small_logo" href="index.php"><img src="../Templates/Admin/img/j_store_small-logo.png" alt="" style="height: 70px;"></a>
            <div class="sidebar_close_icon d-lg-none" style="margin-right: 50px;">
                <i class="ti-close"></i>
            </div>
        </div>
        <ul id="sidebar_menu">

            <!-- links for non-logged-in users  -->
            <?php
            if (!isset($_SESSION["admin_id"])) {
            ?>
                <li class="">
                    <a href="login.php" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/8.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Login</span>
                        </div>
                    </a>
                </li>
                <li class="">
                    <a href="register.php" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/4.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Register</span>
                        </div>
                    </a>
                </li>
            <?php
            }
            ?>


            <?php
            if (isset($_SESSION["admin_id"])) {
            ?>
                <!-- links for non-logged-in users  -->
                <li class="">
                    <a href="index.php" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/8.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Dashboard</span>
                        </div>
                    </a>
                </li>
                <li class="">
                    <a href="#" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/8.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Orders</span>
                        </div>
                    </a>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/dashboard.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Products </span>
                        </div>
                    </a>
                    <ul>
                        <li><a href="add_product.php">Add Product</a></li>
                        <li><a href="products.php">All Products</a></li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/dashboard.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Categories </span>
                        </div>
                    </a>
                    <ul>
                        <li><a href="#">Add Category</a></li>
                        <li><a href="#">All Categories</a></li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/dashboard.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Customers </span>
                        </div>
                    </a>
                    <ul>
                        <li><a href="#">Add Customer</a></li>
                        <li><a href="#">All Customers</a></li>
                    </ul>
                </li>
                <hr>
                <!-- edit_profile.php -->
                <li class="">
                    <a href="edit_profile.php" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/8.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Edit Profile</span>
                        </div>
                    </a>
                </li>
                <li class="">
                    <a href="logout.php" aria-expanded="false">
                        <div class="nav_icon_small">
                            <img src="../Templates/Admin/img/menu-icon/8.svg" alt="">
                        </div>
                        <div class="nav_title">
                            <span>Logout</span>
                        </div>
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>
    </nav>

    <section class="main_content dashboard_part large_header_bg">

        <div   class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0 ">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none">
                            <i class="ti-menu"></i>
                        </div>
                        <div class="line_icon open_miniSide d-none d-lg-block">
                            <img src="../Templates/Admin/img/line_img.png" alt="">
                        </div>
                        <div class="serach_field-area d-flex align-items-center">
                            <div class="search_inner">
                                <form action="#">
                                    <div class="search_field">
                                        <input type="text" placeholder="Search">
                                    </div>
                                    <button type="submit"> <img src="../Templates/Admin/img/icon/icon_search.svg" alt=""> </button>
                                </form>
                            </div>
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="header_notification_warp d-flex align-items-center">
                                <?php
                                if (!isset($_SESSION["admin_id"])) {
                                ?>
                                    <li>
                                        <a title="Register" href="register.php"> <img src="../Templates/Admin/img/menu-icon/4.svg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a title="Login" href="login.php"> <img src="../Templates/Admin/img/menu-icon/8.svg" alt="">
                                        </a>
                                    </li>

                                <?php
                                }
                                ?>
                            </div>


                            <?php
                            if (isset($_SESSION["admin_id"])) {
                            ?>
                                <div class="profile_info">
                                    <div class="main-title">
                                        <h3 class="m-0">Hello, <?php echo $_SESSION["admin_name"]; ?></h3>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>