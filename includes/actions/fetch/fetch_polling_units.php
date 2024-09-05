<?php
// Connection to the database
$connection = $GLOBALS['connection'];

$lga_id = intval($_GET['lga_id']);
settype($lga_id, 'integer');

$query = sprintf("SELECT DISTINCT uniqueid, polling_unit_name FROM polling_unit WHERE lga_id=%d ORDER BY polling_unit_name ASC;", $lga_id);
$result = $connection->query($query);

echo '<option value="">Select Polling Unit</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="'.htmlspecialchars($row['uniqueid']).'">'.htmlspecialchars($row['polling_unit_name']).'</option>';
}
