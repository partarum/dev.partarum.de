<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */

error_reporting(-1);
ini_set("display_errors", "1");

/*
 *  TODO: Den Login vom Holzwurm in Partarum integrieren!
 */

//require_once($_SERVER["DOCUMENT_ROOT"]."src/partarum".DIRECTORY_SEPARATOR."partarum.php");

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <link rel="stylesheet"  type="text/css" href="Partarum/css" media="all">
    <!--
    <link rel="stylesheet"  type="text/css" href="Workspace/assets/css/plainPHP.css" media="all">
    -->
    <link rel="stylesheet"  type="text/css" href="css" media="all">


    <!--
    <link rel="preload" crossorigin="use-credentials" href="PartarumWorkspace/Surface/Import" as="script">
    -->
    <!--
    <link rel="preload" crossorigin="use-credentials" href="Workspace/surface/templates/orderStatus" as="script">
    -->
</head>
<body>


    <header id="workspaceHeader"></header>
    <aside id="workspaceAside"></aside>
    <main id="workspace" class="box-row space-between">

</main>


<script type="module" crossorigin="use-credentials" src="dashboardJS"></script>

<!--
<script src="assets/js/headroom.min.js"></script>
<script src="assets/js/plainPHP/setHeadroom.js"></script>
-->
</body>
</html>