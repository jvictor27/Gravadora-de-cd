<?php

include("conn.php");
header("Content-type: application/json");
header('Content-Disposition: attachment; filename="cd-json.json"');
$cd_id= $_GET['id_cd'];

$stmt = $dbh->prepare("SELECT * FROM ws_cds WHERE cd_id = $cd_id");
$stmt->execute();
$resultado = $stmt->fetchAll();

foreach ($resultado as $row) {
				$id_cd = $row['cd_id'];
        $titulo_cd = $row['cd_title'];
        $data_cd = $row['cd_date'];
}

class MontarJson{
  public $id, $titulo, $dataLancamento;
  function __construct($id, $titulo, $data)
  {
    $this->id=$id;
    $this->titulo=$titulo;
    $this->dataLancamento=$data;
  }
}

$jsonBaixar = json_encode(new MontarJson($id_cd, $titulo_cd, $data_cd));

			echo $jsonBaixar;

 ?>
