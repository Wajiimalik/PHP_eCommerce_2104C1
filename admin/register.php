<?php

session_start();
// if logged-in, redirect to index
if(isset($_SESSION["admin_id"]))
{
  header("location: index.php");
  exit;
}


if(isset($_POST["btn_register"]))
{
    require_once  "../shared/connection.php";

    $name = $_POST["full_name"];
    $contact_num = $_POST["contact_number"];
    $email_address = $_POST["email"];
    $admin_password = $_POST["password"];
    $user_address = $_POST["address"];
    $gender = $_POST["gender"];
    $hashPassword = password_hash($admin_password, PASSWORD_BCRYPT);

    try
    {
        $stmt = $conn->prepare("INSERT INTO tb_Admins(name, contact_num, email_address, admin_password, user_address, gender) VALUES(:name, :contact_num, :email_address, :admin_password, :user_address, :gender);");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':contact_num', $contact_num);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':admin_password', $hashPassword);
        $stmt->bindParam(':user_address', $user_address);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
        $_SESSION["success"] = "Admin registered successfully!";
        header("location: login.php");
        exit;
    }
    catch(PDOException $e)
    {
        if($e->errorInfo[1] == 1062)
        {
            $error = "Email already exists!";
        }
        else
        {
            $error = $e->getMessage();
            // $error = "Something went wrong!";
        }
    }
    $conn = null;
}

$title = "Register";
$style = "
  ";

$headScript = '
';

$headList = [
    '<link rel="stylesheet" href="../Templates/Admin/css/bootstrap1.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/themefy_icon/themify-icons.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/font_awesome/css/all.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/scroll/scrollable.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/css/metisMenu.css">',
    '<link rel="stylesheet" href="../Templates/Admin/css/style1.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/css/colors/default.css" id="colorSkinCSS">',
];

include "../shared/Admin/head_include.php";
?>



<!-- main body -->
<div class="main_content_iner ">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="dashboard_header mb_50">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="dashboard_header_title">
                                <h3> Admin Registration</h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="dashboard_breadcam text-end">
                                <p><a href="login.php">Login Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="white_box mb_30">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">

                            <div class="modal-content cs_modal">
                                <div class="modal-header theme_bg_1 justify-content-center">
                                    <h5 class="modal-title text_white">Resister as Admin</h5>
                                </div>
                                <div class="modal-body">
                                    <?php include "../Shared/Admin/notification_success.php"; ?>
                                    <?php include "../Shared/Admin/notification_error.php"; ?>
                                    <form action="register.php" method="post">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="full_name">Full Name</label>
                                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required maxlength="100" value="<?php echo isset($_POST["full_name"])? $_POST["full_name"]: "";  ?>">
                                            </div>
                                            <div class=" col-md-6">
                                                <label class="form-label" for="contact_number">Contact Number</label>
                                                <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="0333-3333333" required maxlength="15"  value="<?php echo isset($_POST["contact_number"])? $_POST["contact_number"]: "";  ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="email">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required maxlength="100" value="<?php echo isset($_POST["email"])? $_POST["email"]: "";  ?>">
                                            </div>
                                            <div class=" col-md-6">
                                                <label class="form-label" for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required maxlength="20" value="<?php echo isset($_POST["password"])? $_POST["password"]: "";  ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main Street, Karachi, Pakistan" required maxlength="250" value="<?php echo isset($_POST["address"])? $_POST["address"]: "";  ?>">
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label" for="gender">Gender</label>
                                                <select id="gender" name="gender" class="form-control form-select">
                                                    <option value="male" <?php echo isset($_POST["gender"])? ($_POST["gender"] == "male"? "selected": "") : "";  ?>>Male</option>
                                                    <option value="female" <?php echo isset($_POST["gender"])? ($_POST["gender"] == "female"? "selected": "") : "";  ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button name="btn_register" type="submit" class="btn_1 full_width text-center"">Register as Admin</button>
                                        <p>Already have an account? <a data-toggle="modal" data-target="#sing_up" data-dismiss="modal" href="login.php"> Login</a></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$scriptList = [
    '<script src="../Templates/Admin/js/jquery1-3.4.1.min.js"></script>',

    '<script src="../Templates/Admin/js/popper1.min.js"></script>',

    '<script src="../Templates/Admin/js/bootstrap1.min.js"></script>',

    '<script src="../Templates/Admin/js/metisMenu.js"></script>',

    '<script src="../Templates/Admin/vendors/scroll/perfect-scrollbar.min.js"></script>',
    '<script src="../Templates/Admin/vendors/scroll/scrollable-custom.js"></script>',

    '<script src="../Templates/Admin/js/custom.js"></script>',
];

$footScript = '
';

include "../shared/Admin/foot_include.php";
?>