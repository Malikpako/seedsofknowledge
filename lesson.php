<?php
include 'inti.php';
include $tem . 'header.php';
?>

<?php
if (isset($_SESSION["name"]) && $_SESSION["name"] != "") {

    include $tem . 'loginnav.php';
} else {
    header("location: login.php");
}
?>
<div id="lesson-container">

    <div id="lesson-course">

        <div id="lesson-course-inputs">
            <form id="lesson" action="lesson.php" method="POST">
                <div class="lesson-inputs">
                     <input id="subcoursename" type="text" name="name" placeholder="Sub Course">
                     <br>
                    <input id="lessonname" type="text" name="name" placeholder="Lesson Name">
                    <input id="lessondesc" type="text" name="description" placeholder="Lesson Description">
                    <input id="cousreoperatopn" type="hidden" name="operation" value="">
                </div>
                <div class="lesson-buttons">
                    <button type="reset"  class="glyphicon glyphicon-repeat" id="lessonclear" title="Clear"></button>
                    <?php
                    $msg="";
                    $lesson = new lesson("", "", "");
                    $msg = $lesson->checklesson();
                    ?>
                    <button id="lessonadd" class=" glyphicon glyphicon-plus" title="Add"></button>
<!--                    <button id="cousreedit" class="glyphicon glyphicon-pencil" title="Edit"></button>-->
          
                    
                    <span class=error>
                        <?php
                        if (isset($msg)!="") {
                            echo $msg;
                        }
                        ?></span> 
                </div>
            </form>
        </div>

        <div id="lesson-course-table">
            
            <?php lesson::showlesson();?>
        </div>
    </div>


</div>
    
<?php
        function showsubcourse() {
        $sql = "SELECT * FROM `subcourse`";
        $connection = new Database();
        $result = $connection->conn->query($sql);
        echo "<table id=subcourse-table>";
        echo "<colgroup>";
        echo "<col>";
        echo "<col>";
        echo "<col>";
        echo "</colgroup>";
        echo "<tr>";
        echo "<th>Sub Course Name</th>";
        echo "<th>Sub Course Description</th>";
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




?>