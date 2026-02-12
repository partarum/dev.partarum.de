<?php
error_reporting(-1);
ini_set("display_errors", "1");

use PartarumSurface\Surface;
use Medoo\Medoo;


$app = Surface::FinalStage->init();

$db = $app->db;

$stringOfCase = $app->caseName;

$getFormListFields = json_decode($db->get("survey_form_list", "survey_fields", ["topic" => $stringOfCase]), true);

$getFormListAreas = json_decode($db->get("survey_form_list", "survey_areas", ["topic" => $stringOfCase]), true);

$getFormListSections = json_decode($db->get("survey_form_list", "survey_sections", ["topic" => $stringOfCase]), true);

$getFormListContent = json_decode($db->get("survey_form_list", "survey_content", ["topic" => $stringOfCase]), true);

$setupCase = Surface::getCase(Surface::SetupStage, $stringOfCase);

$partnerString = $setupCase->getPartnerString();

$dbDate = $db->get("survey_dashboard", "survey_start", ["topic" => $stringOfCase]);

$dateArray = Surface::getDate($dbDate);

if($dateArray === null) {

    header("Location: https://{$_SERVER['HTTP_HOST']}");
    exit();
}

/*
 *  Aufarbeitung POST - Daten
 */

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
<body id="pfmOnboarding" class="pfm-onboarding ie-body">
<header class="pfm-onboarding-header ie-header">

</header>
<main id="pfmCommission" class="pfm-commission ie-main">
    <article>
        <h2>Ihre Eingaben wurde erfolgreich gespeichert. Vielen Dank für Ihre Unterstützung!</h2>
        <?php
        /*
            echo "<pre>";
            (is_countable($_POST)) && var_dump(count($_POST));
            (is_countable($getFormListAreas)) && var_dump(count($getFormListAreas));
            (is_countable($getFormListSections)) && var_dump(count($getFormListSections));
            (is_countable($getFormListFields)) && var_dump(count($getFormListFields));
            var_dump($_POST);
            var_dump($getFormListAreas);
            var_dump($getFormListSections);
            var_dump($getFormListFields);
        */

            $gottenFields = array_keys($_POST);

        function searchKeyRecursive($array, $key) {
            $iterator = new RecursiveArrayIterator($array);
            $recursiveIterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

            foreach ($recursiveIterator as $k => $value) {
                if ($k === $key) {
                    return $value;
                }
            }

            return null;
        }

        $valueArray = [];
        $valueCounterArray = [];

        $mergedPost = [];

        foreach($getFormListFields as $field) {

            $mergedPost[$field] = $_POST[$field] ?? 0;
        }

        $excelCharacterArray = [];

        foreach($mergedPost as $postKey => $postValue) {

            $fieldArray = searchKeyRecursive($getFormListContent, $postKey);

            $valueArray[$postKey] = $fieldArray;
            $valueCounterArray[$postKey] = count($fieldArray);

            $start = ord("A");

            $letterArray = [];

            for($i = 0; $i < $valueCounterArray[$postKey]; $i++) {

                $valueOfField = $fieldArray[$i]["value"];

                $letterArray[($valueOfField !== "") ? $valueOfField : $i + 1] = chr($start + $i);
            }

            $valueIsSet = 0;

            foreach($fieldArray as $valueKey => $valueObject) {


                $fieldValue = $valueObject["value"];

                if($fieldValue === "") {

                    $excelCharacterArray[] = ($postValue === "") ? "X" : $postValue;

                    $valueIsSet++;

                } else {

                    if($fieldValue === $postValue) {

                        $excelCharacterArray[] = $letterArray[$fieldValue];

                        $valueIsSet++;

                    }
                }
            }

            if($valueIsSet === 0){
                $excelCharacterArray[] = 0;
            }
        }

        $db->insert("survey", [
            "topic" => $stringOfCase,
            "date_stamp" => Medoo::raw("FROM_UNIXTIME(UNIX_TIMESTAMP())"),
            "survey_content" => json_encode($_POST),
            "survey_excel_character_content" => json_encode($excelCharacterArray)
        ]);

        ?>
    </article>
</main>
<footer id="footer" class="pfm-onboarding-footer ie-footer">
</footer>
</body>
</html>