<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header('Content-Disposition: attachment; filename="sgo.xml"');
echo file_get_contents('output/excelxml.xml');
exit;
?>