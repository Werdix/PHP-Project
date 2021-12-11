<?php
    require_once("includes/db_connect.php");
    
    $order = filter_input(INPUT_GET, "order",FILTER_SANITIZE_STRING);
    $query = 'SELECT * FROM room ';
    
    switch($order)
    {
        case 'name_asc':
            $query .= 'ORDER BY name ASC;';
            break;
        
        case 'name_desc':
            $query .= 'ORDER BY name DESC;';
            break;

        case 'num_asc':
            $query .= 'ORDER BY no ASC;';
            break;

        case 'num_desc':
            $query .= 'ORDER BY no DESC;';
            break;

        case 'tel_asc':
            $query .= 'ORDER BY phone ASC;';
            break;

        case 'tel_desc':
            $query .= 'ORDER BY phone DESC;';
            break;
        default:
            $query .= 'ORDER BY name ASC;';
            break;
            
    }
    $stmt = $pdo->query($query);
?>
<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <!-- Bootstrap-->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <title>Seznam místností</title>
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
        </style>
    </head>
    <body>
        <h1>Seznam místností</h1>
        <?php

        if ($stmt->rowCount() == 0) {
            echo "Záznam neobsahuje žádná data";
        } else {
            echo "<table class = 'table table-striped'>";
            echo "<thead><tr><th>Název<a href = 'mistnosti.php?order=name_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'mistnosti.php?order=name_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th>";
            echo "<th>Číslo <a href = 'mistnosti.php?order=num_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'mistnosti.php?order=num_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th>";
            echo "<th>Tel. <a href = 'mistnosti.php?order=tel_asc'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span></a><a href = 'mistnosti.php?order=tel_desc'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span></a></th></tr></thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) { 
                echo "<tr>";
                echo "<td> <a href='mistnost.php?room_id={$row->room_id}'>".htmlspecialchars($row->name)."</a></td>";
                echo "<td>".htmlspecialchars($row->no)."</td>";
                echo "<td>".($row->phone ?: "&mdash;")."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
        unset($stmt);
        ?>
    </body>
</html>