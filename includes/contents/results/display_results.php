<?php
// Connection to the database
$connection = $GLOBALS['connection'];

if(!isset($_GET['state_id']) || !isset($_GET['lga_id']) || !isset($_GET['polling_unit_id'])) {
    exit;
}

// Get the selected IDs from the query parameters
$state_id = intval($_GET['state_id']);
$lga_id = intval($_GET['lga_id']);
$polling_unit_id = intval($_GET['polling_unit_id']);

settype($state_id, 'integer');
settype($lga_id, 'integer');
settype($polling_unit_id, 'integer');

// Fetch the polling unit details
$pollingUnitQuery = sprintf("
    SELECT polling_unit.polling_unit_name, polling_unit.uniqueid AS polling_unit_id,
           announced_pu_results.party_abbreviation AS party_abbreviation, announced_pu_results.party_score
    FROM polling_unit
    JOIN announced_pu_results ON polling_unit.uniqueid = announced_pu_results.polling_unit_uniqueid
    WHERE polling_unit.uniqueid=%d;
", $polling_unit_id);

$pollingUnitResult = $connection->query($pollingUnitQuery);

// Fetch state, LGA, and polling unit names for display
$stateQuery = sprintf("SELECT state_name FROM states WHERE state_id=%d;", $state_id);
$lgaQuery = sprintf("SELECT lga_name FROM lga WHERE lga_id=%d;", $lga_id);
$stateResult = $connection->query($stateQuery);
$lgaResult = $connection->query($lgaQuery);

$state_name = $stateResult->fetch_assoc()['state_name'];
$lga_name = $lgaResult->fetch_assoc()['lga_name'];

$pollingUnit =  $pollingUnitResult->fetch_all(MYSQLI_ASSOC);

$template = new template\Loader('display_results');
$template->show_errors = true;
$template->show_warnings = true;

$template->set('state_name', $state_name);
$template->set('lga_name', $lga_name);
$template->set('polling_unit_id', $polling_unit_id);
$template->set('polling_unit_name', $pollingUnit[0]['polling_unit_name'] ?? '');
$template->if('polling_unit_not_null', !empty($pollingUnit));
$template->foreach('polling_unit', $pollingUnit);
$template->render();