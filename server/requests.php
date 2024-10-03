<?php
include "../common/db.php";
session_start();
$success_msg = false;
$err_msg = false;

//Message with redirection
function redirectWithSuccess($url, $successMessage)
{
    $_SESSION['success_msg'] = $successMessage;
    header("location: $url");
    exit();
}
function redirectWithError($url, $errorMessage)
{
    $_SESSION['err_msg'] = $errorMessage;
    header("location: $url");
    exit();
}

function registerUser($conn, $username, $email, $password, $place)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($hashed_password) && !empty($place)) {
        $signup = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`, `place`) VALUES (:username, :email, :password, :place)");

        $signup->bindValue(':username', $username, PDO::PARAM_STR);
        $signup->bindValue(':email', $email, PDO::PARAM_STR);
        $signup->bindValue(':password', $hashed_password, PDO::PARAM_STR);
        $signup->bindValue(':place', $place, PDO::PARAM_STR);
        $result = $signup->execute();

        return $result ? $conn->lastInsertId() : false;
    } else {
        return "All fields are mandatory";
    }
}

function getUserByEmail($conn, $email)
{
    $login = $conn->prepare("SELECT * FROM `users` WHERE `email`= :email");
    $login->bindValue(':email', $email, PDO::PARAM_STR);
    $login->execute();
    return $login->fetch(PDO::FETCH_ASSOC);
}


function verifyLogin($conn, $email, $password)
{
    if (empty($email) || empty($password)) {
        return "Please fill in all fields.";
    }
    $user = getUserByEmail($conn, $email);

    if (!$user) {
        return "No user found with that email.";
    }
    if (!password_verify($password, $user['password'])) {
        return "Incorrect password";
    }

    return $user;  //Valid user
}

function loginUser($user)
{
    $_SESSION["loggedin"] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['username'];
    $success_msg = "Login Succesfully";
    redirectWithSuccess('/askify/server', $success_msg);
    exit();
}

//Sign Up 
if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $place = trim($_POST['place']);

    $result = registerUser($conn, $username, $email, $password, $place);

    if (is_numeric($result)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $result;
        $_SESSION['name'] = $username;
        $success_msg = 'Registration Successfull';
        redirectWithSuccess('/askify/server', $success_msg);
    } else {
        redirectWithError('/askify/server/?signup=true', $result);
    }

    //Login
} else if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = verifyLogin($conn, $email, $password);

    if (is_array($result)) {
        loginUser($result);
    } else {
        redirectWithError('/askify/server/?login=true', $result);
    }

} else if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: /askify/server");
    exit();

    //Ask a question
} elseif (isset($_POST['ask'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);
    $user_id = trim($_SESSION['user_id']);

    if (!empty($title) && !empty($description) && !empty($category_id) && !empty($user_id)) {
        $question = $conn->prepare("INSERT INTO `questions` (`title`, `description`, `category_id`, `user_id`) VALUES (:title, :description, :category_id, :user_id)");

        $question->bindValue(':title', $title, PDO::PARAM_STR);
        $question->bindValue(':description', $description, PDO::PARAM_STR);
        $question->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $question->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $result = $question->execute();
        $id = $conn->lastInsertId();

        if ($result) {
            $success_msg = "Question Added Successfully";
            redirectWithSuccess('/askify/server', $success_msg);
        } else {
            $err_msg = "Question not added";
            redirectWithError('/askify/server', $err_msg);
        }
    } else {
        $err_msg = "All fields are mandatory";
        redirectWithError('/askify/server', $err_msg);
    }

    //Answer a question
} else if (isset($_POST['answer'])) {
    $answer = trim($_POST['answer']);
    $question_id = trim($_POST['question_id']);
    $user_id = trim($_SESSION['user_id']);

    if (!empty($answer) && !empty($question_id) && !empty($user_id)) {
        $answerResult = $conn->prepare("INSERT INTO `answers` (`answer`, `question_id`, `user_id`) VALUES (?,?,?)");
        $result = $answerResult->execute([$answer, $question_id, $user_id]);

        if ($result) {
            $success_msg = "Answer submitted successfully";
            redirectWithSuccess("/askify/server?q-id=$question_id", $success_msg);
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
        $success_msg = "Question deleted successfully";
        redirectWithSuccess('./', $success_msg);
    } else {
        $err_msg = "Question not deleted";
    }
}