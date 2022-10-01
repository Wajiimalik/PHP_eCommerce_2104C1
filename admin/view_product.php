<?php

$product_id = $_GET["id"];

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
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">SKU Code</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Product Image</th>
                                        <td><img src="" alt="" height="200"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Long Description</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Price</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Stock</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Category Name</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Inserted at</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Updated at</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Updated by Admin</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary mb-3">Edit Product</button>

                            <button type="button" class="btn btn-secondary mb-3">Delete Product</button>
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
];

$footScript = "
  ";

include "../shared/Admin/foot_include.php";
?>