<?php
include "dbconnection.php";


function searchuser($user){
    $query = 'SELECT * from user where username = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $user );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    }
    return null;
}





$query = 'SELECT * from user';
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if(isset($_POST['searchsub'])) {
            if (!empty($_POST["search"])) {
                $username = trim($_POST['search']);
                $query = 'SELECT * from user where username = ?';
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("s", $user );
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $srow = $result->fetch_assoc();

                }

                if (!empty($srow)) {
                    $id = $srow["id"];
                    $firstname = $srow["firstname"];
                    $lastname = $srow["lastname"];
                    $admin = $srow["admin"];
                    $categories = $srow["categories"];
                    $username = $srow["username"];
                    echo "
                <tr>
                 <th scope='row'>$id</th>
                 <td>$username</td>
                <td>$firstname</td>
                 <td>$lastname</td>
                <td>$categories</td>
                <td>$admin</td> 
                <td><a class='btn btn-info'role='button' href='functions.php?edit=$id'> <div class='postDelete'>Edit</div></a></td>
                <td><a class='btn btn-danger 'role='button' href='../backend/dbaccess/deleteuser.php?deleteuser=$id'> <div>Delete</div></a></td>

        </tr> ";
                    break;
                }
            }
        }
        $id = $row["id"];
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $admin = $row["admin"];
        $categories = $row["categories"];
        $username = $row["username"];
        echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$username</td>
        <td>$firstname</td>
        <td>$lastname</td>
        <td>$categories</td>
        <td>$admin</td>  
        <form action='' method='post'>
        <td><a class='btn btn-info'role='button' href='edituser.php?edituser=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../backend/dbaccess/deleteuser.php?deleteuser=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
} else {
    echo "0 results";
}

?>