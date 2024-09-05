<div class="container">
    <h1>Add Results for a New Polling Unit</h1>

    <!-- Dropdown for State -->
    <label for="state_id">State:</label>
    <select id="state_id" name="state_id" onchange="fetchLGA(this.value)" required>
        <option value="">Select State</option>
        @forEach[states]
        <option value="{{state_id}}">{{state_name}}</option>
        @end[states]
    </select><br><br>

    <!-- Dropdown for LGA -->
    <label for="lga_id">LGA:</label>
    <select id="lga_id" name="lga_id" onchange="fetchPollingUnits(this.value)" required>
        <option value="">Select LGA</option>
    </select><br><br>

    <!-- Form for Party Results -->
    <form id="polling-unit-form" onsubmit="return submitForm2();">
        <!-- Dropdown for Polling Unit -->
        <label for="polling_unit_id">Polling Unit:</label>
        <select id="polling_unit_id" name="polling_unit_uniqueid" required>
            <option value="">Select Polling Unit</option>
        </select><br><br>

        <!-- Dynamically generated fields for parties -->
        @forEach[parties]
        <div class="party-input">
            <label for="party_abbreviation_{{partyid}}">{{partyid}} Score:</label>
            <input type="number" id="party_abbreviation_{{partyid}}" name="party_abbreviation[{{partyid}}]" min="0" required>
        </div>
        @end[parties]

        <!-- Submit button -->
        <button type="submit" id="submit-button">Save Results</button>
    </form>
    <div id="response-message"></div>
</div>
