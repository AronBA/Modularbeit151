<?php
require_once "DB_connection.php";

#gibt alle Kategorien zurück
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


#gibt die user id des user zurück
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

#gibt alle kategorien des users zurück
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

#funkion welche alle gesuchten User als tablle zurückgibt
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
        <form action='' method='get'>
        <td><a class='btn btn-info'role='button' href='A_edituser.php?edituser=$id'> <div>Edit</div></a></td></form>
        <form action='' method='post'>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_delete.php?deleteuser=$id'> <div>Delete</div></a></td></form>
        
        </tr>
        
        
        
     ";}
        } else {
            echo "0 results";
        }
    mysqli_close($mysqli);
}

#funkion welche alle  User als tablle zurückgibt
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
        <td><a class='btn btn-info'role='button' href='A_edituser.php?userid=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_delete.php?deleteuser=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
    } else {
        echo "0 results";
    }
    mysqli_close($mysqli);
}

#funkion welche alle  todos als tablle zurückgibt
function displaytodo($user){

    $uid = getuserid($user);
    $query = 'SELECT t.id, t.name as todoname, t.text, t.datebegin, t.datefinish, t.progress, t.priorety, u.username, t.user_id, c.name as categorie, t.archive from todo as t join user as u on u.ID = t.user_id join categories as c on categories_id = t.categories_id join user_has_categories uhc on c.id = uhc.categories_id where uhc.user_id = ? and archive = 0 order by t.priorety desc';

    $mysqli = connection();
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $priorety = $row["priorety"];
            $name = $row["todoname"];
            $text = $row["text"];
            $datebegin = $row["datebegin"];
            $datefinish = $row["datefinish"];
            $progress = $row["progress"];
            $username = $row["username"];
            $categorie = $row["categorie"];
            $timeleft = calculateTime($datefinish,$datebegin);

            #wenn der ersteller eingloggt ist kann er bearbeiten, anstonsten nicht
            if ($username == $_SESSION["username"]){
                echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$priorety</td>
        <td>$name</td>
        <td>$text</td>
        <td>$datebegin</td>
        <td id='date'>$timeleft</td>
        <td><div class='progress'>
  <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar'
  aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width:$progress%'>
    $progress% Complete (success)
  </div>
</div></td>
        <td>$categorie</td>
        <td>$username</td>
                
        <form action='' method='post'>
        <td><a class='btn btn-info'role='button' href='U_edittodo.php?edittodo=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-primary 'role='button' href='../Backend/DB/GET_delete.php?archivetodo=$id'> <div>Archive</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_delete.php?deletetodo=$id'> <div>Delete</div></a></td>

        </form>
        </tr>
        
        
     ";

            } else {
                echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$priorety</td>
        <td>$name</td>
        <td>$text</td>
        <td>$datebegin</td>
        <td id='date'>$timeleft</td>
        <td><div class='progress'>
  <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar'
  aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width:$progress%'>
    $progress% Complete (success)
  </div>
</div></td>
        <td>$categorie</td>
        <td>$username</td>
                
        <form action='' method='post'>
        <td><a class='btn btn-info disabled 'role='button' href='U_edittodo.php?edittodo=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-primary disabled 'role='button' href='../Backend/DB/GET_delete.php?archivetodo=$id'> <div>Archive</div></a></td>
        <td><a class='btn btn-danger disabled  'role='button' href='../Backend/DB/GET_delete.php?deletetodo=$id'> <div>Delete</div></a></td>

        </form>
        </tr>
        
        
     ";

            }
            }
    } else {
        echo "0 results";
    }
    mysqli_close($mysqli);
}

//funktion welche alle gesuchten todos als tabble ausgibt
function displaysearchedtodo($user, $todoname){
    $likename = "%$todoname%";
    $uid = getuserid($user);
    $query = 'SELECT t.id, t.name as todoname, t.text, t.datebegin, t.datefinish, t.progress, t.priorety, u.username, t.user_id, c.name as categorie,c.id , t.archive from todo as t join user as u on u.ID = t.user_id join categories as c on categories_id = t.categories_id join user_has_categories uhc on c.id = uhc.categories_id where uhc.user_id = ? and t.name like ? order by t.priorety desc';

    $mysqli = connection();
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss",$uid,$likename);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $priorety = $row["priorety"];
            $name = $row["todoname"];
            $text = $row["text"];
            $datebegin = $row["datebegin"];
            $datefinish = $row["datefinish"];
            $progress = $row["progress"];
            $username = $row["username"];
            $categorie = $row["categorie"];
            $timeleft = calculateTime($datefinish,$datebegin);

            //nur wenn der ersteller eingeloggt ist kann er es bearbeiten
            if ($username == $_SESSION["username"]){
                echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$priorety</td>
        <td>$name</td>
        <td>$text</td>
        <td>$datebegin</td>
        <td id='date'>$timeleft</td>
        <td><div class='progress'>
  <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar'
  aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width:$progress%'>
    $progress% Complete (success)
  </div>
</div></td>
        <td>$categorie</td>
        <td>$username</td>
                
        <form action='' method='post'>
        <td><a class='btn btn-info'role='button' href='U_edittodo.php?edittodo=$id'> <div>Edit</div></a></td>
        
        <td><a class='btn btn-primary 'role='button' href='../Backend/DB/GET_delete.php?archivetodo=$id'> <div>Archive</div></a></td>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_delete.php?deletetodo=$id'> <div>Delete</div></a></td>

        </form>
        </tr>
        
        
     ";

            } else {
                echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$priorety</td>
        <td>$name</td>
        <td>$text</td>
        <td>$datebegin</td>
        <td id='date'>$timeleft</td>
        <td><div class='progress'>
  <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar'
  aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width:$progress%'>
    $progress% Complete (success)
  </div>
</div></td>
        <td>$categorie</td>
        <td>$username</td>
                
        <form action='' method='post'>
        <td><a class='btn btn-info disabled 'role='button' href='U_edittodo.php?edittodo=$id'> <div>Edit</div></a></td>
        <td><a class='btn btn-primary disabled 'role='button' href='../Backend/DB/GET_delete.php?archivetodo=$id'> <div>Archive</div></a></td>
        <td><a class='btn btn-danger disabled  'role='button' href='../Backend/DB/GET_delete.php?deletetodo=$id'> <div>Delete</div></a></td>

        </form>
        </tr>
        
        
     ";

            }
        }
    } else {
        echo "0 results";
    }
    mysqli_close($mysqli);
}

//funktion welche alle kategorien als tablle ausgibt
function displaycategories(){
    $mysqli = connection();
    $query = 'SELECT * from categories ';
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $id = $row["id"];
            $name = $row["name"];
            echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$name</td>
  
        <form action='' method='post'>
        <td><a class='btn btn-danger' role='button' href='../Backend/DB/GET_delete.php?deletecategorie=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
    } else {
        echo "0 results";
    }
    mysqli_close($mysqli);
}

//funktion welche alle gesuchten kategorien als tabelle zurückgibt
function displaysearchedcategorie($search){
    $likename = "%$search%";
    $query = 'SELECT * from categories where name like ?';
    $mysqli = connection();
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$likename);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $id = $row["id"];
            $name = $row["name"];
            echo "

        <tr>
        <th scope='row'>$id</th>
        <td>$name</td>
  
        <form action='' method='post'>
        <td><a class='btn btn-danger 'role='button' href='../Backend/DB/GET_delete.php?deletecategorie=$id'> <div>Delete</div></a></td>
        </form>
        </tr>
        
        
        
     ";}
    } else {
        echo "0 results";
    }
    mysqli_close($mysqli);
}

//funktoin welche diffrenz zwischen zwei daten ausrechnet
function calculateTime($date1, $date2){

    $datetime1 = date_create($date1);
    $datetime2 = date_create($date2);
    $interval = date_diff($datetime1, $datetime2);

    // Printing result in years & months format
    return $interval->format('%y years %m months %d days');
}

