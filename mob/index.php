<?php
session_start();
/**
 * Created by JetBrains PhpStorm.
 * User: ebon
 * Date: 05/05/13
 * Time: 04:43
 * To change this template use File | Settings | File Templates.
 */

$currentPage = 'home.php';
if (!isset($_SESSION['uid'])) {
    $currentPage="login.php";
}

?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css">
    <script src="../jquery-1.9.1.min.js"></script>
</head>
<body>
<?php include $currentPage; ?>


</body>
</html>