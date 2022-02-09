<?php
include_once "DB_connection.php";

#returns an array of all categories
function getcategroies(){
    $mysqli = connection();
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_error . ') '. $mysqli->connect_error);
    }

    $query = 'SELECT name from categories';
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_all();

        $categories = array();
        for ($i = 0; $i < count($row); $i++){
            $categories[$i] = $row[$i][0];
        }
        mysqli_close($mysqli);
        return $categories;
    }
    mysqli_close($mysqli);
    return null;
}


#returns the users id
function getuserid($uname){
    $mysqli = connection();
    $query = 'SELECT id from user  where username = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $uname );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all();
        mysqli_close($mysqli);
        return $row[0][0];
    }
    mysqli_close($mysqli);
    return null;
}
function getcategorieid($cname){
    $mysqli = connection();
    $query = 'SELECT id from categories  where name = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $cname );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all();
        mysqli_close($mysqli);
        return $row[0][0];
    }
    mysqli_close($mysqli);
    return null;
}

#returns an array of all the users categories
function getusercategories($uid){
    $mysqli = connection();
    $query = 'SELECT * from user_has_categories left join categories c on c.id = user_has_categories.categories_id where user_id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $uid );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all();

        $categories = array();
       for ($i = 0; $i < count($row); $i++){
           $categories[$i] = $row[$i][3];
       }
        mysqli_close($mysqli);
        return $categories;
    }
    mysqli_close($mysqli);
    return null;
}

#this function echos all users which are found formatted as a table entry
function displaysearcheduser($uname){
    $likename = "%$uname%";
    $mysqli = connection();
    $query = 'SELECT * from user where username like ? and admin = 0';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $likename );
    $stmt->execute();
    $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $id = $row["id"];
                $firstname = $row["firstname"];
                $lastname = $row["lastname"];
                $username = $row["username"];

                $categories = implode(", ",getusercategories($id));
                echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$username</td>
        <td>$firstname</td>
        <td>$lastname</td>
        <td>$categories</td>
        <form action='' method='post'>
        <td><a class='btn btn-info'role='button' href='A_edituser.php?edituser=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_deleteuser.php?deleteuser=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
        } else {
            echo "0 results";
        }
    mysqli_close($mysqli);
}

#this function echos all users formatted as a table entry
function displayuser(){
    $mysqli = connection();
    $query = 'SELECT * from user where admin = 0';
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $id = $row["id"];
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];
            $username = $row["username"];

            $categories = implode(", ",getusercategories($id));
            echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$username</td>
        <td>$firstname</td>
        <td>$lastname</td>
        <td>$categories</td>
        <form action='' method='post'>
        <td><a class='btn btn-info'role='button' href='A_edituser.php?edituser=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_deleteuser.php?deleteuser=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
    } else {
        echo "0 results";
    }
    mysqli_close($mysqli);
}


