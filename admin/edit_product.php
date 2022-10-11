<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

// if no product id is send, redirect to homepage
if (!(isset($_GET["id"]) || isset($_POST["product_id"]))) {
    header("location: products.php");
    exit;
}

// fetch id 
if (isset($_GET["id"])) {
    $product_id = $_GET["id"];
}

if (isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
}


// get product from db
require_once  "../shared/connection.php";
try {
    // find product
    $stmt = $conn->prepare(" SELECT * FROM tb_Products WHERE product_id = :product_id;");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $product = $stmt->fetch();

    // if not found any product with entered id
    if (!$product) {
        header("location: products.php");
        exit;
    }

    // find cat.s for select box
    $stmt = $conn->prepare(" SELECT cat_id, cat_name FROM tb_Categories ORDER BY cat_name ASC; ");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $categories = $stmt->fetchAll();
    if (count($categories) < 1) {
        $_SESSION["error"] = "Please enter categories before adding a product!";
        header("location: categories.php");
        exit;
    }
} catch (PDOException $e) {
    // $error = "Something went wrong!";
    $error = $e->getMessage();
    $conn = null;
}

if (isset($_POST["btn_edit_product"])) {
    try {
        $product_name = $_POST["product_name"];
        $sku = $_POST["sku"];
        $category_id = $_POST["category"];
        $price = $_POST["price"];
        $stock = $_POST["stock"];
        $product_image = (isset($_FILES["image_file"]) && $_FILES["image_file"]["error"] == 0) ? $_FILES["image_file"] : "";
        $long_description = isset($_POST["long_description"]) ? $_POST["long_description"] : null;
        $updated_at = date("Y-m-d H:i:s");
        $updated_by_admin = $_SESSION["admin_id"];

        if ($product_image == "") {
            $stmt = $conn->prepare("UPDATE tb_Products SET product_name=:product_name, sku=:sku, long_description=:long_description, price=:price, stock=:stock, category_id=:category_id, updated_at=:updated_at, updated_by_admin=:updated_by_admin WHERE product_id = :product_id;");
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':long_description', $long_description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':updated_at', $updated_at);
            $stmt->bindParam(':updated_by_admin', $updated_by_admin);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            $_SESSION["success"] = "Product UPDATED successfully!";
            header("location: products.php");
            exit;
        } else {
            require_once  "../shared/image_upload.php";
            $fileResult = UploadImage($product_image);

            if ($fileResult["status"] == "success") {
                // get old path of image to delete
                $image_path = $product['product_image'];

                $stmt = $conn->prepare("UPDATE tb_Products SET product_name=:product_name, sku=:sku, long_description=:long_description, product_image = :product_image, price=:price, stock=:stock, category_id=:category_id, updated_at=:updated_at, updated_by_admin=:updated_by_admin WHERE product_id = :product_id;");
                $stmt->bindParam(':product_image', $fileResult['uploadedFile']);
                $stmt->bindParam(':product_name', $product_name);
                $stmt->bindParam(':sku', $sku);
                $stmt->bindParam(':long_description', $long_description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':stock', $stock);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':updated_at', $updated_at);
                $stmt->bindParam(':updated_by_admin', $updated_by_admin);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->execute();

                // delete only if there is no error in executing query
                DeleteImage($image_path);

                $_SESSION["success"] = "Product UPDATED successfully!";
                header("location: products.php");
                exit;
            } else {
                throw new Exception($fileResult["msg"]);
            }
        }
    } catch (Exception $e) {
        /// file uploaded succesfully but error in name or sku
        if ($product_image != "") {
            if ($fileResult["status"] == "success") {
                DeleteImage($fileResult["uploadedFile"]);
            }
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
        if ($product_image != "") {
            if ($fileResult["status"] == "error") {
                $error .= $e->getMessage();
            }
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

$title = "Edit Product";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Edit Product</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                            <li class="breadcrumb-item active">Edit Product</li>
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
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="modal-header justify-content-start theme_bg_1">
                    <h5 class="modal-title text_white">Edit Product</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <form action="edit_product.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>">
                                    <div class="modal-body">
                                        <div class="container">
                                            <?php include "../shared/Admin/notification_success.php";  ?>
                                            <?php include "../shared/Admin/notification_error.php";  ?>
                                            <div class="row mb-3">
                                                <label for="product_name" class="form-label col-sm-4 col-form-label">Product Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name" value="<?php echo isset($_POST["product_name"]) ? $_POST["product_name"] : $product["product_name"];  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="sku" class="form-label col-sm-4 col-form-label">SKU Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU Code" value="<?php echo isset($_POST["sku"]) ? $_POST["sku"] : $product["sku"];  ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="category" class="form-label col-sm-4 col-form-label">Category</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    $cats = array();
                                                    foreach ($categories as $row) {
                                                        if (isset($_POST["category"])) {
                                                            if ($_POST["category"] == $row["cat_id"]) {
                                                                array_push($cats, '<option value="' . $row["cat_id"] . '" selected>' . $row["cat_name"] . '</option>isset');
                                                            } else {
                                                                array_push($cats, '<option value="' . $row["cat_id"] . '">' . $row["cat_name"] . '</option>blank');
                                                            }
                                                        } else if ($product["category_id"] == $row["cat_id"]) {
                                                            array_push($cats, '<option value="' . $row["cat_id"] . '" selected>' . $row["cat_name"] . '</option>product');
                                                            continue;
                                                        } else
                                                            array_push($cats, '<option value="' . $row["cat_id"] . '">' . $row["cat_name"] . '</option>blank');
                                                    }
                                                    ?>

                                                    <select id="category" name="category" class="form-select form-control">
                                                        <?php
                                                        foreach ($cats as $row) {
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
                                                        <input type="number" class="form-control" aria-label="PKR" id="price" name="price" placeholder="Price" value="<?php echo isset($_POST["price"]) ? $_POST["price"] : $product["price"];  ?>">
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
                                                        <input type="number" class="form-control" aria-label="PKR" id="stock" name="stock" placeholder="Stock" value="<?php echo isset($_POST["stock"]) ? $_POST["stock"] : $product["stock"];  ?>">
                                                        <div class="input-group-text">
                                                            <span class="text_white">Units</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="product_image" class="form-label col-sm-4 col-form-label">Product Image</label>
                                                <div class="col-sm-5">
                                                    <input type="file" class="form-control" id="image_file" name="image_file" onchange="ChangeShownImage(event)">
                                                    <p class="text-primary">Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB</p>
                                                </div>
                                                <div class="col-sm-3 text-center">
                                                    <img src="<?php echo $product["product_image"];  ?>" style="height: 120px" id="shownImage" />
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
                                                    <textarea class="form-control" name="long_description" id="long_description" cols="30" rows="4"><?php echo isset($_POST["long_description"]) ? $_POST["long_description"] : $product["long_description"];  ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <!-- <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Clear</button> -->
                                        <button type="submit" name="btn_edit_product" class="btn btn-primary mx-2"><i class="fas fa-arrow-right"></i> Update Product</button>
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