<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

// if no product id is send, redirect to homepage
if (!(isset($_GET["id"]) || isset($_POST["cat_id"]))) {
    header("location: category.php");
    exit;
}

// fetch id 
if (isset($_GET["id"])) {
    $cat_id = $_GET["id"];
}

if (isset($_POST["cat_id"])) {
    $cat_id = $_POST["cat_id"];
}

// get product from db
require_once  "../shared/connection.php";
try {
    // find product
    $stmt = $conn->prepare(" SELECT * FROM tb_Categories WHERE cat_id = :cat_id;");
    $stmt->bindParam(':cat_id', $cat_id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $category = $stmt->fetch();

    // if not found any product with entered id
    if (!$category) {
        header("location: category.php");
        exit;
    }

    // find cat.s for select box
    // $stmt = $conn->prepare(" SELECT cat_id, cat_name FROM tb_Categories ORDER BY cat_name ASC; ");
    // $stmt->execute();
    // $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // $categories = $stmt->fetchAll();
    // if (count($categories) < 1) {
    //     $_SESSION["error"] = "Please enter categories before adding a product!";
    //     header("location: categories.php");
    //     exit;
    // }
} catch (PDOException $e) {
    // $error = "Something went wrong!";
    $error = $e->getMessage();
    $conn = null;
}

if (isset($_POST["btn_edit_category"])) {
    try {
        $category_name = $_POST["cat_name"];
        $cat_image = (isset($_FILES["image_file"]) && $_FILES["image_file"]["error"] == 0) ? $_FILES["image_file"] : "";
        $updated_at = date("Y-m-d H:i:s");
        $updated_by_admin = $_SESSION["admin_id"];

        if ($cat_image == "") {
            $stmt = $conn->prepare("UPDATE tb_Categories SET cat_name=:cat_name, updated_at=:updated_at, updated_by_admin=:updated_by_admin WHERE cat_id = :cat_id;");

            $stmt->bindParam(':cat_name', $category_name);
            $stmt->bindParam(':updated_at', $updated_at);
            $stmt->bindParam(':updated_by_admin', $updated_by_admin);
            $stmt->bindParam(':cat_id', $cat_id);
            $stmt->execute();
            $_SESSION["success"] = "Category UPDATED successfully!";
            header("location: category.php");
            exit;
        } else {
            require_once  "../shared/image_upload.php";
            $fileResult = UploadImage($cat_image);

            if ($fileResult["status"] == "success") {
                // get old path of image to delete
                $image_path = $category['cat_image'];

                $stmt = $conn->prepare("UPDATE tb_Categories SET cat_name=:cat_name, cat_image = :cat_image,updated_at=:updated_at, updated_by_admin=:updated_by_admin WHERE cat_id = :cat_id;");

                $stmt->bindParam(':cat_image', $fileResult['uploadedFile']);
                $stmt->bindParam(':cat_name', $category_name);
                $stmt->bindParam(':updated_at', $updated_at);
                $stmt->bindParam(':updated_by_admin', $updated_by_admin);
                $stmt->bindParam(':cat_id', $cat_id);
                $stmt->execute();

                // delete only if there is no error in executing query
                DeleteImage($image_path);

                $_SESSION["success"] = "Category UPDATED successfully!";
                header("location: category.php");
                exit;
            } else {
                throw new Exception($fileResult["msg"]);
            }
        }
    } catch (Exception $e) {
        /// file uploaded succesfully but error in name or sku
        if ($cat_image != "") {
            if ($fileResult["status"] == "success") {
                DeleteImage($fileResult["uploadedFile"]);
            }
        }

        $stmt = $conn->prepare("SELECT cat_name FROM tb_Categories WHERE cat_name = :cat_name;");
        $stmt->bindParam(':cat_name', $category_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_cat_name = $stmt->fetch();

        $error = "<ul>";
        if ($cat_image != "") {
            if ($fileResult["status"] == "error") {
                $error .= $e->getMessage();
            }
        }
        if ($result_cat_name) {
            $error .= "<li><b>Product name</b> already exists!</li>";
        }

        if (!($result_cat_name)) {
            $error .= $e->getMessage();
        }
        $error .= "</ul>";
    }
}

$title = "Edit Category";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Edit Category</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="category.php">Category</a></li>
                            <li class="breadcrumb-item active">Edit Category</li>
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
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="modal-header justify-content-start theme_bg_1">
                    <h5 class="modal-title text_white">Edit Category</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="edit_category.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="cat_id" id="cat_id" value="<?php echo $cat_id; ?>">
                                    <div class="modal-body">
                                        <div class="container">
                                            <?php include "../shared/Admin/notification_success.php";  ?>
                                            <?php include "../shared/Admin/notification_error.php";  ?>
                                            <div class="row mb-3">
                                                <label for="cat_name" class="form-label col-sm-4 col-form-label">Category Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="cat_name" name="cat_name" placeholder="Category Name" value="<?php echo isset($_POST["cat_name"]) ? $_POST["cat_name"] : $category["cat_name"];  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="cat_image" class="form-label col-sm-4 col-form-label">Category Image</label>
                                                <div class="col-sm-5">
                                                    <input type="file" class="form-control" id="image_file" name="image_file" onchange="ChangeShownImage(event)">
                                                    <p class="text-primary">Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB</p>
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <img src="<?php echo $category["cat_image"];  ?>" style="height: 120px" id="shownImage" />
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
                                        <button type="submit" name="btn_edit_category" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Update Category</button>
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