<?php
require 'db.php';
session_start();

/* INSERT DATA ONLY IF FORM IS SUBMITTED */
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $category = $_POST['category'];
    $total_qty = $_POST['total_qty'];
    $available = $_POST['available'];
    $status = $_POST['status'];

    mysqli_query($conn,
    "INSERT INTO inventory(name,category,total_qty,available,status)
    VALUES('$name','$category','$total_qty','$available','$status')");

    

    header("Location: inventory.php");
    exit();
}

/* FETCH DATA */
$result = mysqli_query($conn, "SELECT * FROM inventory ORDER BY category, name");

?>
<style>
  body{
    background:#f1f5f9;
    font-family:Segoe UI, sans-serif;
}

/* HEADER */
.header{
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:18px;
    background:white;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    margin-bottom:20px;
}

.header h1{
    margin:0;
}

.header p{
    margin-top:6px;
    color:#64748b;
}

/* INVENTORY LAYOUT */
.inventory-layout{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
    padding:20px;
}

/* PANELS */
.inventory-table,
.inventory-form{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:14px;
    padding:18px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

/* FORM INPUTS */
form input,
form select{
    width:100%;
    padding:10px 12px;
    border:1px solid #e2e8f0;
    border-radius:8px;
    font-size:14px;
    outline:none;
}

form input:focus,
form select:focus{
    border-color:#3b82f6;
    box-shadow:0 0 0 3px rgba(59,130,246,0.15);
}

/* BUTTON */
form button{
    padding:10px 18px;
    border:none;
    background:#3b82f6;
    color:white;
    border-radius:8px;
    cursor:pointer;
}

form button:hover{
    background:#2563eb;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

table th{
    background:#f8fafc;
    text-align:left;
    padding:12px;
    border-bottom:1px solid #e2e8f0;
}

table td{
    padding:12px;
    border-bottom:1px solid #e2e8f0;
}

table tr:hover{
    background:#f1f5f9;
}

/* RESPONSIVE */
@media(max-width:900px){
    .inventory-layout{
        grid-template-columns:1fr;
    }
}
</style>
<div class="header">
    <header>
        <h1>Tech support dashboard</h1>
        <p class="h1 p">Monitor and assign support tickets</p>
        <a href="dashboard.php" 
           style="padding:10px 20px; background:#3b82f6; color:white; border-radius:8px; text-decoration:none; font-weight:500; <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'box-shadow:0 0 0 3px rgba(59,130,246,0.3);' : ''; ?>">
            Ticket Management
        </a>
        
        <a href="inventory.php" 
           style="padding:10px 20px; background:#6b7280; color:white; border-radius:8px; text-decoration:none; font-weight:500; <?php echo basename($_SERVER['PHP_SELF']) === 'inventory.php' ? 'box-shadow:0 0 0 3px rgba(107,114,128,0.4);' : ''; ?>">
            Inventory Management
        </a>
    </header>
</div>

    <h1>Inventory</h1>

  <div class="inventory-layout">

    <!-- LEFT SIDE (TABLE) -->
    <div class="inventory-table">

        <h2>Inventory Items</h2>

        <?php if(mysqli_num_rows($result) > 0): ?>

        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Total Qty</th>
                <th>Available</th>
                <th>Status</th>
            </tr>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['total_qty']; ?></td>
                <td><?php echo $row['available']; ?></td>
                <td><?php echo $row['status']; ?></td>
                    <td>
                        <form method="POST" action="update_item.php" style="display:flex; gap:6px; align-items:center;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="In Stock" >In Stock</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                            <input type="number" name="total_qty" placeholder="Total Quantity" required>
                            <br><br>
                            <input type="number" name="available" placeholder="Available" required>
                            <br><br>

                            <button class="update-btn" type="submit">Update</button>
                        </form>
                        <form method="POST" action="delete_item.php" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button class="delete-btn" type="submit">Delete</button>
                        </form>
                    </td>
        
            </tr>
             
        <?php } ?>

        </table>

        <?php else: ?>
        <p>No items found.</p>
        <?php endif; ?>

    </div>


    <!-- RIGHT SIDE (FORM) -->
    <div class="inventory-form">

        <h3>Add Item</h3>

        <form method="POST">

            <input type="text" name="name" placeholder="Item Name" required>
            <br><br>

            <input type="text" name="category" placeholder="Category" required>
            <br><br>

            <input type="number" name="total_qty" placeholder="Total Quantity" required>
            <br><br>

            <input type="number" name="available" placeholder="Available" required>
            <br><br>

            <select name="status" required>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>

            <br><br>
            <button type="submit">Add Item</button>

        </form>

    </div>

</div>