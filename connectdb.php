<?php

try {
    $pdo = new PDO( "mysql:host=localhost;dbname=pos_db", "root", "" );
//    echo "connection successful";
} 
catch( PDOException $f ) {
    echo $f->getmessage();
}

?>