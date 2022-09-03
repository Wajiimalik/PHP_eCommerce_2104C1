<?php

if (isset($success)) {
    echo '
    <div id="success" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success: </strong> <span id="success_msg">' . $success . '</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ';
    $success = null;
}
if (isset($_SESSION["success"])) {
    echo '
    <div id="success" class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success: </strong> <span id="success_msg">' . $_SESSION["success"] . '</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ';
    $_SESSION["success"] = null;
}

?>