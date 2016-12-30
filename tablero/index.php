<?php
//>> Pasar los GET
header('Location: web/'. ($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''));