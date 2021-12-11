<?php
require_once("includes/db_connect.php");
$state = "ok";
$roomId = $_GET['room_id'];
$roomId = filter_input(INPUT_GET,"room_id",FILTER_VALIDATE_INT);
if(!$roomId)
{
    $state = "bad request";
}
else{
    //query pro klíče
    $queryKeys = 'SELECT employee.name, employee.surname, employee.employee_id FROM ((`key` INNER JOIN room ON room.room_id=`key`.room) INNER JOIN employee ON `key`.employee = employee.employee_id) WHERE room_id = ?';
    $stmtKeys = $pdo->prepare($queryKeys);
    $stmtKeys -> execute([$roomId]);
    //$keys = $stmtKeys -> fetch(PDO::FETCH_OBJ);

    //query pro průměrný plat
    $queryAvg = "SELECT AVG(wage) AS AvgWage FROM employee INNER JOIN room ON employee.room = room.room_id WHERE room_id = ?";
    $stmtAvg = $pdo->prepare($queryAvg);
    $stmtAvg -> execute([$roomId]);
    $wageAvg = $stmtAvg->fetch(PDO::FETCH_OBJ);

    //query pro lidi 
    $queryEmp = 'SELECT employee.name AS EmNa, employee.surname AS EmSu, employee.employee_id AS EmId FROM employee RIGHT JOIN room ON employee.room = room.room_id WHERE room_id = ?';
    $stmtEmp = $pdo->prepare($queryEmp);
    $stmtEmp -> execute([$roomId]);
    //$join = $stmtEmp->fetch(PDO::FETCH_OBJ);
    

    $query = "SELECT * FROM room WHERE room_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt ->execute([$roomId]);
    $room = $stmt->fetch(PDO::FETCH_OBJ);
    $ok = true;
    if($stmt->rowCount()==0)
    {
        $ok = false;
        http_response_code(404);
    }
}
$title = $state == "ok" ? ("Místnost: ".$room->name):
         ($state == "bad request" ? "Error: Bad Request":"Error: Not Found");
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
    <title><?php echo htmlspecialchars($room->name);?></title>
</head>
<body>
    <?php
    if($state == "ok")
    {
    echo "<h2>".$room->name."</h2>";
    echo "<h4>Číslo:</h4>";
    echo "<p>".$room->no."</p>";
    echo "<h4>Tel.:</h4>";
    echo "<p>".$room->phone."</p>";
    echo "<h4>Průměrná mzda:</h4>";
    echo "<p>".number_format($wageAvg->AvgWage,2,",",".")." Kč"."</p>";
    echo "<h4>Klíče: </h4>";
    
    while($keys = $stmtKeys -> fetch(PDO::FETCH_OBJ))
    {
        echo "<a href='clovek.php?employee_id={$keys->employee_id}'>".htmlspecialchars($keys->name)." ".htmlspecialchars($keys->surname)."</a>";
        echo "<br>";
    }

    echo "<h4>Lidé: </h4>";
    while($join = $stmtEmp->fetch(PDO::FETCH_OBJ))
    {
        echo "<a href='clovek.php?employee_id={$join->EmId}'>".htmlspecialchars($join->EmNa)." ".htmlspecialchars($join->EmSu)."</a>";
        echo "<br>";
    }
    echo "<br>";
    echo "<a href ='mistnosti.php'>Seznam místností</a>";
    }
    elseif($state=="bad request")
    {
        echo "<h2>Error: Bad Request</h2>";
    } 
    else echo"<h2> Error: Not Found</h2>";
    unset($stmtEmp);
    unset($stmtAvg);
    unset($stmt);
    ?>
</body>
</html>