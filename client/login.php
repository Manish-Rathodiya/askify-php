<div class="container login">
    <h1 class="heading">Login</h1>

    <?php include '../server/requests.php';
    displayMessage(); ?>

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