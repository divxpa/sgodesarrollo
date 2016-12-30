<?php
$xml = simplexml_load_file($sourcefile);

foreach($xml->children() as $location){

	foreach($location->children() as $locationData){

		switch($locationData->getName()){
			case "groupid":
				$groupid = strip_tags($locationData);
				break;
			case "type":
				$type = strip_tags($locationData);
				break;
			case "name":
				$name = strip_tags($locationData);
				break;
			case "points":
				$points = strip_tags($locationData);
				break;
			case "infowindow":
				$infowindow = strip_tags($locationData,"<p><br><a>");
				break;
			case "aditionaldata":
				$aditionaldata = strip_tags($locationData);
				break;
			case "visible":
				$visible = strip_tags($locationData);
				break;
		}

	}

	$data[$groupid][] = array(
							$type,
							$name,
							explode(",",$points),
							$infowindow,
							explode(",",$aditionaldata),
							$visible
						);

}

?>