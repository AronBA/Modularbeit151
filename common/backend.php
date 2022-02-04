<?php


function selectall(){
    $host = 'localhost';
    $database = 'db_m151_modularbeit';
    $username = 'root';
    $password = '';
    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }

    $query = 'SELECT * from user';
    $result = $mysqli->query($query);

    return $result;
}


function searchuser($user){
    $host = 'localhost';
    $database = 'db_m151_modularbeit';
    $username = 'root';
    $password = '';
    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }

    $query = 'SELECT * from user where username = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $user );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        return $row;
    }
}
function redirect($url)
{
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit();
}

function getcategroies(){
    $host = 'localhost';
    $database = 'db_m151_modularbeit';
    $username = 'root';
    $password = '';
    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }

    $query = 'SELECT name from categories';
    $result = $mysqli->query($query);

    return $result;
}



if (isset($_GET["deleteuser"])){
    deleteuser($_GET["deleteuser"]);
    redirect("adminspace/admin.php");
}

function deleteuser($id){
    $host = 'localhost';
    $database = 'db_m151_modularbeit';
    $username = 'root';
    $password = '';
    $mysqli = new mysqli($host, $username, $password, $database);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }
    $query = 'DELETE FROM db_m151_modularbeit.user WHERE id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $id );
    $stmt->execute();
}

