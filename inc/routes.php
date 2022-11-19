<?php
/*
Настройка роутов
*/
define("SWAGGER","swagger");
$CONTROLLER_ROUTE = array();
$CONTROLLER_ROUTE["auth"] = array(
    0 => "login",
    1 => "authorize",
    2 => "logout"
);
$CONTROLLER_ROUTE["feedback"] = array(
    0 => "updateFeedback",
    1 => "getFeedbackList",
    2 => "addFileToFeedback",
    3 => "addFeedback"
);
$CONTROLLER_ROUTE["file"] = array(
    0 => "upload",
    1 => "download"
);
$CONTROLLER_ROUTE["databaseinit"] = array(
    0 => "createTables"
);
define("ROUTES", $CONTROLLER_ROUTE);
