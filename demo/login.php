<!--
This is just a very simple demo that show how to login with the library and use the groups;
-->

<html>
<head>
    <title>BrigthcenterSDK</title>
</head>
<body style="background-color: orange;">

    <?php
    function login(){
        if(empty($_POST['username']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }

        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        include('../bcconnect.php');

        $connector = new BCConnect();

        $groups = $connector->getGroupsOfTeacher($username, $password);
        return $groups;
    }
    ?>




    <div id="groups" style="width: 350px; height: 200px; margin: auto; padding: auto; background-color: green; margin-top: 50px; border-radius: 5px;">
        <h1>Groups<h2>
        
        <?php
            $groups = login();
            for ($i=0; $i < count($groups) ; $i++) { 
                echo '<select name="students" style="font-size: 30px;">';
                $students = $groups[$i]->students;
                for ($j=0; $j < count($students) ; $j++) { 
                    echo '<option value"' . $students[$j]->firstName .'">' . $students[$j]->firstName . ': ' . $students[$j]->id . '</option>' ;
                }
                
                echo '</select>';
                echo "<br>";
            }
        ?>
        
    </div>
</body>
</html>
<!-- 
       $results = $connector->getResultsOfStudentForAssessment("123-456-789", "4");
        var_dump($results);
        echo "<br><BR><BR>";


        echo $connector->postResultOfStudentForAssessment("1", "3", "2", 3, 5, CompletionStatus::INCOMPLETE, 1234566); -->