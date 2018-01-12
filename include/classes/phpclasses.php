<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//////////////////////user
class user {

    public $id = null;
    public $name;
    public $email;
    public $password;
    public $role;

    public function __construct($name, $email, $password, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getemail() {
        return $this->email;
    }

    public function setemail($email) {
        $this->email = $email;
    }

    public function getpassword() {
        return $this->password;
    }

    public function setpassword($password) {
        $this->password = $password;
    }

    public function getrole() {
        return $this->role;
    }

    public function setrole($role) {
        $this->role = $role;
    }

    public function checkaccountinput() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST["name"])) {

                $this->setname(test_input($_POST["name"]));

                if ($this->name == "") {
                    return "name can not be empty <br>";
                }
            }


            if (isset($_POST["email"])) {
                $this->setemail(test_input($_POST["email"]));
                if ($this->email == "") {
                    return "email can not be empty";
                }
            }

            if (isset($_POST["pwd"])) {
                $this->setpassword(test_input($_POST["pwd"]));
                if ($this->password == "") {
                    return "password can not be empty";
                }
            }
            if (isset($_POST["cpwd"])) {
                $this->setpassword(test_input($_POST["cpwd"]));
                if ($this->password == "") {
                    return "password can not be empty";
                }
            }

            if ($_POST['pwd'] != $_POST['cpwd']) {
                return "passwords do not match";
            }
            if (isset($_POST["role"])) {
                $this->setrole(test_input($_POST["role"]));
            }


            if ($this->checkifaccountexist() == false) {
                return $this->addaccount();
            } else {
                return "the username or the email that you have enterd is al ready exist! Please choose another one";
            }
        }
    }

    public function checkifaccountexist() {
        $sql = "SELECT * FROM `user` WHERE `email`='.$this->email.'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            $connection->conn->close();
            return true;
        } else {
            return false;
        }
    }

    public function addaccount() {

        $sql = "INSERT INTO `user`(`name`, `email`, `password`, `role`) VALUES";
        $sql .= " ('$this->name','$this->email','$this->password','$this->role')";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        session_start();
        $_SESSION["name"] = $this->name;
        $connection->conn->close();
        header("location: login.php");
    }

    public function signin() {

        $sql = "SELECT * FROM `user` WHERE `email`= BINARY '$this->email' AND `password`= BINARY '$this->password'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if (isset($result)) {
            if ($result->num_rows <= 0) {
                return "email or password does not match";
            } else {
                session_start();
                $row = $result->fetch_assoc();
                $_SESSION["name"] = $row['name'];
                $connection->conn->close();
                header("location: index.php");
            }
        }
    }

    public function checksignin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["email"])) {
                $this->setemail(test_input($_POST["email"]));
                if ($this->email == "") {
                    return "email can not be empty <br>";
                }
            }
            if (isset($_POST["psw"])) {
                $this->setpassword(test_input($_POST["psw"]));
                if ($this->password == "") {
                    return "password can not be empty";
                }
            }
            if ($this->email != "" && $this->password != "") {
                return $this->signin();
            }
        }
    }

}

//////////////////////course
class course {

    public $id;
    public $name;
    public $description;

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getdescription() {
        return $this->description;
    }

    public function setdescription($description) {
        $this->description = $description;
    }

    public function checkcourse() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"])) {
                $this->setname(test_input($_POST["name"]));
                if ($this->name == "") {
                    return "Course name can not be empty <br>";
                }
            }
            if (isset($_POST["description"])) {
                $this->setdescription(test_input($_POST["description"]));
                if ($this->description == "") {
                    return "description can not be empty <br>";
                }
            }
        }

        if ($this->checkifcourseexist() == false) {
            $this->addcourse();
        } else {
            return "course is al ready exist! Please choose another name";
        }
    }

    public function checkifcourseexist() {
        $sql = "SELECT * FROM `course` WHERE `name`='$this->name'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            $connection->conn->close();
            return true;
        } else {
            return false;
        }
    }

    public function addcourse() {
        $sql = "INSERT INTO `course`(`name`, `description`) VALUES";
        $sql .= " ('$this->name','$this->description')";
        $connection = new Database();
        $connection->conn->query($sql);
        $connection->conn->close();
    }

    static public function showcourse() {
        $sql = "SELECT * FROM `course`";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=course-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Course Name</th>";
        echo "<th>Course Description</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><button onclick=del(".$row['id'].") class='glyphicon glyphicon-trash' id=cousredelete title=Delete></button></td>";
            echo "</tr>";
           }
        echo "</table>";
        $connection->conn->close();
    }
}

//////////////////////subcourse
class subcourse {

    public $id;
    public $name;
    public $desc;
    public $courseID;

    public function __construct($name, $desc) {
        $this->name = $name;
        $this->desc = $desc;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getdesc() {
        return $this->email;
    }

    public function setdesc($email) {
        $this->email = $email;
    }

    public function getcourseID() {
        return $this->courseID;
    }

    public function setcourseID($courseID) {
        $this->courseID = $courseID;
    }

}

//////////////////////lesson
class lesson {

    public $id;
    public $name;
    public $description;
    public $subcourseID;

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getdescription() {
        return $this->description;
    }

    public function setdescription($description) {
        $this->description = $description;
    }

    public function getsubcourseID() {
        return $this->subcourseID;
    }

    public function setsubcourseID($subcourseID) {
        $this->subcourseID = $subcourseID;
    }
    //start check lesson
     public function checklesson() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"])) {
                $this->setname(test_input($_POST["name"]));
                if ($this->name == "") {
                    return "Lesson name can not be empty <br>";
                }
            }
            if (isset($_POST["description"])) {
                $this->setdescription(test_input($_POST["description"]));
                if ($this->description == "") {
                    return "description can not be empty <br>";
                }
            }
        }

        if ($this->checkiflessonexist() == false) {
            $this->addlesson();
        } else {
            return "lesson is al ready exist! Please choose another name";
        }
    }

    public function checkiflessonexist() {
        $sql = "SELECT * FROM `lesson` WHERE `name`='$this->name'";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        if ($result->num_rows >= 1) {
            $connection->conn->close();
            return true;
        } else {
            return false;
        }
    }

    public function addlesson() {
        $sql = "INSERT INTO `lesson`(`name`, `description`) VALUES";
        $sql .= " ('$this->name','$this->description')";
        $connection = new Database();
        $connection->conn->query($sql);
        $connection->conn->close();
    }

    static public function showlesson() {
        $sql = "SELECT * FROM `lesson`";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=lesson-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Lesson Name</th>";
        echo "<th>Lesson Description</th>";
        echo "<th>Delete</th>";
        echo "</tr>";
        for ($x = 0; $x < $result->num_rows; $x++) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><button onclick=del(".$row['id'].") class='glyphicon glyphicon-trash' id=lessondelete title=Delete></button></td>";
            echo "</tr>";
           }
        echo "</table>";
        $connection->conn->close();
    }

}

//////////////////////section
class section {

    public $id;
    public $name;
    public $letter;
    public $content;
    public $lessonID;

    public function __construct($name, $letter, $content) {
        $this->name = $name;
        $this->desc = $letter;
        $this->name = $content;
    }

    public function getid() {
        return $this->id;
    }

    public function setid($id) {
        $this->id = $id;
    }

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function getletter() {
        return $this->letter;
    }

    public function setletter($letter) {
        $this->letter = $letter;
    }

    public function getcontent() {
        return $this->content;
    }

    public function setcontent($content) {
        $this->content = $content;
    }

    public function getlessonID() {
        return $this->lessonID;
    }

    public function lessonID($lessonID) {
        $this->lessonID = $lessonID;
    }

}

?>