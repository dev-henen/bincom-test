<?php
// Fetch all states

$connection = $GLOBALS['connection'];

$sql = "SELECT DISTINCT state_id, state_name FROM states ORDER BY state_name ASC;";
$result = $connection->query($sql);

$rows = $result->fetch_all(MYSQLI_ASSOC);

$template = new template\Loader('polling_units');
$template->show_errors = true;
$template->show_warnings = true;

$template->foreach('states', $rows);

$template->render();
