<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

// if not id passed, go to list page
if (!(isset($_GET["id"]))) {
    header("location: products.php");
    exit;
}
$product_id = $_GET["id"];


require_once  "../shared/connection.php";


try {
    $stmt = $conn->prepare("SELECT P.product_id, P.product_name, P.sku, P.product_image, P.price, P.stock, P.inserted_at, P.updated_at, P.long_description, C.cat_name , A.name
        FROM tb_Products P INNER JOIN tb_Categories C ON P.category_id = C.cat_id 
        INNER JOIN tb_Admins A ON A.admin_id = P.updated_by_admin
            WHERE p.product_id = :product_id;");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $result = $stmt->fetch();

    // print_r($result);
    // exit;
} catch (PDOException $e) {
    $error = $e->getMessage();
}


// delete product
if (isset($_GET["btn_delete_product"])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tb_Products WHERE product_id=:product_id;");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        require_once  "../shared/image_upload.php";
        DeleteImage($result["product_image"]);

        $_SESSION["success"] = "Product deleted successfully!";
        header("location: products.php");
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}



$title = "Product Details";
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
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">Product Details</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Product Details</li>
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
                    <h5 class="modal-title text_white">Product Details</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Product Name</th>
                                        <td><?php echo $result["product_name"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">SKU Code</th>
                                        <td><?php echo $result["sku"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Product Image</th>
                                        <td><img src="<?php echo $result["product_image"]; ?>" alt="" height="200"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Long Description</th>
                                        <td><?php echo $result["long_description"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Price</th>
                                        <td><?php echo $result["price"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Stock</th>
                                        <td><?php echo $result["stock"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Category Name</th>
                                        <td><?php echo $result["cat_name"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Inserted at</th>
                                        <td><?php echo $result["inserted_at"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Updated at</th>
                                        <td><?php echo $result["updated_at"]; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Updated by Admin</th>
                                        <td><?php echo $result["name"]; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="edit_product.php?id=<?php echo $result["product_id"]; ?>" class="btn btn-secondary mb-3">Edit Product</a>

                            <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#view_delete_modal">Delete Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="view_delete_modal" tabindex="-1" role="dialog" aria-labelledby="view_delete_modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_delete_modalTitle">Delete Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are yo sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                <form action="view_product.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $result["product_id"]; ?>">
                    <button type="submit" class="btn btn-primary" name="btn_delete_product">Delete</button>
                </form>
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
];

$footScript = "
  ";

include "../shared/Admin/foot_include.php";
?>