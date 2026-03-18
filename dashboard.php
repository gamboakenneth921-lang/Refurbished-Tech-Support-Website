<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])|| $_SESSION['roles'] != 'admin'){
    header("Location: login.php");
    exit();
}

//displays all the pending tickets in the pending panel
$result = mysqli_query($conn,
"SELECT * FROM tickets WHERE status='Pending' ORDER BY created_at DESC");

//shows all employees and display in the employee panel
$EmpResult = mysqli_query($conn,
"SELECT * FROM employees");

//show result of all available staffs only numbers above
$availableCount = mysqli_query($conn,
"SELECT COUNT(*) as total FROM employees WHERE status='Available'");

$availableStaff = mysqli_fetch_assoc($availableCount);

//shows the number of assigned tickets only numbers above
$countAssigned = mysqli_query($conn,
"SELECT COUNT(*) as totals FROM tickets WHERE status='Assigned'");

$assignedCount = mysqli_fetch_assoc($countAssigned);


//shows results of tickets where pending shows numbers above
$alltickets = mysqli_query($conn,
"SELECT COUNT(*) as all_total FROM tickets WHERE status='Pending'");

$availableTicket = mysqli_fetch_assoc($alltickets);



//display the assigned tickets below the pending tickets
$assignedTickets = mysqli_query($conn,
"SELECT *  FROM tickets WHERE status='Assigned'");

//$assignedAvailableTickets = mysqli_fetch_assoc($assignedTickets);

//$Employee_result = mysqli_query($conn,
//"SELECT * FROM employees");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        --success: #16a34a;
        --warning: #d97706;
        --radius: 10px;
        --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.05);
        --tag-high-bg: #fff1e6;
        --tag-high: #c44d00;
        --tag-pending-bg: #fffbe6;
        --tag-pending: #9a7500;
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

    /* ── STAT BOXES ── */
    .box-container {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        animation: fadeUp 0.38s ease both;
        animation-delay: 0.05s;
    }

    .box {
        min-width: 180px;
        padding: 18px 22px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .box-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .box-number {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--text);
        font-family: 'DM Mono', monospace;
    }

    /* ── DASHBOARD GRID ── */
    .dashboard-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        background: transparent;
        font-family: 'Syne', sans-serif;
        animation: fadeUp 0.4s ease both;
        animation-delay: 0.1s;
    }

    /* ── PANELS ── */
    .panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px;
        box-shadow: var(--shadow);
    }

    .panel h1 {
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border);
    }

    .panel h3 {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.01em;
        margin-bottom: 14px;
    }

    /* ── TICKET CARDS ── */
    .ticket-top {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 7px;
      flex-wrap: wrap;
    }
    .ticket-item {
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 16px 18px;
      background: var(--bg);
      transition: border-color 0.15s, box-shadow 0.15s;
      cursor: pointer;
    }
     .tag {
      font-family: 'DM Mono', monospace;
      font-size: 10.5px;
      font-weight: 500;
      padding: 3px 9px;
      border-radius: 4px;
      letter-spacing: 0.04em;
    }
     .tag-high {
      background: var(--tag-high-bg);
      color: var(--tag-high);
      border: 1px solid #f5c9a0;
    }
    .tag-pending {
      background: var(--tag-pending-bg);
      color: var(--tag-pending);
      border: 1px solid #f0df90;
    }
     .ticket-desc {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .ticket-card {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 12px;
        background: var(--bg);
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .ticket-card:hover {
        border-color: #b8b0a6;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .ticket-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .ticket-meta {
        font-family: 'DM Mono', monospace;
        font-size: 11.5px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .ticket-desc {
        font-size: 13px;
        color: #5a5650;
        margin-top: 8px;
        line-height: 1.5;
    }

    .ticket-status {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: var(--text-muted);
    }

    /* ── EMPLOYEE CARDS ── */
    .employee-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 10px;
        background: var(--bg);
    }

    .emp-name {
        font-weight: 700;
        font-size: 14px;
    }

    .emp-role {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
        font-family: 'DM Mono', monospace;
    }

    /* ── STATUS BADGES ── */
    .status {
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-family: 'DM Mono', monospace;
    }

    .available { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .busy      { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    /* ── INPUTS & SELECTS ── */
    input, select {
        width: 100%;
        padding: 10px 13px;
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
    }

    button:hover {
        opacity: 0.88;
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }

    .delete-btn {
        background: var(--danger);
        box-shadow: 0 1px 4px rgba(220,38,38,0.18);
    }

    .delete-btn:hover {
        background: var(--danger-hover);
        box-shadow: 0 3px 10px rgba(220,38,38,0.26);
    }

    .update-btn {
        background: var(--accent);
        box-shadow: 0 1px 4px rgba(44,69,245,0.18);
    }

    .update-btn:hover {
        background: var(--accent-hover);
        box-shadow: 0 3px 10px rgba(44,69,245,0.26);
    }

    /* Form submit button inside employee add form */
    form > button[type="submit"] {
        background: var(--accent);
        box-shadow: 0 2px 8px rgba(44,69,245,0.18);
        width: 100%;
        padding: 11px;
        font-size: 13px;
        margin-top: 2px;
    }

    form > button[type="submit"]:hover {
        background: var(--accent-hover);
        box-shadow: 0 4px 14px rgba(44,69,245,0.28);
    }

    /* Employee popup */
    .employee-popup {
        margin-top: 12px;
        border-top: 1px solid var(--border);
        padding-top: 12px;
    }

    /* Assigned panel — full width below grid */
    .Assigned-Tickets {
        grid-column: 1 / -1;
    }

    /* Card wrapper inside employee panel */
    .card {
        /* no extra border, just spacing */
    }

    /* Button row in employee ticket-card */
    .ticket-card form {
        display: inline-block;
        margin-top: 8px;
        margin-right: 6px;
    }

   .ticket-card .btn-row {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.ticket-card .btn-row form {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ticket-card .btn-row select {
    width: auto;
    margin-bottom: 0;
    font-size: 12px;
    padding: 7px 10px;
}

    /* Responsive */
    @media(max-width: 900px){
        .dashboard-container { grid-template-columns: 1fr; }
        .Assigned-Tickets { grid-column: 1; }
    }

    /* Animations */
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
        <br>
        <a href="logout.php">
            <button>LOGOUT</button>
        </a>
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

<p class="section-eyebrow">Overview</p>

<div class="box-container">

    <div class="box">
        <div class="box-title">Pending Tickets</div>
        <div class="box-number">
            <?php echo $availableTicket['all_total']; ?>
        </div>
    </div>

    <div class="box">
        <div class="box-title">Assigned Tickets</div>
        <div class="box-number">
             <?php echo $assignedCount['totals']; ?>
        </div>
    </div>

    <div class="box">
        <div class="box-title">Available Staff</div>
        <div class="box-number">
            <?php echo $availableStaff['total']; ?>
        </div>
    </div>

</div>

<div class="dashboard-container">

    <!-- LEFT SIDE — Pending Tickets -->
    <div class="panel tickets-panel">
        <h1>Pending Tickets</h1>

        <?php while($row = mysqli_fetch_assoc($result)){ ?>

    <div class="ticket-item" onclick="toggleEmployees(this)" style="margin-bottom:12px;">

        <div class="ticket-top">
            <span class="ticket-title">ID: <?php echo $row['id']; ?> | <?php echo $row['title']; ?> </span>
            <span class="tag tag-high"><?php echo $row['priority']; ?></span>
            <span class="tag tag-pending"><?php echo $row['status']; ?></span>
        </div>

        <p class="ticket-desc"><?php echo $row['description']; ?></p>

        <div class="ticket-meta">
            <span><?php echo $row['email']; ?> |</span>
            <span><?php echo $row['created_at']; ?></span>
        </div>

        <!-- Hidden Available Employees -->
        <div class="employee-popup" style="display:none;">

            <?php
            $availableEmployees = mysqli_query($conn,
            "SELECT * FROM employees WHERE status='Available'");

            while($emp = mysqli_fetch_assoc($availableEmployees)){
            ?>

                <div class="employee-card">
                    <div>
                        <div class="emp-name">
                            <?php echo $emp['name']; ?>
                        </div>
                        <div class="emp-role">
                            <?php echo $emp['role']; ?>
                        </div>
                        <form method="POST" action="assign_ticket.php">
                            <input type="hidden" name="assign_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="employee" value="<?php echo $emp['id']; ?>">
                            <button type="submit" class="update-btn">Assign</button>
                        </form>
                    </div>
                </div>

            <?php } ?>

        </div>

    </div>

<?php } ?>

</div>

    <!-- Employee Panel -->
    <div class="panel employee-panel">

      <div class="card">

        <h3>Add Employee</h3>

        <form method="POST" action="add_employee.php">

            <input type="text" name="name" placeholder="Employee Name" required>

            <input type="text" name="role" placeholder="Role / Position" required>

            <select name="status">
                <option>Available</option>
                <option>Busy</option>
                <option>Offline</option>
            </select>

            <button type="submit">Add Employee</button>

        </form>

        <?php while($emp = mysqli_fetch_assoc($EmpResult)){ ?>

            <div class="ticket-card">

                <div class="ticket-title">
                    <?php echo $emp['name']; ?>
                </div>

                <div class="ticket-meta">
                    <?php echo $emp['role']; ?> |
                    
                </div>

                <div class="ticket-status">
                    Status: <?php echo $emp['status']; ?>
                </div>

                <div class="btn-row">
                    <form method="POST" action="delete_employee.php">
                        <input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>

                    <form method="POST" action="update_employee.php">
                        <input type="hidden" name="id" value="<?php echo $emp['id']; ?>" >
                        <select name="status">
                            <option value="Available">Available</option>
                            <option value="Busy">Busy</option>
                            <option value="Offline">Offline</option>
                        </select>
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </div>
               
            </div>

        <?php } ?>

    </div>

    </div>

    <!-- Assigned Ticket Panel -->
    <div class="panel Assigned-Tickets">
        <h1>Assigned Tickets</h1>
        <?php while($rows = mysqli_fetch_assoc($assignedTickets)){
            
         $employeeID = $rows['assigned_to'];

        $assignedName = mysqli_query($conn,
        "SELECT * FROM employees WHERE id='$employeeID'");

        $emp = mysqli_fetch_assoc($assignedName);
       

        ?>
        <div class="assigned-card ticket-card">
             <div class="ticket-top">
                <span class="ticket-title">ID: <?php echo $rows ['id']; ?> | <?php echo $rows['title']; ?></span>
                <span class="tag tag-high"><?php echo $rows['priority']; ?></span>
                <span class="tag tag-pending"><?php echo $rows['status']; ?></span>
            </div>

            <p class="ticket-desc"><?php echo $rows['description']; ?></p>

            <div class="ticket-meta">
                <span><?php echo $rows['email']; ?> |</span>
                <span><?php echo $rows['created_at']; ?></span>
            </div>
             <div class="ticket-status">
                Assigned to: <?php echo  $emp['name']; ?>
            </div>
            <br>
            <form method="POST" action="completed_ticket.php">
                <button type="submit" name="complete_id" value="<?php echo $rows['id'] ?>">Completed</button>
            </form>

            
           
           
        </div>
        <?php } ?>
    </div>

</div>

<script>
//shows the available staff to be assigned
function toggleEmployees(card) {

    let popup = card.querySelector(".employee-popup");

    if (popup.style.display === "none") {
        popup.style.display = "block";
    } else {
        popup.style.display = "none";
    }

}
</script>

</body>
</html>
