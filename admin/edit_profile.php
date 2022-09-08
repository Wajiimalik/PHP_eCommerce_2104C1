<?php

session_start();
// if not logged-in, redirect to login
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}
require_once  "../shared/connection.php";
try {
    $stmt = $conn->prepare("SELECT * FROM tb_Admins WHERE admin_id = :admin_id;");
    $stmt->bindParam(':admin_id', $_SESSION["admin_id"]);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();
} catch (PDOException $e) {
    $error = $e->getMessage();
}

if (isset($_POST["btn_edit_admin"])) {
    $name = $_POST["full_name"];
    $contact_num = $_POST["contact_number"];
    $email_address = $_POST["email"];
    $admin_password = $_POST["password"];
    $user_address = $_POST["address"];
    $gender = $_POST["gender"];

    try {
        $stmt = $conn->prepare("UPDATE tb_Admins SET name=:name, contact_num=:contact_num, user_address=:user_address, gender=:gender WHERE admin_id =:admin_id;");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':contact_num', $contact_num);
        $stmt->bindParam(':user_address', $user_address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':admin_id', $_SESSION["admin_id"]);
        $stmt->execute();
        $_SESSION["success"] = "Profile updated successfully!";
        header("location: edit_profile.php");
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

$title = "Edit Profile";
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
                            <div class="dashboard_header_title">
                                <h3>Edit Admin Profile</h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="dashboard_breadcam text-end">
                                <p><a href="index.php">Dashboard</a> <i class="fas fa-caret-right"></i> Edit Profile
                                </p>
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
                                    <h5 class="modal-title text_white">Edit Admin Profile</h5>
                                </div>
                                <div class="modal-body">
                                    <?php include "../Shared/Admin/notification_success.php"; ?>
                                    <?php include "../Shared/Admin/notification_error.php"; ?>
                                    <form action="edit_profile.php" method="post">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="full_name">Full Name</label>
                                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required maxlength="100" value="<?php echo $result["name"]; ?>">
                                            </div>
                                            <div class=" col-md-6">
                                                <label class="form-label" for="contact_number">Contact Number</label>
                                                <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="0333-3333333" required maxlength="15" value="<?php echo $result["contact_num"]; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="email">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required maxlength="100" value="<?php echo $result["email_address"]; ?>" readonly>
                                            </div>
                                            <div class=" col-md-6">
                                                <label class="form-label" for="password">Change Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" maxlength="20">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main Street, Karachi, Pakistan" required maxlength="250" value="<?php echo $result["user_address"]; ?>">
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label" for="gender">Gender</label>
                                                <select id="gender" name="gender" class="form-control">
                                                    <option value="male" <?php echo ($result["gender"] == "male") ? "selected" : ""; ?>>Male</option>
                                                    <option value="female" <?php echo ($result["gender"] == "female") ? "selected" : ""; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn_1 full_width text-center" name="btn_edit_admin">Update</button>
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