<?php
$mysqli = new mysqli("localhost","sidmaindia_bssuser","z*lq5fUfxEGY");


// Check connection
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit;
}else{
    echo 'connected';
    exit;
}

phpinfo();

echo 'thank you';