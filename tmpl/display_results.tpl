<div class="container">
    <h1>Results for Polling Unit</h1>
    <p><strong>State:</strong> @{state_name}</p>
    <p><strong>LGA:</strong> @{lga_name}</p>
    <p><strong>Polling Unit:</strong> @{polling_unit_name}</p>
    <p><strong>Polling Unit ID:</strong> @{polling_unit_id}</p>

    <table>
        <thead>
            <tr>
                <th>Party Abbreviation</th>
                <th>Party Score</th>
            </tr>
        </thead>
        <tbody>
            @if[polling_unit_not_null](expr)
            @forEach[polling_unit]
            <tr>
                <td>{{party_abbreviation}}</td>
                <td>{{party_score}}</td>
            </tr>
            @end[polling_unit]
            @else[polling_unit_not_null]
            <tr>
                <td colspan="2">No results found</td>
            </tr>
            @end[polling_unit_not_null]
        </tbody>
    </table>
</div>
