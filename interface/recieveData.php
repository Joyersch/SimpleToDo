<?php
$injson = file_get_contents('php://input');
$data =  json_decode($injson, true);
?>