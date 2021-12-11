<?php
    require_once("includes/db_connect.php");
    $state = "ok";
    $emp_id = $_GET['employee_id'];
    $emp_id = filter_input(INPUT_GET,"employee_id",FILTER_VALIDATE_INT);
    //query pro zaměstnance
    $query = 'SELECT employee.wage, employee.name,employee.surname,employee.job, room.name AS RoomName, room.room_id FROM employee INNER JOIN room ON room.room_id = employee.room WHERE employee.employee_id = ?';
    //query pro místnosti
    $query2 = 'SELECT room.name AS RName, room.room_id AS RiD FROM 
    ((room INNER JOIN `key` ON room.room_id=`key`.room) 
    INNER JOIN employee ON employee.employee_id = `key`.employee) WHERE `key`.employee = ?';
    
    if(!$emp_id)
    {
        $state = "bad request";
        http_response_code(400);
    }

    $stmt2 = $pdo->prepare($query2);
    $stmt2  ->execute([$emp_id]);
    $stmt1 = $pdo->prepare($query);
    $stmt1 ->execute([$emp_id]);

    $emp2 = $stmt2->fetch(PDO::FETCH_OBJ);
    $emp = $stmt1->fetch(PDO::FETCH_OBJ);
    
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        p,a{
            font-weight: bold;
            margin-left: 25px;
            font-size: 16px;
        }
        h4,h2{
            margin-left:10px ;
        }
    </style>
    <title><?php echo "Karta: ".htmlspecialchars($emp->name)." ".htmlspecialchars($emp->surname);?></title>
</head>
<body>
    <?php
    if($state == "ok")
    {
        echo "<h2>Karta osoby: ".htmlspecialchars($emp->name)." ".htmlspecialchars($emp->surname)."</h2>";
        echo "<h4>Jméno:</h4>";
        echo "<p>".htmlspecialchars($emp->name)."</p>";
        echo "<h4>Příjmení:</h4>";
        echo "<p>".htmlspecialchars($emp->surname)."</p>";
        echo "<h4>Pozice:</h4>";
        echo "<p>".htmlspecialchars($emp->job)."</p>";
        echo "<h4>Mzda:</h4>";
        echo "<p>".number_format($emp->wage,2,",",".")." Kč</p>";
        echo "<h4>Místnost:</h4>";
        echo "<a href ='mistnost.php?room_id={$emp->room_id}'>".htmlspecialchars($emp->RoomName)."</a>";
        echo "<h4>Klíče:</h4>";
            while($emp2 = $stmt2->fetch(PDO::FETCH_OBJ))
            {
                echo "<a href='mistnost.php?room_id={$emp2->RiD}'>".htmlspecialchars($emp2->RName)."</a>";
                echo "<br>";
            }
        echo "<br>";
        echo "<a href ='lide.php'>Seznam zaměstnanců</a>";
    }
        elseif($state=="bad request")
        {
            echo "<h2>Error: Bad Request</h2>";
            http_response_code(400);
        } 
        else
        {
            echo"<h2> Error: Not Found</h2>";
            http_response_code(404);
        } 
        unset($stmt1);
        unset($stmt2);
    ?>
</body>
</html>