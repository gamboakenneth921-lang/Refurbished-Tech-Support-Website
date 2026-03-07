<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
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
     

<style>
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
body{
    background:#f1f5f9;
    font-family:Segoe UI, sans-serif;
}

.box-container{
    display:flex;
    gap:18px;
    flex-wrap:wrap;
    padding:20px;
}

.box{
    width:220px;
    padding:20px;
    background:white;
    border:1px solid #e2e8f0;
    border-radius:14px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

.box-title{
    color:#64748b;
    font-size:14px;
    margin-bottom:8px;
}

.box-number{
    font-size:30px;
    font-weight:600;
    color:#0f172a;
}
.dashboard-container{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
    padding:20px;
    background:#f1f5f9;
    font-family:Segoe UI, sans-serif;
}

/* Panels */
.panel{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:14px;
    padding:18px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.panel-header{
    border-bottom:1px solid #e2e8f0;
    padding-bottom:12px;
    margin-bottom:15px;
}

/* Ticket Cards */
.ticket-card{
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:14px;
    margin-bottom:12px;
}

.ticket-title{
    font-weight:600;
    margin-bottom:6px;
}

.ticket-meta{
    font-size:13px;
    color:#64748b;
}

.ticket-desc{
    margin-top:10px;
    font-size:14px;
    color:#475569;
}

/* Employees */
.employee-card{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:14px;
    margin-bottom:10px;
}

.emp-name{
    font-weight:600;
}

.emp-role{
    font-size:13px;
    color:#64748b;
}

/* Status badges */
.status{
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    color:white;
}

.available{
    background:#22c55e;
}

.busy{
    background:#ef4444;
}




/* Responsive */
@media(max-width:900px){
    .dashboard-container{
        grid-template-columns:1fr;
    }
}
input, select{
    width:100%;
    padding:10px;
    border:1px solid #e2e8f0;
    border-radius:8px;
    font-size:14px;
    outline:none;
    background:white;
}

input:focus, select:focus{
    border-color:#3b82f6;
}
button{
    border:none;
    padding:7px 14px;
    border-radius:8px;
    font-size:13px;
    cursor:pointer;
    background:#0f172a;
    color:white;
    transition:0.2s;
}

button:hover{
    opacity:0.85;
}

.delete-btn{
    background:#ef4444;
}

.update-btn{
    background:#3b82f6;
}


  
</style>
</head>

<body>

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
 <h1>Ticket</h1>

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

    <!-- LEFT SIDE — Pending Tickets this is the pending panel ticket-->
    <div class="panel tickets-panel">
        <h1>Pending Tickets</h1>

    <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <div class="ticket-card" onclick="toggleEmployees(this)">

            <div class="ticket-title">
                <?php echo $row['title']; ?>
            </div>

            <div class="ticket-meta">
                <?php echo $row['user_name']; ?> |
                <?php echo $row['email']; ?>
            </div>

            <div class="ticket-desc">
                <?php echo $row['description']; ?>
            </div>

            <div class="ticket-status">
                Status: <?php echo $row['status']; ?>
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
                        </div>
                    </div>

                <?php } ?>

            </div>

        </div>

    <?php } ?>

</div>





 <!-- employee panel assigns and display input add and remove-->
    <div class="panel employee-panel">

      <div class="card">

        <h3>Add Employee</h3>

        <form method="POST" action="add_employee.php">

            <input type="text" name="name" placeholder="Employee Name" required>
            <br><br>

            <input type="text" name="role" placeholder="Role / Position" required>
            <br><br>

                <select name="status">
                    <option>Available</option>
                    <option>Busy</option>
                    <option>Offline</option>
                </select>

            <br><br>

            <button type="submit">Add Employee</button>

        </form>

         <!-- shows the status role and name of an employee from the employee panel -->
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



                <form method="POST" action="delete_employee.php">
                    <input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                    <button type="submit">Delete</button>
                </form>


                <form method="POST" action="update_employee.php">
                    <input type="hidden" name="id" value="<?php echo $emp['id']; ?>">
                    <button type="submit">Update</button>
                    
                    <select name="status">
                        <option value="Available">Available</option>
                        <option value="Busy">Busy</option>
                        <option value="Offline">Offline</option>
                    </select>

                    <br><br>

                    
                </form>
               
            </div>

        <?php } ?>

    </div>


</div>

 <!-- Assigned ticket panel -->
<div class="panel Assigned-Tickets">
    <h1>Assigned Tickets</h1>
    <?php while($rows = mysqli_fetch_assoc($assignedTickets)){ ?>
    <div class="assigned-card ticket-card">
        <div class="ticket-title">
            <?php echo $rows['title']; ?>
        </div>

        <div class="ticket-meta">
            <?php echo $rows['user_name']; ?> |
            <?php echo $rows['email']; ?>
        </div>

        <div class="ticket-desc">
            Description: <?php echo $rows['description']; ?>
        </div>
        <div class="ticket-status">
            Status: <?php echo $rows['status']; ?>
        </div>
       
    </div>
    <?php } ?>


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