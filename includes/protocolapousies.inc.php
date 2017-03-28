<?php
// ρυθμίσεις σλυνδεσης στη mysql
$host = "localhost";
$username = "root";
$password = "";

// ρυθμίσεις για εισαγωγή στο ηλεκτρονικό πρωτόκολλο
//---------------------------------------------------------
// το όνομα της βάσης με το πρωτόκολλο απουσιών
$database = "";
// βρείτε το "id" του χρήστη στο table "users"
$entryby = 0;
$diekperaivsi = 'ΓΡΑΜΜΑΤΕΑΣ'; //να αλλάξει ανάλογα
//-----------------------------------------------------

$protocol_con = false;

if (isset($database)){
    // έλεγχος της σύνδεσης με mysql
    $link = mysqli_connect ( $host, $username, $password, $database );
    if (! mysqli_connect_errno()) {
        $protocol_con = true;
    }

    if ($link) {
	    mysqli_query ( $link, "SET character_set_connection=utf8" );
	    mysqli_query ( $link, "SET character_set_client=utf8" );
	    mysqli_query ( $link, "SET character set 'utf8'" );
	    mysqli_query ( $link, "SET NAMES 'utf8'" );
    }
}

?>
