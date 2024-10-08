<div class="container mb-3">
    <div class="offset-sm-1">
        <h5>Answers: </h5>
        <?php
        $displayAnswers = $conn->prepare("SELECT * FROM answers WHERE question_id = ?");
        $displayAnswers->execute([$qid]);
        $result = $displayAnswers->fetchAll();
        foreach ($result as $row) {
            $answer = $row['answer'];
            echo "<div class='row'><p class='answer-wrapper'>$answer</p></div>";
        }
        ?>
    </div>
</div>