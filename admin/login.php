<?php
session_start();

// if logged-in, redirect to index
if(isset($_SESSION["admin_id"]))
{
  header("location: index.php");
  exit;
}

if(isset($_POST["btn_login"]))
{
    $email_address = $_POST["email_address"];
    $admin_password = $_POST["password"];

    try {
        require_once  "../shared/connection.php";

        $stmt = $conn->prepare("SELECT * FROM tb_Admins WHERE email_address = :email_address;");
        $stmt->bindParam(':email_address', $email_address);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        if($result)
        {
            $hashed_password = $result["admin_password"];

            if(password_verify($admin_password, $hashed_password))
            {
                $_SESSION["admin_id"] = $result["admin_id"];
                $_SESSION["admin_name"] = $result["name"];

                $_SESSION["success"] = "Admin logged-in successfully!";
                header("location: index.php");
                exit;
            }
            else
            {
                $error = "Invalid username or password!";
            }
        }
        else
        {
            $error = "Invalid username or password!";
        }
    }
    catch(PDOException $e)
    {
        $error = $e->getMessage();
    }
}

$title = "Login";
$style = "
  ";

$headScript = "";

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


<div class="main_content_iner ">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="dashboard_header mb_50">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="s">
                                <h3>Login as Admin</h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="dashboard_breadcam text-end">
                                <p><a href="register.php">Register Here</a></p>
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
                                <div class="modal-header justify-content-center theme_bg_1">
                                    <h5 class="modal-title text_white">Log in</h5>
                                </div>
                                <div class="modal-body">
                                    <?php include "../Shared/Admin/notification_success.php"; ?>
                                    <?php include "../Shared/Admin/notification_error.php"; ?>
                                    <form action="login.php" method="post">
                                        <div class="">
                                            <input type="text" class="form-control" placeholder="Enter your email" name="email_address" value="<?php echo isset($_POST["email_address"])? $_POST["email_address"]: "";  ?>">
                                        </div>
                                        <div class="">
                                            <input type="password" class="form-control" placeholder="Password" name="password" value="<?php echo isset($_POST["password"])? $_POST["password"]: "";  ?>">
                                        </div>
                                        <button type="submit" class="btn_1 full_width text-center" name="btn_login">Log in</button>
                                        <p>Need an account? <a data-toggle="modal" data-target="#sing_up" data-dismiss="modal" href="register.php"> Register</a></p>
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

$footScript = "
  ";

include "../shared/Admin/foot_include.php";
?>