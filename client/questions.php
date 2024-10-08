<?php include '../server/requests.php';
displayMessage(); ?>

<div class="container list-group w-auto">
    <div class="row">
        <div class="col-8">
            <h1 class="heading">Questions</h1>
            <?php include "../common/db.php";
            $uid = $_SESSION['user_id'] ?? null;

            if (isset($_GET['c-id'])) {
                $questions = $conn->prepare("SELECT * FROM questions WHERE `category_id`= ?");
                $questions->execute([$cid]);
            } else if (isset($_GET['u-id'])) {
                $questions = $conn->prepare("SELECT * FROM questions WHERE `user_id`= ?");
                $questions->execute([$uid]);
            } else if (isset($_GET['latest'])) {
                $questions = $conn->query("SELECT * FROM questions ORDER BY id desc");
            } else if (isset($_GET['search'])) {
                $questions = $conn->query("SELECT * FROM `questions` WHERE `title` LIKE '%$search%'");
            } else {
                $questions = $conn->query("SELECT * FROM questions");
            }

            // Check if the current page is "My Questions"
            $isMyQuestionsPage = isset($_GET['u-id']) && $_GET['u-id'] == $uid;

            //Adding a delete button for my questions
            while ($data = $questions->fetch(PDO::FETCH_ASSOC)) {
                $title = $data['title'];
                $id = $data['id'];
                echo "<div class='question-list list-group-item'>
                <h4 class='my-question'> <a href='?q-id=$id'>$title</a> ";
                echo $isMyQuestionsPage ? "<a href='../server/requests.php?delete=$id'>Delete</a>" : null;
                echo "</h4></div>";
            }
            ?>
        </div>
        <div class="col-4">
            <?php include "category_list.php"; ?>
        </div>
        </di>
    </div>