<?php

/**
 *
 *
 * @version $Id: ExcelXml.php 563 2012-02-17 14:52:46Z facundo $
 * @copyright 2009
 */

/**
 * ExcelXml
 *
 *
 */
class ExcelXml {

	private $hojas = null;
	private $xml = null;

    function __construct(){
    	$this->hojas = array();
    }

	public function AgregarHoja($nombre, $datos){
		$this->hojas[] = array(
							"Nombre" => $nombre,
							"Datos" => $datos
						);
	}

	public function GenerarXml(){
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<?mso-application progid=\"Excel.Sheet\"?>
				<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
	 					xmlns:o=\"urn:schemas-microsoft-com:office:office\"
	 					xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
	 					xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
	 					xmlns:html=\"http://www.w3.org/TR/REC-html40\">
	 					<DocumentProperties xmlns=\"urn:schemas-microsoft-com:office:office\">
	  						<Author>Punto Gap S.R.L.</Author>
						<LastAuthor>Punto Gap S.R.L.</LastAuthor>
						<Created>2010-09-22T04:06:26Z</Created>
						<LastSaved>2010-09-22T04:30:11Z</LastSaved>
						<Company>Punto Gap S.R.L.</Company>
						<Version>11.6360</Version>
					</DocumentProperties>
	  					<ExcelWorkbook xmlns=\"urn:schemas-microsoft-com:office:excel\">
						<WindowHeight>8535</WindowHeight>
						<WindowWidth>12345</WindowWidth>
						<WindowTopX>480</WindowTopX>
						<WindowTopY>90</WindowTopY>
						<ProtectStructure>False</ProtectStructure>
						<ProtectWindows>False</ProtectWindows>
					</ExcelWorkbook>";

		for($i=0;$i<count($this->hojas);$i++){
			$xml .= "<Worksheet ss:Name=\"". $this->hojas[$i]["Nombre"] ."\">
						<Table>";

			for($j=0;$j<count($this->hojas[$i]["Datos"]);$j++){
				$xml .= "<Row>";

				for($k=0;$k<count($this->hojas[$i]["Datos"][$j]);$k++){

					if(is_array($this->hojas[$i]["Datos"][$j][$k])){
						$xml .= "<Cell><Data ss:Type=\"".$this->hojas[$i]["Datos"][$j][$k][0]."\">".$this->hojas[$i]["Datos"][$j][$k][1]."</Data></Cell>";
					}
					else{
						$xml .= "<Cell><Data ss:Type=\"String\">".$this->hojas[$i]["Datos"][$j][$k]."</Data></Cell>";
					}

				}

				$xml .= "</Row>";
			}

			$xml .= "	</Table>
					</Worksheet>";
		}

		$xml .= "</Workbook>";
		$this->xml = $xml;
		$handle = fopen("output/excelxml.xml", "w");
		fwrite($handle,$xml);
		fclose($handle);
	}

	public function getXml(){
		return $this->xml;
	}

	public function downloadXml(){
		include("protected/classes/excelxml/download.php");
	}

}
?>