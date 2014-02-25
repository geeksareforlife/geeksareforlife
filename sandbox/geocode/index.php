<?php

$sURL = 'http://maps.googleapis.com/maps/api/geocode/json?';

$sAddress = '1450 Holbrook Road, Alvaston, Derby, DE24 0LW';

$sRequest = $sURL . 'address=' . urlencode($sAddress) . '&sensor=false&region=uk';

var_dump($sRequest);

$json = file_get_contents($sRequest);

var_dump($json);

?>
