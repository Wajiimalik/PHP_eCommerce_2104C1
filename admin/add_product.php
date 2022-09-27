<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

require_once  "../shared/connection.php";
try {
    $stmt = $conn->prepare("SELECT * FROM tb_Categories;");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $categories = $stmt->fetchAll();

    if (count($categories) < 1) {
        $_SESSION["error"] = "Please enter categories before adding product!";
        header("location: categories.php");
        exit;
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
}


// add product
if (isset($_POST["btn_add_product"])) {
    require_once  "../shared/image_upload.php";
    try {
        $fileResult = UploadImage($_FILES["image_file"]);
        $product_name = $_POST["product_name"];
        $sku = $_POST["sku"];
        if ($fileResult["status"] == "success") {
            $category_id = $_POST["category"];
            $price = $_POST["price"];
            $stock = $_POST["stock"];
            $product_image = $fileResult["uploadedFile"];
            $long_description = isset($_POST["long_description"]) ? $_POST["long_description"] : null;
            $inserted_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
            $updated_by_admin = $_SESSION["admin_id"];

            $stmt = $conn->prepare("INSERT INTO tb_Products (product_name, sku, product_image, long_description, price, stock, category_id, inserted_at, updated_at, updated_by_admin) VALUES (:product_name, :sku, :product_image, :long_description, :price, :stock, :category_id, :inserted_at, :updated_at, :updated_by_admin);");

            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':product_image', $product_image);
            $stmt->bindParam(':long_description', $long_description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':inserted_at', $inserted_at);
            $stmt->bindParam(':updated_at', $updated_at);
            $stmt->bindParam(':updated_by_admin', $updated_by_admin);
            $stmt->execute();
            $_SESSION["success"] = "New Product added successfully!";
            header("location: products.php");
            exit;
        } else {
            throw new Exception($fileResult["msg"]);
        }
    } catch (Exception $e) {
        // file uploaded succesfully but error in name or sku
        if ($fileResult["status"] == "success") {
            DeleteImage($fileResult["uploadedFile"]);
        }

        $stmt = $conn->prepare("SELECT product_name FROM tb_Products WHERE product_name = :product_name;");
        $stmt->bindParam(':product_name', $product_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_product_name = $stmt->fetch();

        $stmt = $conn->prepare("SELECT sku FROM tb_Products WHERE sku = :sku;");
        $stmt->bindParam(':sku', $sku);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result_sku = $stmt->fetch();

        $error = "<ul>";
        if ($fileResult["status"] == "error") {
            $error .= $e->getMessage();
        }
        if ($result_product_name) {
            $error .= "<li><b>Product name</b> already exists!</li>";
        }
        if ($result_sku) {
            $error .=  "<li><b>SKU code</b> already exists!</li>";
        }
        if (!($result_product_name || $result_sku)) {
            $error .= $e->getMessage();
        }
        $error .= "</ul>";
    }
}

$conn = null;

$title = "Add Products";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Add New Products</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Add Product</li>
                        </ol>
                    </div>
                    <div class="page_title_right">
                        <a id="btn_add_new_product" href="products.php" class="btn_1">
                            <i class="ti-menu-alt"></i> All Products
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
                    <h5 class="modal-title text_white">Add New Product</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="add_product.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="product_id" id="product_id">
                                    <div class="modal-body">
                                        <div class="container">
                                            <?php include "../shared/Admin/notification_error.php";  ?>
                                            <div class="row mb-3">
                                                <label for="product_name" class="form-label col-sm-4 col-form-label">Product Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name" value="<?php echo isset($_POST["product_name"]) ? $_POST["product_name"] : "";  ?>" required maxlength="100">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="sku" class="form-label col-sm-4 col-form-label">SKU Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU Code" value="<?php echo isset($_POST["sku"]) ? $_POST["sku"] : "";  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="category" class="form-label col-sm-4 col-form-label">Category</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    $categories_options = array();
                                                    foreach ($categories as $row) {
                                                        if (isset($_POST["category"])) {
                                                            if ($_POST["category"] == $row["cat_id"]) {
                                                                array_push($categories_options, '<option value="' . $row["cat_id"] . '" selected>' . $row["cat_name"] . '</option>');
                                                            } else {
                                                                array_push($categories_options, '<option value="' . $row["cat_id"] . '">' . $row["cat_name"] . '</option>');
                                                            }
                                                        } else {
                                                            array_push($categories_options, '<option value="' . $row["cat_id"] . '">' . $row["cat_name"] . '</option>');
                                                        }
                                                    }
                                                    ?>

                                                    <select id="category" name="category" class="form-select form-control">
                                                        <?php
                                                        foreach ($categories_options as $row) {
                                                            echo $row;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="price" class="form-label col-sm-4 col-form-label">Price</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-text">
                                                            <span class="text_white">PKR</span>
                                                        </div>
                                                        <input type="number" class="form-control" aria-label="PKR" id="price" name="price" placeholder="Price" value="<?php echo isset($_POST["price"]) ? $_POST["price"] : "";  ?>">
                                                        <div class="input-group-text">
                                                            <span class="text_white">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="stock" class="form-label col-sm-4 col-form-label">Stock</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control" aria-label="PKR" id="stock" name="stock" placeholder="Stock" value="<?php echo isset($_POST["stock"]) ? $_POST["stock"] : "";  ?>">
                                                        <div class="input-group-text">
                                                            <span class="text_white">Units</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="product_image" class="form-label col-sm-4 col-form-label">Product Image</label>
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
                                            <div class="row mb-3">
                                                <label for="long_description" class="form-label col-sm-4 col-form-label">Description</label>
                                                <div class="col-sm-8">
                                                    <!-- text area doesn't support value attr.; instead write inside container tag -->
                                                    <textarea class="form-control" name="long_description" id="long_description" cols="30" rows="4"><?php echo isset($_POST["long_description"]) ? $_POST["long_description"] : "";  ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Clear</button> -->
                                        <button type="submit" name="btn_add_product" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Add Product</button>
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