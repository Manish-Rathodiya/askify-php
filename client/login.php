<?php
$err_msg = isset($_SESSION['err_msg']) ? $_SESSION['err_msg'] : '';
unset($_SESSION['err_msg']);
?>
<div class="container login">
    <h1 class="heading">Login</h1>
    <?php
    if ($err_msg) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong> <?php echo $err_msg ?> </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>
    <form action="../server/requests.php" method="post">

        <div class="col-6 offset-sm-3 mb">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="enter email" required>
        </div>
        <div class="col-6 offset-sm-3 mb">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="enter password"
                required>
        </div>
        <button type="submit" name="login" class="btn btn-primary offset-sm-3 mb">Login</button>
    </form>

</div>