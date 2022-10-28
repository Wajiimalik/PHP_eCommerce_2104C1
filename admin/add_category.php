<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

require_once  "../shared/connection.php";

// add product
if (isset($_POST["btn_add_category"])) {
    require_once  "../shared/image_upload.php";
    try {
        $fileResult = UploadImage($_FILES["image_file"]);
        $category_name = $_POST["cat_name"];
        if ($fileResult["status"] == "success") {
            $category_image = $fileResult["uploadedFile"];
            $inserted_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
            $updated_by_admin = $_SESSION["admin_id"];

            $stmt = $conn->prepare("INSERT INTO tb_Categories (cat_name, cat_image, inserted_at, updated_at, updated_by_admin) VALUES (:cat_name, :cat_image, :inserted_at, :updated_at, :updated_by_admin);");

            $stmt->bindParam(':cat_name', $category_name);
            $stmt->bindParam(':cat_image', $category_image);
            $stmt->bindParam(':inserted_at', $inserted_at);
            $stmt->bindParam(':updated_at', $updated_at);
            $stmt->bindParam(':updated_by_admin', $updated_by_admin);
            $stmt->execute();
            $_SESSION["success"] = "New Category added successfully!";
            header("location: category.php");
            exit;
        } else {
            throw new Exception($fileResult["msg"]);
        }
    } catch (Exception $e) {
         // file uploaded succesfully but error in name or sku
         if ($fileResult["status"] == "success") {
             DeleteImage($fileResult["uploadedFile"]);
         }

        $stmt = $conn->prepare("SELECT cat_name FROM tb_Categories WHERE cat_name = :cat_name;");
        $stmt->bindParam(':cat_name', $category_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_category_name = $stmt->fetch();

        // $stmt = $conn->prepare("SELECT sku FROM tb_Products WHERE sku = :sku;");
        // $stmt->bindParam(':sku', $sku);
        // $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        // $result_sku = $stmt->fetch();

        $error = "<ul>";
        if ($fileResult["status"] == "error") {
            $error .= $e->getMessage();
        }
        if ($result_category_name) {
            $error .= "<li><b>Category name</b> already exists!</li>";
        }
        if (!($result_category_name)) {
            $error .= $e->getMessage();
        }
        $error .= "</ul>";
    }
}

$conn = null;

$title = "Add Categories";
$style = "
  ";

$headScript = "";

$headList = [
    '<link rel="stylesheet" href="../Templates/Admin/css/bootstrap1.min.css" />',

    '<link rel="stylesheet" href="../Templates/Admin/vendors/themefy_icon/themify-icons.css" />',

    '<link rel="stylesheet" href="../Templates/Admin/vendors/scroll/scrollable.css" />',

    '<link rel="stylesheet" href="../Templates/Admin/vendors/font_awesome/css/all.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/text_editor/summernote-bs4.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/datatable/css/jquery.dataTables.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/datatable/css/responsive.dataTables.min.css" />',
    '<link rel="stylesheet" href="../Templates/Admin/vendors/datatable/css/buttons.dataTables.min.css" />',


    '<link rel="stylesheet" href="../Templates/Admin/css/metisMenu.css">',

    '<link rel="stylesheet" href="../Templates/Admin/css/style1.css" />',
];

include "../shared/Admin/head_include.php";
?>


<div class="main_content_iner overly_inner">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
                    <div class="page_title_left d-flex align-items-center">
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Add New Categories</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Add Category</li>
                        </ol>
                    </div>
                    <div class="page_title_right">
                        <a id="btn_add_new_product" href="category.php" class="btn_1">
                            <i class="ti-menu-alt"></i> All Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php include "../shared/Admin/notification_success.php";  ?>
        <?php include "../shared/Admin/notification_error.php";  ?>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="modal-header justify-content-start theme_bg_1">
                    <h5 class="modal-title text_white">Add New Category</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="add_category.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="cat_id" id="cat_id">
                                    <div class="modal-body">
                                        <div class="container">
                                            <?php include "../shared/Admin/notification_error.php";  ?>
                                            <div class="row mb-3">
                                                <label for="product_name" class="form-label col-sm-4 col-form-label">Category Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="cat_name" name="cat_name" placeholder="Product Name" value="<?php echo isset($_POST["cat_name"]) ? $_POST["cat_name"] : "";  ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="cat_image" class="form-label col-sm-4 col-form-label">Category Image</label>
                                                <div class="col-sm-5">
                                                    <input type="file" class="form-control" id="image_file" name="image_file" onchange="ChangeShownImage(event)" value="<?php echo isset($_FILES['image_file']) ? $_FILES['image_file'] : "";  ?>">
                                                    <p class="text-primary">Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB</p>
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <img src="../Templates//Admin//img//package.png" style="height: 120px" id="shownImage" />
                                                </div>
                                                <script>
                                                    function ChangeShownImage(event) {
                                                        document.getElementById("shownImage").src = URL.createObjectURL(event.target.files[0]);
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Clear</button> -->
                                        <button type="submit" name="btn_add_category" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Add Category</button>
                                    </div>
                                </form>
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

    '<script src="../Templates/Admin/vendors/datatable/js/jquery.dataTables.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/dataTables.responsive.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/dataTables.buttons.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/buttons.flash.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/jszip.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/pdfmake.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/vfs_fonts.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/buttons.html5.min.js"></script>',
    '<script src="../Templates/Admin/vendors/datatable/js/buttons.print.min.js"></script>',

    '<script src="../Templates/Admin/vendors/scroll/perfect-scrollbar.min.js"></script>',
    '<script src="../Templates/Admin/vendors/scroll/scrollable-custom.js"></script>',

    '<script src="../Templates/Admin/js/custom.js"></script>',
    '<script src="../module_js_scripts/Admin/products.js"></script>',
];

$footScript = "
  ";

include "../shared/Admin/foot_include.php";
?>