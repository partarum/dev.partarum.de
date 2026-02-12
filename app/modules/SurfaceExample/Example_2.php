 <?php

use app\src\Befragung\Surface;

$app = Surface::TransitionStage->init();

$db = $app->db;

$dbDate = $db->get("survey_dashboard", "survey_start", ["topic" => "KVH"]);

$dateArray = Surface::getDate($dbDate);

 if($dateArray === null) {

     header("Location: https://{$_SERVER['HTTP_HOST']}");
     exit();
 }

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Example-Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <link rel="shortcut icon" href="/public/assets/media/favicon.ico">

    <link rel="stylesheet" type="text/css" href="/public/assets/css/KVH.css" media="all">

    <link rel="stylesheet" href="/public/assets/fonts/fontawesome/css/all.min.css">

</head>
<body class="pfm ie-body">
<header id="pfmExecutionHeader" class="pfm-execution-header ie-header">

</header>
<main id="pfmExecutionStage" class="ie-main pre-pfm-execution-stage">

</main>
<footer id="pfmExecutionFooter" class="pfm-execution-footer ie-footer">

</footer>
</body>
</html>
