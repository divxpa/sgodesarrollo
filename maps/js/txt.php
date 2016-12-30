<?php
/*
   estructura del archivo
   0 = id de grupo
   1 = tipo de grupo (M) Marker (P) Polygon (PL) Polyline
   2 = nombre del elemento
   3 = puntos (separados por coma)
   4 = informacion adicional para InfoWindow
   5 = datos de imagen o color, separados por coma (archivo o color, ancho, alto)
   6 = visible (1 0)
*/
   $f = fopen($sourcefile,"r");

   while (!feof($f)) {
	   $line = fgets($f, 4096);
	   $lineArray = explode("|",$line);
	   $data[$lineArray[0]][] = array(
								   $lineArray[1],
								   $lineArray[2],
								   explode(",",$lineArray[3]),
								   $lineArray[4],
								   explode(",",$lineArray[5]),
								   $lineArray[6]
	   							);
   }

   fclose ($f);
?>