<html>

<head>
    <link rel="stylesheet" href="https://unpkg.com/sakura.css/css/sakura.css" type="text/css">
</head>

<body>

    <h1>Orders</h1>
    <a href="/new_order.php">Create a New Order</a><br />
    <br />
    <div>
        <button onclick="getOrders('technician')">Sort by Tech</button>
        <button onclick="getOrders('name')">Sort by Key Name</button>
        <button onclick="getOrders('make')">Sort by Vehicles</button>
    </div>

    <div id="orders">
    </div>

    <hr>
    <h1>Documention</h1>
    <p>The purpose of this web app is to allow an HTL Admin to create/read/update/delete orders over the Cars Restful PHP API</p>
    <p><b>This project builds using compser for heroku. Run composer install.</b><p>

    <h2>Cars Restful PHP API</h2>
    <p>Calls to the cars api are routed to
    <pre>https://kg-key-api.herokuapp.com/api/cars.php</pre>
    </p>
    <h3>Endpoints</h3>
    <br />
    <h4>orders</h4>
    <br />
    <pre>cars.php/orders<br />
    <code>
        PUT - Update Order
        { "orders":
        {
        "id":id, // ommit id to create new order
        "vin": vin,
        "technician_id": technician_id
        }
        }

        GET - Fetch Orders, Single Order or Orders (ordered by)
        {
        "id": id // passing id get a single order
        "order_by": name /*(key name is default if not passed)*/|technician|vehicle

        }
        Response: {"status":200,"orders":[{"key_id":1,"vehicle_id":4,"name":"K-Ford","price":4.3,"make":"Ford","model":"Mustang","vin":"111","technician":"Kellar,Susan","id":194,"technician_id":34}]}


        DELETE - Delete Order
        {
        "id":id
        }
    </code>
    </pre>
    <br />
    <br />
    <h4>keys</h4>
    <pre>cars.php/keys<br/>
    <code>
        GET - Fetch keys or single key
        {
        "id":id // not passing id will return all keys
        }
        Response: {"status":200,"keys":[{"id":4,"name":"K-Kia","descr":"Key for Kia Vehicles","price":1.33}]}
    </code>
    </pre>
    <br />
    <br />
    <h4>technicians</h4>
    <pre>cars.php/technicians<br/>
    <code>
        GET - Fetch technician or single technician
        {
        "id":id // not passing id will return all technicians
        }
    </code>
    </pre>
    <br />
    <h4>vehicles</h4>
    <br />
    <pre>cars.php/vehicles</pre>
    <br />
    <code>
        GET - Fetch vehicle or single vehicle
        {
        "id":id // not passing id will return all vehicles
        }
        Response: {"status":200,"technicians":[{"id":4,"firstname":"Jane","lastname":"Doe","truckID":1}]}
    </code>
    <br />

    <h4>key_vehicles</h4>
    <br />
    <pre>cars.php/key_vehicles<br />
    <code>
        GET - Fetch key_vehicles, single key_vehicles, keys or vehicles
        {
        "key_id": key_id // pass to get a vehicle (key ids are not unique to a signle vehicle. multiple vehicles will be returned)
        "vehicle_id": vehicle_id // pass to get a key (vehicle ids are unique to the key)
        "id": id // passing id get a single key_vehicle
        // pass nothing to get all key_vehicles

        }

        key_id Response: {"status":200, "vehicles":[{"id":24, "year":2016, "make":"Kia","model":"Soul", "vin":"P0P"},{"id":104, "year":2009, "make":"Kia","model":"Sorento", "vin":"CM3"},{"id":134, "year":2012, "make":"Kia","model":"Forte", "vin":"XP1"}]}
        vehicle_id Response: {"status":200,"keys":[{"id":1,"name":"K-Ford","descr":"Key for Ford Vehicles","price":4.3}]}
        id Response: {"status":200,"kvs":[{"id":54,"key_id":1,"vehicle_id":4}]}
    </code>
    </pre>
    <p><b>Note: </b>all responses will return with a status code (200|500) and data (array) | message (string) </p>
    <br />
    <br />
    <h2>Database Management</h2>
    <h3>Tables</h3>
    <p><b>Note:</b>Table create/populate mysql can be found in db_statements.</p>
    <br/>
    <h4>keys</h4>
    <p>This table stores keys. The id of the key is tied to a vehicle id in the key_vehicles tables</p>
    <pre>
    |	id	|	name	|	descr	                |	price	|
    |  	1	|	K-Ford	|	Key for Ford Vehicles	|	4.3	    |
    |	2	|	K-Honda |	Key for Honda Vehicles	|	5.01	|
    |	4	|	K-Kia	|	Key for Kia Vehicles	|	1.33	|
    |	14	|	K-BMW	|	Key for BMW Vehicles	|	10.45	|
    |	24	|	K-GMC	|	Key for GMC Vehicles	|	45.23	|
    </pre>
    <br/>
    <h4>technicians</h4>
    <p>This table stores technicians. The id of the technician tied to a vin number in the orders table</p>
    <pre>
    |	id	|	firstname	|	lastname	|	truckID	|
    |	4	|	Jane	    |	Doe	        |	1	    |
    |	14	|	Mike	    |	Johnson	    |	2	    |
    |	24	|	Tim	        |	Parker	    |	3	    |
    |	34	|	Susan	    |	Kellar	    |	4	    |
    |	44	|	Alex	    |	Smith	    |	5	    |
    |	54	|	Dante	    |	Cordova	    |	501	    |
    |	64	|	Brooks	    |	Homes	    |	502	    |
    |	74	|	Jimmy	    |	Butler	    |	503	    |
    |	84	|	Terry	    |	Bradshaw    |	504	    |
    |	94	|	The	        |	Pope	    |	505	    |
    |	104	|	Jimmy	    |	Johns	    |	506	    |
    |	114	|	Michael	    |	Jordan	    |	507	    |
    |	124	|	Jenn	    |	Zynga	    |	508	    |
    |	134	|	JR	        |	Marshall    |	509	    |         
    |	144	|	Kelsey	    |	May	        |	510	    |
    </pre>
    <br/>
    <h4>vehicles</h4>
    <p>This table stores vehicles. The vin of the vehicle is tied to a technician in the orders table. The vehicle_id is tied the key_id in the key_vehicles table</p>
    <pre>
    |	id	|	year	|	make	|	model	|	vin	|
    |	4	|	1969	|	Ford	|	Mustang	|	111	|
    |	14	|	2019	|	Honda	|	Civic	|	5F4	|
    |	24	|	2016	|	Kia	    |	Soul	|	P0P	|
    |	34	|	1999	|	BMW	    |	m5	    |	9F7	|
    |	44	|	2003	|	GMC	    |	Yukon	|	C44	|
    |	54	|	2004	|	GMC	    |	Denali	|	J90	|
    |	64	|	2005	|	BMW	    |	i8	    |	C29	|
    |	74	|	2006	|	Ford	|	Focus	|	C12	|
    |	84	|	2007	|	Honda	|	Pilot	|	PLQ	|
    |	94	|	2008	|	Honda	|	Accord	|	VBA	|
    |	104	|	2009	|	Kia	    |	Sorento	|	CM3	|
    |	114	|	2010	|	Ford	|	Bronco	|	C9F	|
    |	124	|	2011	|	BMW	    |	i3	    |	BH3	|
    |	134	|	2012	|	Kia	    |	Forte	|	XP1	|
    |	144	|	2013	|	Ford	|	Focus	|	BI5	|
    |	154	|	2014	|	GMC	    |	Sierra	|	71T	|
    </pre>
    <br/>
    <h4>key_vehicles</h4>
    <p>This table links keys to vehicles. A key can be linked to multiple vehicles but a vehicle can only be linked to one key.</p>
    <p>This table is also used to join with keys or vehicles to select the respective vehicle or key details</p>
    <pre>
    |	id	|	key_id	|	vehicle_id	|
    |	54	|	1	    |	4	        |
    |	64	|	1	    |	74	        |
    |	74	|	1	    |	114	        |
    |	84	|	1	    |	144	        |
    |	124	|	2	    |	14	        |
    |	134	|	2	    |	84	        |
    |	144	|	2	    |	94	        |
    |	154	|	4	    |	24	        |           
    |	164	|	4	    |	104	        |
    |	174	|	4	    |	134	        |
    |	214	|	14	    |	34	        |
    |	224	|	14	    |	64	        |
    |	234	|	14	    |	124	        |
    |	244	|	24	    |	44	        |
    |	254	|	24	    |	54	        |
    |	264	|	24	    |	154	        |
    </pre>
    <br/>
    <h4>orders</h4>
    <p>This table is a custom table, used to store the vin and technician for an order</p>
    <p>The api uses the vin and technician id to join the other tables to get the full details.</p>
    <pre>
    |   id  | technician_id | vin   | 
    |	104	|	4	        |	111	|
    |	114	|	14	        |	5F4	|
    |	194	|	34	        |	111	|
    |	204	|	4	        |	9F7	|
    |	214	|	64	        |	C44	|   
    </pre>
    <br/>
    <h3>Front End</h3>
    <p>Styling is a bootstrap from - https://unpkg.com/sakura.css/css/sakura.css (single line of code)</p>
    <p><i>index.php</i> - This file fetches all the orders and has a button to edit or delete the order based on the id.</p>
    <p><i>edit_order.php</i> - This file fetches the orders passed by id and allows the user to change the technician.</p>
    <p><i>new_order.php</i> - This file allows the user to create a new order with a vehicle and technician.</p>
    <br/>
    <h3>Back End</h3>
    <p><i>api/cars.php</i> - This file handles server_requests, url parsing, and class fetching</p>
    <p><i>api/db_connect.php</i> - This file is responsible for connecting to the database</p>
    <p><i>api/key_vehicles.php</i> - This file is a class file for key_vehicles that builds queries for the database</p>
    <p><i>api/keys.php</i> - This file is a class file for keys that builds queries for the database</p>
    <p><i>api/orders.php</i> - This file is a class file for orders that builds queries for the database</p>
    <p><i>api/technicians.php</i> - This file is a class file for technicians that builds queries for the database</p>
    <p><i>api/vehicles.php</i> - This file is a class file for vehicles that builds queries for the database</p>
    <hr>
    <h2>Things to add/improve</h2>
    <ul>
        <li>Add api call to create/update keys, vehicles, technicians</li>
        <li>Create Script to rebuild key_vehicles table based on keys and vehicles</li>
        <li>Implement more security and authorization tokens, headers, etc</li>
        <li>Implement custom libs to clean up code file management</li>
        <li>Investigate Laravel Further</li>
    </ul>

    <script>
        getOrders("technician");

        function getOrders(order_by) {

            document.getElementById("orders").innerHTML = "";

            fetch("/api/cars.php/orders?order_by=" + order_by, {
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
                        var orders = "<table><tr><th>Key Name</th><th>Key Price</th><th>Make</th><th>Model</th><th>Vin</th><th>Technician</th>";
                        data.orders.forEach(order => {
                            orders += `<tr><td>${order["name"]}</td><td>${order["price"]}</td><td>${order["make"]}</td><td>${order["model"]}</td><td>${order["vin"]}</td><td>${order["technician"]}</td><td><a href="edit_order.php?id=${order["id"]}">edit</a>     <button onClick="deleteOrder(${order["id"]})">delete</button></td></tr>`;
                        })

                        document.getElementById("orders").innerHTML = orders;
                    } else {
                        throw "Error: " + data.message;
                    }
                }).catch((error) => {
                    alert("check console for error");
                    console.log(error)
                });

        }

        function deleteOrder(id) {

            if (confirm("Delete this order?")) {

                fetch("/api/cars.php/orders", {
                        method: 'DELETE',
                        body: JSON.stringify({
                            "id": id
                        }),
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

                            alert("Order Deleted");
                            window.location = "/"
                        } else {
                            throw "Error: " + data.message;
                        }
                    }).catch((error) => {
                        alert("check console for error");
                        console.log(error)
                    });
            } else {
                alert("Deletion Canceled");
            }
        }
    </script>
</body>

</html>