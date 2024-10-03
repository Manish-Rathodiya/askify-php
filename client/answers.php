<?php $success_msg = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : '';
unset($_SESSION['success_msg']);
?>

<?php if ($success_msg) { ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong> <?php echo $success_msg ?> </strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

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