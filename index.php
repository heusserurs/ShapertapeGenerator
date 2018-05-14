<?php
require_once('lib/shaper.class.php');

$shaper = new shaper();

header('Content-Type: image/svg+xml');
print_r($shaper->render(420,297,40));

?>