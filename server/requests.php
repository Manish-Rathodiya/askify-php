<?php
include "../common/db.php";
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Message with redirect
function redirectWithMessage($url, $message, $type)
{
    if ($type === 'success') {
        $_SESSION['success_msg'] = $message;
    } else {
        $_SESSION['err_msg'] = $message;
    }
    header("location: $url");
    exit();
}

function displayMessage()
{
    if (isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])) {
        $message = $_SESSION['success_msg'];
        echo '<div class="alert bg-success text-white alert-dismissible fade show" role="alert">
    <strong>' . htmlspecialchars($message) . '</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
        unset($_SESSION['success_msg']); // Clear the message after displaying
    } elseif (isset($_SESSION['err_msg']) && !empty($_SESSION['err_msg'])) {
        $message = $_SESSION['err_msg'];
        echo '<div class="alert bg-danger text-white  alert-dismissible fade show" role="alert">
    <strong>' . htmlspecialchars($message) . '</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
        unset($_SESSION['err_msg']); // Clear the message after displaying
    }
}


function registerUser($conn, $username, $email, $password, $place)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($username) && !empty($email) && !empty($hashed_password) && !empty($place)) {
        $signup = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`, `place`) VALUES (:username, :email,
:password, :place)");

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
    return $user; //Valid user
}

function loginUser($user)
{
    $_SESSION["loggedin"] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['username'];
    $success_msg = "Login Succesfully";
    redirectWithMessage('/askify/server', $success_msg, 'success');
    exit();
}

function ask($conn, $title, $description, $category_id, $user_id)
{

    if (!empty($title) && !empty($description) && !empty($category_id) && !empty($user_id)) {
        $question = $conn->prepare("INSERT INTO `questions` (`title`, `description`, `category_id`, `user_id`) VALUES (:title,
:description, :category_id, :user_id)");

        $question->bindValue(':title', $title, PDO::PARAM_STR);
        $question->bindValue(':description', $description, PDO::PARAM_STR);
        $question->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $question->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $result = $question->execute();
        $id = $conn->lastInsertId();

        // $connid = $conn->lastInsertId(); 
        // $idObj = new stdClass(); 
        // $idObj->property = $connid; 
        return ['result' => $result, 'id' => $id];
        // return ['result'->$result, 'id'->$id];

    }
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
        redirectWithMessage('/askify/server', $success_msg, 'success');
    } else {
        redirectWithMessage('/askify/server/?signup=true', $result, 'error');
    }

    //Login
} else if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = verifyLogin($conn, $email, $password);

    if (is_array($result)) {
        loginUser($result);
    } else {
        redirectWithMessage('/askify/server/?login=true', $result, 'error');
    }

    //Log out
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

    $result = ask($conn, $title, $description, $category_id, $user_id);

    if ($result) {
        $success_msg = "Question Added Successfully";
        redirectWithMessage('/askify/server', $success_msg, 'success');
    } else {
        $err_msg = "Question not added";
        redirectWithMessage('/askify/server', $err_msg, 'error');
    }
}

//Answer a question
else if (isset($_POST['answer'])) {
    $answer = trim($_POST['answer']);
    $question_id = trim($_POST['question_id']);
    $user_id = trim($_SESSION['user_id']);

    if (!empty($answer) && !empty($question_id) && !empty($user_id)) {
        $answerResult = $conn->prepare("INSERT INTO `answers` (`answer`, `question_id`, `user_id`) VALUES (?,?,?)");
        $result = $answerResult->execute([$answer, $question_id, $user_id]);

        if ($result) {
            $success_msg = "Answer submitted successfully";
            redirectWithMessage("/askify/server?q-id=$question_id", $success_msg, 'success');
        } else {
            $err_msg = "Answer is not submitted";
            redirectWithMessage('/askify/server', $err_msg, 'error');
        }

    } else {
        $err_msg = "All fields are mandatory";
        redirectWithMessage('/askify/server', $err_msg, 'error');
    }

    //Delete my question
} else if (isset($_GET['delete'])) {
    $qid = $_GET['delete'];

    //Start the transaction 
    $conn->beginTransaction();

    // Prepare and execute the delete query for the answers table 
    $deleteAnswers = $conn->prepare("DELETE FROM answers WHERE question_id = ?");
    $deleteAnswers->execute([$qid]);

    // Prepare and execute the delete query for the questions table 
    $deleteQuestions = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $deleteQuestions->execute([$qid]);

    // Commit the transaction 
    if ($conn->commit() === true) {
        $success_msg = "Question deleted successfully";
        redirectWithMessage('./', $success_msg, 'success');
    } else {
        $err_msg = "Question not deleted";
        redirectWithMessage('./', $err_msg, 'error');
    }
}