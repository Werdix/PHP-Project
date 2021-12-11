<?php
    require_once("includes/db_connect.php");
    $order = filter_input(INPUT_GET, "order",FILTER_SANITIZE_STRING);
    
    $query = 'SELECT employee.employee_id,employee.name,employee.surname,employee.job, room.name AS RoomName, room.phone FROM employee INNER JOIN room ON room.room_id = employee.room ';
    switch($order){
        case 'name_asc':
            $query .= 'ORDER BY employee.name ASC';
            break;
        case 'name_desc':
            $query .= 'ORDER BY employee.name DESC';
            break;
        case 'room_asc':
            $query .= 'ORDER BY room.name ASC';
            break;
        case 'room_desc':
            $query .= 'ORDER BY room.name DESC';
            break;
        case 'tel_asc':
            $query .= 'ORDER BY room.phone ASC';
            break;
        case 'tel_desc':
            $query .= 'ORDER BY room.phone DESC';
            break;
        case 'job_asc':
            $query .= 'ORDER BY employee.job ASC';
            break;
        case 'job_desc':
            $query .= 'ORDER BY employee.job DESC';
            break;
        default:
            $query .= 'ORDER BY employee.name ASC';
            break;
    }
    $stmt = $pdo->query($query);
    
    if($stmt->rowCount()==0)
    {
        $ok = false;
        http_response_code(404);
    }

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        
    a:active,a:hover{
            color: orange;
        }
    h1{
        margin-left: 20px;
    }
    table{
        margin-left: 15px;
    }
       
    a{
        text-align:center;  
    }
    li{
    position: relative;
    display: block;
    padding: 10px 15px;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid #ddd;
    }
        </style>
    <title>Seznam zaměstnanců</title>
</head>
<body>
<?php 
        if ($stmt->rowCount() == 0) {
            echo "Záznam neobsahuje žádná data";
        } else {
            echo "<h2>Seznam zaměstnanců</h2>";
            echo "<table class = 'table table-striped'>";
            echo "<thead><tr><th>Jméno<a href = 'lide.php?order=name_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'lide.php?order=name_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th>";
            echo "<th>Místnost<a href = 'lide.php?order=room_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'lide.php?order=room_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th>";
            echo "<th>Telefon<a href = 'lide.php?order=tel_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'lide.php?order=tel_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th>";
            echo "<th>Pozice<a href = 'lide.php?order=job_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'lide.php?order=job_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th>";
            echo "</tr></thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) { 
                echo "<tr>";
                echo "<td><a href='clovek.php?employee_id={$row->employee_id}'>".htmlspecialchars($row->name)." ".htmlspecialchars($row->surname)."</a></td>";
                echo "<td>".htmlspecialchars($row->RoomName)."</td>";
                echo "<td>".htmlspecialchars($row->phone)."</td>";
                echo "<td>".htmlspecialchars($row->job)."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
        unset($stmt);
        
?>
</body>
</html>