function fetchLGA(state_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/actions/fetch/lga?state_id=" + state_id, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('lga_id').innerHTML = xhr.responseText;
            document.getElementById('polling_unit_id').innerHTML = '<option value="">Select Polling Unit</option>';
        }
    };
    xhr.send();
}

function fetchPollingUnits(lga_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/actions/fetch/polling_units?lga_id=" + lga_id, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('polling_unit_id').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}


 // Function to handle form submission using AJAX
 function submitForm() {
    var form = document.getElementById('polling-unit-form');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_polling_unit_results.php', true);
    xhr.onload = function () {
        var responseMessage = document.getElementById('response-message');
        if (xhr.status === 200) {
            responseMessage.innerHTML = '<p>Results saved successfully!</p>';
            form.reset(); // Reset the form
        } else {
            responseMessage.innerHTML = '<p>Error saving results: ' + xhr.statusText + '</p>';
        }
    };
    xhr.onerror = function () {
        document.getElementById('response-message').innerHTML = '<p>Request failed</p>';
    };

    xhr.send(formData); // Send the form data
}


function validateForm() {
    var state = document.getElementById('state_id').value;
    var lga = document.getElementById('lga_id').value;
    var pollingUnit = document.getElementById('polling_unit_id').value;
    var valid = true;
    var errorMessage = '';

    if (!state) {
        errorMessage += 'Please select a state.\n';
        valid = false;
    }
    if (!lga) {
        errorMessage += 'Please select an LGA.\n';
        valid = false;
    }
    if (!pollingUnit) {
        errorMessage += 'Please select a Polling Unit.\n';
        valid = false;
    }

    // Validate party scores
    document.querySelectorAll('.party-input input[type="number"]').forEach(function(input) {
        if (input.value === '' || isNaN(input.value) || parseInt(input.value) < 0) {
            errorMessage += `Please provide a valid score for ${input.name}.\n`;
            valid = false;
        }
    });

    if (!valid) {
        alert(errorMessage);
    }

    return valid;
}

function submitForm2() {
    if (!validateForm()) return;  // Ensure form is valid before submitting

    var form = document.getElementById('polling-unit-form');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/actions/results/polling_units/add/save", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if(xhr.status == 200) {
                form.reset();
            }
            var responseMessage = document.getElementById('response-message');
            responseMessage.style.color = (xhr.status == 200) ? 'green' : 'red';
            responseMessage.innerText = xhr.responseText;
        }
    };
    xhr.send(formData);

    return false;
}
