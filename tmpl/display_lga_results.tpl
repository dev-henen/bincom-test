<div class="container">
    <h1>Summed Results for LGA: @{lga_name}</h1>
    <table>
        <thead>
            <tr>
                <th>Party Abbreviation</th>
                <th>Total Score</th>
            </tr>
        </thead>
        <tbody>
            @forEach[total_results]
            <tr>
                <td>{{party_abbreviation}}</td>
                <td>{{total_score}}</td>
            </tr>
            @end[total_results]
        </tbody>
    </table>
</div>
