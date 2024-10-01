<?php
include "../common/db.php";


session_start();
$success_msg = false;
$err_msg = false;

//Sign Up 
if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $place = trim($_POST['place']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($hashed_password) && !empty($place)) {
        $signup = $conn->prepare("INSERT INTO `users` (`id`, `username`, `email`, `password`, `place`) VALUES (null,?, ?, ?, ?)");

        $result = $signup->execute([$username, $email, $hashed_password, $place]);
        $id = $conn->lastInsertId();

        if ($result) {
            $success_msg = "New User Registered";
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $username;
            header("location: /askify/server");
        } else {
            $err_msg = "Registration Failed";
        }
    } else {
        $err_msg = "All fields are mandatory";
    }

    //Login
} else if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_id = 0;

    if (!empty($email) && !empty($password)) {
        $login = $conn->prepare("SELECT * FROM `users` WHERE `email`= ?");
        $login->execute([$email]);

        if ($login->rowCount() > 0) {
            $result = $login->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $result['password'];
            $user_id = $result['id'];
            $username = $result['username'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['name'] = $username;
                // $_SESSION['success_msg'] = "Login successful!";
                header("location: /askify/server");
                exit();
            } else {
                $_SESSION['err_msg'] = "Incorrect password.";
            }
        } else {
            $_SESSION['err_msg'] = "No user found with that username.";
        }

    } else {
        $_SESSION['err_msg'] = "Please fill in all fields.";
    }
} else if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location: /askify/server");
    exit();

    //Ask a question
} elseif (isset($_POST['ask'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);
    $user_id = trim($_SESSION['user_id']);

    if (!empty($title) && !empty($description) && !empty($category_id) && !empty($user_id)) {
        $question = $conn->prepare("INSERT INTO `questions` (`id`, `title`, `description`, `category_id`, `user_id`) VALUES (NULL, ?,?,?,?)");

        $result = $question->execute([$title, $description, $category_id, $user_id]);
        $id = $conn->lastInsertId();

        if ($result) {
            $success_msg = "Question Added Successfully";
            header("location: /askify/server");
        } else {
            $err_msg = "Question not added";
        }
    } else {
        $err_msg = "All fields are mandatory";
    }

    //Answer a question
} else if (isset($_POST['answer'])) {
    $answer = trim($_POST['answer']);
    $question_id = trim($_POST['question_id']);
    $user_id = trim($_SESSION['user_id']);

    if (!empty($answer) && !empty($question_id) && !empty($user_id)) {
        $answerResult = $conn->prepare("INSERT INTO `answers` (`id`, `answer`, `question_id`, `user_id`) VALUES (NULL, ?,?,?)");
        $result = $answerResult->execute([$answer, $question_id, $user_id]);

        if ($result) {
            $success_msg = "Answer submitted successfully";
            header("location: /askify/server?q-id=$question_id");
        } else {
            $err_msg = "Answer is not submitted";
        }

    } else {
        $err_msg = "All fields are mandatory";
    }

    //Delete my question
} else if (isset($_GET['delete'])) {
    $qid = $_GET['delete'];
    $delete = $conn->prepare("DELETE FROM questions WHERE id=?");
    $result = $delete->execute([$qid]);
    if ($result) {
        header("location: /askify/server");
        $success_msg = "Question deleted successfully";

    } else {
        $err_msg = "Question not deleted";
    }
}