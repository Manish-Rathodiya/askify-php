<?php $success_msg = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : '';
unset($_SESSION['success_msg']);
?>

<?php if ($success_msg) { ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong> <?php echo $success_msg ?> </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

<div class="container list-group w-auto">
    <div class="row">
        <div class="col-8">
            <h1 class="heading">Questions</h1>
            <?php include "../common/db.php";
            $questions = $conn->prepare("SELECT * FROM questions WHERE id = ?");
            $questions->execute([$qid]);
            $row = $questions->fetch(PDO::FETCH_ASSOC);
            $cid = $row['category_id'];
            echo "<h4 class='question-title mb'> Question: " . $row['title'] . "</h4><p>" . $row['description'] . "</p>";
            include "../client/answers.php";
            ?>
            <form action="../server/requests.php" method="post">
                <input type="hidden" name="question_id" value="<?php echo $qid ?>">
                <textarea name="answer" class="form-control mb" placeholder="Your answer..."></textarea>
                <button class="btn btn-primary">Write Your Answer</button>
            </form>
        </div>
        <div class="col-4">
            <?php

            $categoryName = $conn->prepare("SELECT * FROM category WHERE id= ?");
            $categoryName->execute([$cid]);

            foreach ($categoryName as $row) {
                $catName = $row['name'];
                echo " <h1 class='heading'>" . ucfirst($catName) . "</h1>";
            }

            $category = $conn->prepare("SELECT * FROM questions WHERE category_id= ? AND id!=?");
            $category->execute([$cid, $qid]);
            foreach ($category as $row) {
                $id = $row['id'];
                $title = $row['title'];

                echo "<div class='question-list list-group-item'>
                <h4><a href='?q-id=$id'>$title</a></h4></div>";
            }

            ?>
        </div>
    </div>
</div>