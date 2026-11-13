<?php

chdir("..");

exec("php Partarum/PartarumCLI/v_b/PartarumServer/build.php", $output, $reCode);

echo $reCode . "\n";

print_r($output);