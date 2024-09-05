<?php
// Connection to the database
$connection = $GLOBALS['connection'];

$state_id = intval($_GET['state_id']);
settype($state_id, 'integer');

$query = sprintf("SELECT DISTINCT lga_id, lga_name FROM lga WHERE state_id=%d ORDER BY lga_name ASC;", $state_id);
$result = $connection->query($query);

echo '<option value="">Select LGA</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="'.htmlspecialchars($row['lga_id']).'">'.htmlspecialchars($row['lga_name']).'</option>';
}
