<?php
$connection = $GLOBALS['connection'];

if(!isset($_GET['lga_id'])) {
    exit;
}

$lga_id = intval($_GET['lga_id']);

// Fetch the LGA name for display
$lgaQuery = sprintf("SELECT lga_name FROM lga WHERE lga_id=%d;", $lga_id);
$lgaResult = $connection->query($lgaQuery);
$lga_name = $lgaResult->fetch_assoc()['lga_name'];

// Fetch the summed total results for all polling units under the selected LGA
$totalResultsQuery = sprintf("
    SELECT announced_pu_results.party_abbreviation, SUM(announced_pu_results.party_score) AS total_score
    FROM announced_pu_results
    JOIN polling_unit ON announced_pu_results.polling_unit_uniqueid = polling_unit.uniqueid
    WHERE polling_unit.lga_id=%d
    GROUP BY announced_pu_results.party_abbreviation;
", $lga_id);

$totalResults = $connection->query($totalResultsQuery)->fetch_all(MYSQLI_ASSOC);

$template = new template\Loader('display_lga_results');
$template->show_errors = true;
$template->show_warnings = true;

// Set template variables
$template->set('lga_name', $lga_name);
$template->foreach('total_results', $totalResults);

// Render the template
$template->render();
