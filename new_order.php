<html>

<head>
    <link rel="stylesheet" href="https://unpkg.com/sakura.css/css/sakura.css" type="text/css">
</head>

<?php

?>

<body>

    <h1>New Order</h1>

    Select Vehicle:<br />
    <select id="vehicles"></select><br />
    Vin: <span id="vin"></span><br />
    Key: <span id="key"></span><br /><br />

    Select Technician<br />
    <select id="technicians"></select>

    <br />
    <br />
    When editing a order, you may change the technician assigned to the order, but not the vehicle.<br />
    You can cancel the order and make a new order with the correct make/model vehicle.<br />
    The key and price are directly tied to the make and model of the vehicle.<br />
    Currently you can only purchase new keys for cars already in the database.<br />
    If you need to edit the vin of a car or create a new car, please contact HTL.<br />
    <button onclick="createNewOrder()">Create Order</button>



    <script>
        // Fetch all you fetch all vehicles
        var vinMap = new Map();

        fetch("/api/cars.php/vehicles/", {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then((res) => {
                if (res.ok) {
                    return res.json()
                } else {
                    throw "error in res: " + res.status
                };
            })
            .then((data) => {
                if (data.status == 200) {
                    // Add vehicles to select 
                    var options = "<option value=\"select\">Select Vehicle</option>";
                    data.vehicles.forEach(vehicle => {
                        options += `<option value="${vehicle["id"]}">${vehicle["make"]+" "+vehicle["model"]+" - "+vehicle["vin"]}</option>`;
                        vinMap.set(vehicle["id"], vehicle["vin"]);
                    })

                    document.getElementById("vehicles").innerHTML = options;
                    document.getElementById("vehicles").onchange = vehicleListener; // add onChangeListener
                } else {
                    throw "Error: " + data.message;
                }
            }).catch((error) => {
                alert("check console for error");
                console.log(error)
            });


        // Fetch all technicians
        fetch("/api/cars.php/technicians/", {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then((res) => {
                if (res.ok) {
                    return res.json()
                } else {
                    throw "error in res: " + res.status
                };
            })
            .then((data) => {
                if (data.status == 200) {
                    // Add technicians to select 
                    var options = "<option value=\"select\">Select Technician</option>";
                    data.technicians.forEach(technician => {
                        options += `<option value="${technician["id"]}">${technician["lastname"]+", "+technician["firstname"]}</option>`;
                    })

                    document.getElementById("technicians").innerHTML = options;
                } else {
                    throw "Error: " + data.message;
                }
            }).catch((error) => {
                alert("check console for error");
                console.log(error)
            });




        // new orders need to pass technician, vin
        function createNewOrder() {
            var vin = vinMap.get(parseInt(document.getElementById("vehicles").value));
            var technician_id = document.getElementById("technicians").value;

            fetch("/api/cars.php/orders/", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "order": JSON.stringify({
                            "vin": vin,
                            "technician_id": technician_id
                        })
                    })
                })
                .then((res) => {
                    if (res.ok) {
                        return res.json()
                    } else {
                        throw "error in res: " + res.status
                    };
                })
                .then((data) => {
                    if (data.status == 200) {

                        alert("New Order Created");
                        window.location = "/"

                    } else {
                        throw "Error: " + data.message;
                    }
                }).catch((error) => {
                    alert("check console for error");
                    console.log(error)
                });
        }

        // onchange listener for vehicle selection
        function vehicleListener() {
            var vehicle_id = this.value;
            if (vehicle_id == "Select Vehicle") {
                console.log("user did not choose vehicle");
            } else {
                // fetch key based on vehicle id
                fetch("/api/cars.php/key_vehicles?vehicle_id=" + vehicle_id, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then((res) => {
                        if (res.ok) {
                            return res.json()
                        } else {
                            throw "error in res: " + res.status
                        };
                    }).then((data) => {
                        if (data.status == 200) {
                            // Get Keys based on vehicle id
                            document.getElementById("vin").textContent = vinMap.get(parseInt(vehicle_id));
                            document.getElementById("key").textContent = data.keys[0]["name"] + ": $" + data.keys[0]["price"];
                        } else {
                            throw "Error: " + data.message;
                        }
                    }).catch((error) => {
                        alert("check console for error");
                        console.log(error)
                    });
            }
        }
    </script>
</body>

</html>