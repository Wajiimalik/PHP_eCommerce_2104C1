<?php

// if not logged in, then can't access any page except login and register page
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location: login.php");
    exit;
}

require_once  "../shared/connection.php";
try {
    $stmt = $conn->prepare("SELECT cat_id,cat_name,cat_image FROM tb_Categories;");
    $stmt->execute();
    $result = $stmt->fetchAll();

    // print_r($result);
    // exit;
} catch (PDOException $e) {
    $error = $e->getMessage();
}


if (isset($_POST["btn_delete_category"])) {
    $cat_id = $_POST["id"];

    try {
        $stmt = $conn->prepare("SELECT cat_image FROM tb_Categories WHERE cat_id=:cat_id;");
        $stmt->bindParam(':cat_id', $cat_id);
        $stmt->execute();
        $cat_image = $stmt->fetch();
        

        $stmt = $conn->prepare("DELETE FROM tb_Categories WHERE cat_id=:cat_id;");
        $stmt->bindParam(':cat_id', $cat_id);
        $stmt->execute();

        require_once  "../shared/image_upload.php";
        DeleteImage($cat_image["cat_image"]);

        $_SESSION["success"] = "Category deleted successfully!";
        header("location: category.php");
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

$title = "All Category";
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
        <?php include "../shared/Admin/notification_success.php";  ?>
        <?php include "../shared/Admin/notification_error.php";  ?>
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
                    <div class="page_title_left d-flex align-items-center">
                        <h3 class="f_s_25 f_w_700 dark_text mr_30">All Categories</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </div>
                    <div class="page_title_right">
                        <a id="btn_add_new_product" href="add_category.php" class="btn_1">
                            <i class="fas fa-arrow-right"></i> Add New Category
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="modal-header justify-content-start theme_bg_1">
                    <h5 class="modal-title text_white">Catgegory List</h5>
                </div>
                <div class="white_card card_height_100 p-3">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="QA_table">
                                <table class="table" id="products_list">
                                    <!-- products_list -->
                                    <thead>
                                        <tr>
                                            <th scope="col">Category</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($result as $row) {
                                        ?>
                                            <tr>
                                                <td><img src="<?php echo $row["cat_image"]; ?>" width="50"></td>
                                                <th>
                                                   <a class="list_name" href="view_category.php?id=<?php echo $row["cat_id"]; ?>"><?php echo $row["cat_name"]; ?></a>
                                                </th>
                                                <td>
                                                    <a class="dt_icon" href="view_category.php?id=<?php echo $row["cat_id"]; ?>"><i class="ti-eye"></i></a>
                                                    <a class="dt_icon" href="edit_category.php?id=<?php echo $row["cat_id"]; ?>"><i class="ti-pencil"></i></a>
                                                    <a class="dt_icon" href="#" onclick="OpenDeleteModal(<?php echo $row['cat_id']; ?>)"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
                <h5 class="modal-title" id="view_delete_modalTitle">Delete Category</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are yo sure you want to delete this category?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                <form action="category.php" method="post">
                    <input type="hidden" name="id" id="delete_cat_id" value="">
                    <button type="submit" class="btn btn-primary" name="btn_delete_category">Delete</button>
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
    '<script src="../module_js_scripts/Admin/products.js"></script>',
];

$footScript = '
    function OpenDeleteModal(id)
    {
        $("#delete_cat_id").attr("value", id);
        $("#view_delete_modal").modal("show");
    }
  ';

include "../shared/Admin/foot_include.php";
?>