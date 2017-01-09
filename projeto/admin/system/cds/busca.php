<?php
// Incluir aquivo de conex�o
include"conn.php";

// Recebe o valor enviado
$valor = $_GET['valor'];





$stmt = $dbh->prepare("SELECT * FROM ws_cds WHERE cd_title LIKE '%".$valor."%' or cd_date LIKE '%".$valor."%'");

$stmt->execute();
$resultado = $stmt->fetchAll();

// Exibe todos os valores encontrados
foreach ($resultado as $row) {
echo"<form action=\"baixajson.php\" method=\"get\">";

echo "---------------------------------------------------------------<br>";
echo "Titulo: ".$row['cd_title']."<br>";
echo "Data: ".$row['cd_date'];
echo "<br>---------------------------------------------------------------<br>";
echo"<input type=\"text\" style=\"display:none;\" name=\"id_cd\" value=\"".$row['cd_id']."\">";
echo"<button class=\"btn blue\" type=\"submit\">Baixar JSON</button> ";
echo" <button class=\"btn blue\" type=\"submit\" formaction=\"baixaxml.php\">Baixar XML</button>";
echo"</form>";
}
// Acentuacao
header("Content-Type: text/html; charset=ISO-8859-1",true);
?>
