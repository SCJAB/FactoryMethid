<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_factory";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

interface Person {
    public function displayInfo();
}

class Supplier implements Person {
    public $id;
    public $name;
    public $contact;
    public $address;

    public function __construct($id, $name, $contact, $address) {
        $this->id = $id;
        $this->name = $name;
        $this->contact = $contact;
        $this->address = $address;
    }

    public function displayInfo() {
        echo "<tr>
                <td>{$this->id}</td>
                <td>{$this->name}</td>
                <td>{$this->contact}</td>
                <td>{$this->address}</td>
              </tr>";
    }
}

class Customer implements Person {
    public $id;
    public $name;
    public $contact;
    public $address;

    public function __construct($id, $name, $contact, $address) {
        $this->id = $id;
        $this->name = $name;
        $this->contact = $contact;
        $this->address = $address;
    }

    public function displayInfo() {
        echo "<tr>
                <td>{$this->id}</td>
                <td>{$this->name}</td>
                <td>{$this->contact}</td>
                <td>{$this->address}</td>
              </tr>";
    }
}

function createPerson($type, $id, $name, $contact, $address) {
    if ($type === 'supplier') {
        return new Supplier($id, $name, $contact, $address);
    } elseif ($type === 'customer') {
        return new Customer($id, $name, $contact, $address);
    }

    return null;
}

$people = array();

$sql = "SELECT * FROM supplier";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $person = createPerson('supplier', $row["id"], $row["name"], $row["contact"], $row["address"]);
        $people[] = $person;
    }
}

$sql = "SELECT * FROM customer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $person = createPerson('customer', $row["id"], $row["name"], $row["contact"], $row["address"]);
        $people[] = $person;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Factory</title>
</head>
<body>
    <div class="button-container">
        <button id="openSupplierModal">Supplier</button>
        <button id="openCustomerModal">Customer</button>
    </div>

    <div id="supplierModal" class="modal">
        <div class="modal-content">
            <h2>Supplier Information</h2>
            <table class="supplier-table">
                <tr>
                    <td>Supplier ID:</td>
                    <td>Name:</td>
                    <td>Contact:</td>
                    <td>Address:</td>
                </tr>
                <?php foreach ($people as $person): ?>
                <?php if ($person instanceof Supplier): ?>
                    <tr>
                        <td><?php echo $person->id; ?></td>
                        <td><?php echo $person->name; ?></td>
                        <td><?php echo $person->contact; ?></td>
                        <td><?php echo $person->address; ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </table>
            <span class="close" id="closeSupplierModal">Close</span>
        </div>
    </div>

    <div id="customerModal" class="modal">
        <div class="modal-content">
            <h2>Customer Information</h2>
            <table class="customer-table">
                <tr>
                    <td>Customer ID:</td>
                    <td>Name:</td>
                    <td>Contact:</td>
                    <td>Address:</td>
                </tr>
                <?php foreach ($people as $person): ?>
                <?php if ($person instanceof Customer): ?>
                    <tr>
                        <td><?php echo $person->id; ?></td>
                        <td><?php echo $person->name; ?></td>
                        <td><?php echo $person->contact; ?></td>
                        <td><?php echo $person->address; ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </table>
            <span class="close" id="closeCustomerModal">Close</span>
        </div>
    </div>

    <script>
        var supplierModal = document.getElementById("supplierModal");
        var customerModal = document.getElementById("customerModal");
        var buttonContainer = document.querySelector(".button-container");

        function toggleModal(modal) {
            modal.style.display = modal.style.display === "block" ? "none" : "block";
        }

        buttonContainer.addEventListener("click", function (event) {
            if (event.target.id === "openSupplierModal") {
                toggleModal(supplierModal);
            } else if (event.target.id === "openCustomerModal") {
                toggleModal(customerModal);
            }
        });

        supplierModal.querySelector(".close").addEventListener("click", function () {
            toggleModal(supplierModal);
        });

        customerModal.querySelector(".close").addEventListener("click", function () {
            toggleModal(customerModal);
        });
    </script>
</body>
</html>