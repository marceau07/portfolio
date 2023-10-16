<?php

include 'config.php';

$idOrdinateur = $_POST['idOrdinateur'];

$sql = "SELECT * FROM ordinateur WHERE idOrdinateur=" . $idOrdinateur;
$result = mysqli_query($con, $sql);

$respons = "<table border='0' width='100%'>";
while ($row = mysqli_fetch_array($result)) {
    $ip = $row['ip'];
    $mac = $row['mac'];
    $statut = $row['statut'];
    $employe = $row['employe'];

    $respons .= "<tr>";
    $respons .= "<td>ip:</td><td>" . $ip . "</td>";
    $respons .= "</tr>";

    $respons .= "<tr>";
    $respons .= "<td>mac:</td><td>" . $mac . "</td>";
    $respons .= "</tr>";


    $respons .= "<tr>";
    $respons .= "<td>statut:</td><td>" . $statut ."</td>";
    $respons .= "</tr>";


    $respons .= "<tr>";
    $respons .= "<td>employe:</td><td>" . $employe . "</td>";
    $respons .= "</tr>";
}
$respons .= "</table>";

echo $respons;
