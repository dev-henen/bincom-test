<div class="container">
    <h1>Check Polling Unit Results</h1>
    <form method="GET" action="/results/display">
        <label for="state_id">Select State:</label>
        <select id="state_id" name="state_id" onchange="fetchLGA(this.value)" required>
            <option value="">Select State</option>
            @forEach[states]
            <option value="{{state_id}}"> {{state_name}} </option>
            @end[states]
        </select><br><br>

        <label for="lga_id">Select LGA:</label>
        <select id="lga_id" name="lga_id" onchange="fetchPollingUnits(this.value)" required>
            <option value="">Select LGA</option>
        </select><br><br>

        <label for="polling_unit_id">Select Polling Unit:</label>
        <select id="polling_unit_id" name="polling_unit_id" required>
            <option value="">Select Polling Unit</option>
        </select><br><br>

        <button type="submit">Get Polling Unit Results</button>
    </form>
</div>