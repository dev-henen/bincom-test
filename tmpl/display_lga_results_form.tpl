<div class="container">
    <h1>Check Summed Results for LGA</h1>
    <form method="GET" action="/results/lga/display">
        <label for="state_id">Select State:</label>
        <select id="state_id" name="state_id" onchange="fetchLGA(this.value)" required>
            <option value="">Select State</option>
            @forEach[states]
            <option value="{{state_id}}">{{state_name}}</option>
            @end[states]
        </select><br><br>

        <label for="lga_id">Select LGA:</label>
        <select id="lga_id" name="lga_id" required>
            <option value="">Select LGA</option>
            <!-- LGAs will be loaded dynamically -->
        </select><br><br>

        <button type="submit">Get Summed Results</button>
    </form>
</div>
