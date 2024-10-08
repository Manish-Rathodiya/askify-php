<div class="container">
    <h1 class="heading">Sign Up</h1>

    <?php include '../server/requests.php';
    displayMessage(); ?>

    <form action="../server/requests.php" method="post">
        <div class="col-6 offset-sm-3 mb">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="enter username" required>
        </div>
        <div class="col-6 offset-sm-3 mb">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="enter email" required>
        </div>
        <div class="col-6 offset-sm-3 mb">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="enter password"
                required>
        </div>
        <div class="col-6 offset-sm-3 mb">
            <label for="place" class="form-label">Place</label>
            <input type="text" class="form-control" id="place" name="place" placeholder="enter place" required>
        </div>
        <button type="submit" name="signup" class="btn btn-primary offset-sm-3 mb">Sign Up</button>
    </form>

</div>