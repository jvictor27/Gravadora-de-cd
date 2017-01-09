<?php 
	header("Content-Type:application/xml");
	header('Content-Disposition: attachment; filename="cd-xml.xml"');
	try{

		include("conn.php");
		

		$cd_id= $_GET['id_cd'];

		$stmt = $dbh->prepare("SELECT * FROM ws_cds WHERE cd_id = $cd_id");
		$stmt->execute();
		$resultado = $stmt->fetchAll();

		$dom = new DOMDocument('1.0', 'utf-8');
		$cd = $dom->createElement('cd');
		

		foreach ($resultado as $row) {
			
			$id = $dom->createElement("id", $row['cd_id']);
			$titulo = $dom->createElement("titulo", $row['cd_title']);
			$data = $dom->createElement("dataLancamento", $row['cd_date']);
		}

		$cd->appendChild($id);
		$cd->appendChild($titulo);
		$cd->appendChild($data);
		$dom->appendChild($cd);


		echo $dom->saveXML();

	}
	catch(PDOException $e){
		echo $e->getMessage();
	}

 ?>