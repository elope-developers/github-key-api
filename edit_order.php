<?php
if (!isset($_GET["id"])) {
    exit("id not set");
}
?>
<html>

<head>
    <link rel="stylesheet" href="https://unpkg.com/sakura.css/css/sakura.css" type="text/css">
</head>

<body>

    <h1>Edit Order</h1>

    Vehicle: <span id="vehicle"></span><br />
    Vin: <span id="vin"></span><br />
    Key (dependant on vehicle make/model): <span id="key"></span><br /><br />

    Select Technician<br />
    <select id="technicians"></select>

    <br />
    <br />
    When editing a order, you may change the technician assigned to the order, but not the vehicle.<br />
    You can cancel the order and make a new order with the correct make/model vehicle.<br />
    The key and price are directly tied to the make and model of the vehicle.<br />
    Currently you can only purchase new keys for cars already in the database.<br />
    If you need to edit the vin of a car or create a new car, please contact HTL.<br />

    <button onclick="editOrder()">Edit Order</button>



    <script>
        // default values of order:
        var vinDefault = "";
        var techDefault = "";

        // Fetch order data
        fetch("/api/cars.php/orders?id=<?= $_GET['id'] ?>", {
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


                    if (data.orders.length > 0) {
                        order = data.orders[0];
                        console.log(order);

                        // Set up defaults select 
                        vinDefault = order["vin"];
                        techDefault = `<option value="${order["technician_id"]}">${order["technician"]}</option>`;

                        document.getElementById("vehicle").innerText = order["make"] + " " + order["model"];
                        document.getElementById("vin").innerText = vinDefault;
                        document.getElementById("key").innerText = order["name"] + ": $" + order["price"];

                        getAllTechnicians();

                    } else {
                        throw "Error: no orders found for id"
                    }

                } else {
                    throw "Error: " + data.message;
                }
            }).catch((error) => {
                alert("check console for error");
                console.log(error)
            });






        // Fetch all technicians
        function getAllTechnicians() {
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
                        var options = techDefault; //"<option value=\"select\">Select Technician</option>";
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
        }



        // new orders need to pass technician, vin
        function editOrder() {
            var vin = vinDefault;
            if (!(vin.length > 0)) {
                throw "Please enter a vin number";
            }
            var id = <?= $_GET["id"]; ?>;
            var technician_id = document.getElementById("technicians").value;

            fetch("/api/cars.php/orders/", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        "order": JSON.stringify({
                            "id": id,
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

                        alert("Order Modified");
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
            console.log("vehicle id: ", vehicle_id);
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
                            console.log(data.keys);
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