<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img class="navbar-logo" src="../public/askify.jfif" alt="logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./">Home</a>
                </li>
                <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="?login=true">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="?signup=true">Sign Up</a>
                    </li>
                <?php } else { ?>

                    <li class="nav-item">
                        <a class="nav-link active" href="../server/requests.php?logout=true">Log Out
                            (<?php echo $_SESSION['name']; ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="?ask=true">Ask A Question</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="?u-id=<?php echo $_SESSION['user_id']; ?>">My Questions</a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="?latest=true">Latest Questions</a>
                </li>
            </ul>
            <form class="d-flex" role="search" action="">
                <input class="form-control me-2" type="search" placeholder="Search Questions" aria-label="Search"
                    name="search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>