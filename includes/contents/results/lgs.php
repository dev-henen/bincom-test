<?php
// Connection to the database
$connection = $GLOBALS['connection'];

// Fetch all states
$statesQuery = "SELECT DISTINCT state_id, state_name FROM states ORDER BY state_name ASC;";
$statesResult = $connection->query($statesQuery);
$states = $statesResult->fetch_all(MYSQLI_ASSOC);

$template = new template\Loader('display_lga_results_form');
$template->show_errors = true;
$template->show_warnings = true;

// Setting template variables
$template->foreach('states', $states);

// Render the template
$template->render();
