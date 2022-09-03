<?php

if (isset($error)) {
    echo '
    <div id="error" class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error: </strong> <span id="error_msg">' . $error . '</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ';
    $error = null;
}
if (isset($_SESSION["error"])) {
    echo '
    <div id="error" class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error: </strong> <span id="error_msg">' . $_SESSION["error"] . '</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ';
    $_SESSION["error"] = null;
}
?>