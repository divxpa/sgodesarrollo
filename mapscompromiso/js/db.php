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
   // $sql = "SELECT * FROM obra o where o.Latitud <> '' and o.Longitud <> ''"; 
   $sql = "SELECT * FROM obra o where o.Latitud <> '' and o.Longitud <> ''"; 
   $query = mysql_query($sql); 





/*
   $data[]


   <location>
      <groupid>1</groupid>
      <type>G</type>
      <name>ZonaCentro</name>
      <points>-39.531119,-71.466208,-38.17905,-69.270961</points>
      <infowindow></infowindow>
      <aditionaldata>images/zonacentro.png,0.5</aditionaldata>
      <visible>1</visible>
   </location>
   <location>
      <groupid>2</groupid>
      <type>G</type>
      <name>ZonaConfluencia</name>
      <points>-39.803547,-70.031742,-37.70205,-67.998147</points>
      <infowindow></infowindow>
      <aditionaldata>images/zonaconfluencia.png,0.5</aditionaldata>
      <visible>1</visible>
   </location>
   <location>
      <groupid>3</groupid>
      <type>G</type>
      <name>ZonaSur</name>
      <points>-41.093842,-71.965469,-39.012383,-69.544461</points>
      <infowindow></infowindow>
      <aditionaldata>images/zonasur.png,0.5</aditionaldata>
      <visible>1</visible>
   </location>*/


   $i = 4;
/*
   while($row = mysql_fetch_object($query)) 
   { 
      $data[$i][] = array(
                        0,
                        'M',
                        $row->Denominacion,
                        $row->Latitud.",".$row->Longitud,
                        '<p style="font-size:12px;">Destino de fondos: Clínica de diagnóstico por imágenes<br>Actividad: Servicios<br>Localización:   Neuquén Capital<br>Generación de empleo: 131 puestos de trabajo.<br>Zona: Confluencia<br>Página web: www.funmed.org.ar<br>Domicilio: Santa Fe Nº 273 - Neuquén CP 8300</p>',
                        '909aff',
                        1
                     );
      $i++;
   } 
*/
?>