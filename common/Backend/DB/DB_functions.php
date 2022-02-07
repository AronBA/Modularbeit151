<?php
include_once "DB_connection.php";


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
        return $categories;
    }
    return null;
}
function displaysearcheduser($uname){
    $uname = "%$uname%";
    $mysqli = connection();
    $query = 'SELECT * from user where username like ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $uname );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["id"];
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $admin = $row["admin"];

        $categories = implode(", ",getusercategories($id));
        $username = $row["username"];
        echo "
                <tr>
                 <th scope='row'>$id</th>
                 <td>$username</td>
                <td>$firstname</td>
                 <td>$lastname</td>
                <td>$categories</td>
                <td>$admin</td> 
                <td><a class='btn btn-info' role='button' href='functions.php?edit=$id'> <div class='postDelete'>Edit</div></a></td>
                <td><a class='btn btn-danger' role='button' href='../Backend/DB/DB_deleteuser.php?deleteuser=$id'> <div>Delete</div></a></td>

        </tr> ";
    }




}
function displayuser(){
    $mysqli = connection();
    $query = 'SELECT * from user';
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {


            $id = $row["id"];
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];
            $admin = $row["admin"];
            $username = $row["username"];
            $categories = implode(", ",getusercategories($id));
            echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$username</td>
        <td>$firstname</td>
        <td>$lastname</td>
        <td>$categories</td>
        <td>$admin</td>  
        <form action='' method='post'>
        <td><a class='btn btn-info'role='button' href='A_edituser.php?edituser=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/DB_deleteuser.php?deleteuser=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
    } else {
        echo "0 results";
    }

}


