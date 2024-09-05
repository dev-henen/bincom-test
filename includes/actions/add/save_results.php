<?php
// Database connection
$connection = $GLOBALS['connection'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $polling_unit_id = intval($_POST['polling_unit_uniqueid']);
    $party_scores = $_POST['party_abbreviation'];

    // Validate Polling Unit ID
    if (!$polling_unit_id || $polling_unit_id <= 0) {
        http_response_code(400); 
        echo 'Polling Unit ID is required and must be selected.';
        exit;
    }

    // Validate Party Scores
    foreach ($party_scores as $party_abbreviation => $party_score) {
        if (!preg_match('/^[A-Z]{0,9}$/', $party_abbreviation)) {
            http_response_code(400);
            echo "Invalid party abbreviation: $party_abbreviation. Abbreviations must be 0 to 9 uppercase letters.";
            exit;
        }

        if (!is_numeric($party_score) || intval($party_score) < 0) {
            http_response_code(400);
            echo "Invalid score for party: $party_abbreviation. Scores must be non-negative integers.";
            exit;
        }
    }

    // Insert the results into the database
    $stmt = $connection->prepare("INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score) VALUES (?, ?, ?)");
    $stmt->bind_param('isi', $polling_unit_id, $party_abbreviation, $party_score);

    foreach ($party_scores as $party_abbreviation => $party_score) {
        $stmt->execute();
    }

    // Close the statement and connection
    $stmt->close();
    $connection->close();

    // Respond with success message
    http_response_code(200); 
    echo 'Results saved successfully!';
}
