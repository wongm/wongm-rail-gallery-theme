<?php
$where = "i.title REGEXP '_[0-9]{4}' OR i.title REGEXP 'DSCF[0-9]{4}'";
setCustomPhotostream($where);
require_once('uploads-base.php');
?>