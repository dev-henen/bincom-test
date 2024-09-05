<?php
// Database connection
$connection = $GLOBALS['connection'];

// Fetch states from the database
$statesQuery = "SELECT DISTINCT state_id, state_name FROM states ORDER BY state_name ASC;";
$statesResult = $connection->query($statesQuery);
$states = $statesResult->fetch_all(MYSQLI_ASSOC);

// Fetch all parties from the database
$partiesQuery = "SELECT partyid, partyname FROM party ORDER BY partyname ASC;";
$partiesResult = $connection->query($partiesQuery);
$parties = $partiesResult->fetch_all(MYSQLI_ASSOC);

// Use the template loader to render the page
$template = new template\Loader('add_polling_unit_results');
$template->show_errors = true;
$template->show_warnings = true;

$template->forEach('states', $states);
$template->forEach('parties', $parties);

$template->render();
