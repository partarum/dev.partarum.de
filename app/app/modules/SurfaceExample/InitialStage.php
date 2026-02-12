<?php
error_reporting(-1);
ini_set("display_errors", "1");

use PartarumSurface\Surface;

$app = Surface::InitialStage->init([app\core\SurfaceExample\SurfaceInitialStage::class]);

$db = $app->db;

$setupCase = Surface::getCase(Surface::SetupStage, $app->caseName);

$partnerString = $setupCase->getPartnerString();

$dbDate = $db->get("survey_dashboard", "survey_start", ["topic" => $app->caseName]);

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
    <title>Dr. Franz-Josef Lückge Consulting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <link rel="shortcut icon" href="/public/assets/media/favicon.ico">

    <link rel="stylesheet" type="text/css" href="/public/assets/css/Prozess.css" media="all">

    <link rel="stylesheet" href="/public/assets/fonts/fontawesome/css/all.min.css">

</head>
<body id="pfmOnboarding" class="pfm-onboarding ie-body" style="-ms-grid-rows: 10vh 50vh 40vh;">
<header class="pfm-onboarding-header ie-header">

</header>
<main id="pfmCommission" class="pfm-commission ie-main">

</main>
<footer id="footer" class="pfm-onboarding-footer ie-footer">

</footer>
</body>
</html>
