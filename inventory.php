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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet"/>
<style>
    :root {
        --bg: #f0ede8;
        --surface: #faf9f7;
        --border: #d6d0c8;
        --border-focus: #1a1a1a;
        --text: #1a1a1a;
        --text-muted: #888075;
        --accent: #2c45f5;
        --accent-hover: #1932d4;
        --danger: #dc2626;
        --danger-hover: #b91c1c;
        --radius: 10px;
        --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.05);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--bg);
        font-family: 'Syne', sans-serif;
        color: var(--text);
        padding: 24px;
    }

    /* ── HEADER ── */
    .header {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 24px;
        background: var(--surface);
        box-shadow: var(--shadow);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        animation: fadeUp 0.35s ease both;
    }

    .header h1 {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .header p {
        margin-top: 4px;
        color: var(--text-muted);
        font-size: 13px;
    }

    .header-nav {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .header-nav a {
        padding: 9px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.03em;
        transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
    }

    .header-nav a.nav-active {
        background: var(--accent);
        color: white;
        box-shadow: 0 2px 8px rgba(44,69,245,0.22);
    }

    .header-nav a.nav-active:hover {
        background: var(--accent-hover);
        box-shadow: 0 4px 14px rgba(44,69,245,0.3);
        transform: translateY(-1px);
    }

    .header-nav a.nav-inactive {
        background: #e8e4de;
        color: var(--text-muted);
    }

    .header-nav a.nav-inactive:hover {
        background: #dedad3;
        color: var(--text);
        transform: translateY(-1px);
    }

    /* ── SECTION LABEL ── */
    .section-eyebrow {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 12px;
        padding: 0 2px;
    }

    /* ── LAYOUT ── */
    .inventory-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        animation: fadeUp 0.4s ease both;
        animation-delay: 0.1s;
    }

    /* ── PANELS ── */
    .inventory-table,
    .inventory-form {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px;
        box-shadow: var(--shadow);
    }

    .inventory-table h2,
    .inventory-form h3 {
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border);
    }

    /* ── TABLE ── */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 10px 12px;
        border-bottom: 1.5px solid var(--border);
        text-align: left;
        background: var(--bg);
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        font-size: 13.5px;
        vertical-align: middle;
    }

    table tr:last-child td {
        border-bottom: none;
    }

    table tr:hover td {
        background: #f5f2ed;
    }

    /* ── INPUTS & SELECTS ── */
    input, select {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid var(--border);
        border-radius: 7px;
        font-size: 13px;
        font-family: 'Syne', sans-serif;
        outline: none;
        background: var(--surface);
        color: var(--text);
        transition: border-color 0.18s, box-shadow 0.18s;
        margin-bottom: 10px;
        appearance: none;
        -webkit-appearance: none;
    }

    input:focus, select:focus {
        border-color: var(--border-focus);
        box-shadow: 0 0 0 3px rgba(26,26,26,0.07);
    }

    /* ── BUTTONS ── */
    button {
        border: none;
        padding: 9px 16px;
        border-radius: 7px;
        font-size: 12px;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        letter-spacing: 0.04em;
        cursor: pointer;
        background: var(--text);
        color: white;
        transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
        white-space: nowrap;
    }

    button:hover {
        opacity: 0.88;
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }

    .update-btn {
        background: var(--accent);
        box-shadow: 0 1px 4px rgba(44,69,245,0.18);
    }

    .update-btn:hover {
        background: var(--accent-hover);
        box-shadow: 0 3px 10px rgba(44,69,245,0.26);
    }

    .delete-btn {
        background: var(--danger);
        box-shadow: 0 1px 4px rgba(220,38,38,0.18);
    }

    .delete-btn:hover {
        background: var(--danger-hover);
        box-shadow: 0 3px 10px rgba(220,38,38,0.26);
    }

    /* Add item submit button */
    .inventory-form form > button[type="submit"] {
        width: 100%;
        padding: 11px;
        font-size: 13px;
        background: var(--accent);
        box-shadow: 0 2px 8px rgba(44,69,245,0.18);
        margin-top: 2px;
        margin-bottom: 0;
    }

    .inventory-form form > button[type="submit"]:hover {
        background: var(--accent-hover);
        box-shadow: 0 4px 14px rgba(44,69,245,0.28);
    }

    /* ── TABLE ACTION CELL ── */
    .action-cell {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

    .action-cell form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-cell form input[type="number"],
    .action-cell form select {
        width: 110px;
        margin-bottom: 0;
        font-size: 12px;
        padding: 7px 10px;
        flex-shrink: 0;
    }

    /* ── STATUS BADGE ── */
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        font-family: 'DM Mono', monospace;
        text-transform: uppercase;
    }

    .badge-instock  { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-outstock { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    /* ── RESPONSIVE ── */
    @media(max-width: 900px){
        .inventory-layout { grid-template-columns: 1fr; }
    }

    /* ── ANIMATIONS ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>

<div class="header">
    <header>
        <h1>Tech Support Dashboard</h1>
        <p>Monitor and assign support tickets</p>
    </header>
    <nav class="header-nav">
        <a href="dashboard.php"
           class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'nav-active' : 'nav-inactive'; ?>"
           style="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'box-shadow:0 0 0 3px rgba(44,69,245,0.22);' : ''; ?>">
            Ticket Management
        </a>
        <a href="inventory.php"
           class="<?php echo basename($_SERVER['PHP_SELF']) === 'inventory.php' ? 'nav-active' : 'nav-inactive'; ?>"
           style="<?php echo basename($_SERVER['PHP_SELF']) === 'inventory.php' ? 'box-shadow:0 0 0 3px rgba(44,69,245,0.22);' : ''; ?>">
            Inventory Management
        </a>
    </nav>
</div>

<p class="section-eyebrow">Inventory</p>

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
                <th>Actions</th>
            </tr>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td style="font-family:'DM Mono',monospace;"><?php echo $row['total_qty']; ?></td>
                <td style="font-family:'DM Mono',monospace;"><?php echo $row['available']; ?></td>
                <td>
                    <span class="badge <?php echo $row['status'] === 'In Stock' ? 'badge-instock' : 'badge-outstock'; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
                <td>
                    <div class="action-cell">
                        <form method="POST" action="update_item.php">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="In Stock">In Stock</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                            <input type="number" name="total_qty" placeholder="Total Qty" required>
                            <input type="number" name="available" placeholder="Available" required>
                            <button class="update-btn" type="submit">Update</button>
                        </form>
                        <form method="POST" action="delete_item.php">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button class="delete-btn" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </table>

        <?php else: ?>
        <p style="color:var(--text-muted); font-size:13px; padding: 12px 0;">No items found.</p>
        <?php endif; ?>

    </div>

    <!-- RIGHT SIDE (FORM) -->
    <div class="inventory-form">

        <h3>Add Item</h3>

        <form method="POST">

            <input type="text" name="name" placeholder="Item Name" required>

            <input type="text" name="category" placeholder="Category" required>

            <input type="number" name="total_qty" placeholder="Total Quantity" required>

            <input type="number" name="available" placeholder="Available" required>

            <select name="status" required>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>

            <button type="submit">Add Item</button>

        </form>

    </div>

</div>

</body>
</html>