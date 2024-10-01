<div class="container">
    <h1 class="heading">Ask A Questions</h1>
    <form action="../server/requests.php" method="post">
        <div class="col-6 offset-sm-3 mb">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="enter question" required>
        </div>
        <div class="col-6 offset-sm-3 mb">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="enter description"
                required></textarea>
        </div>
        <div class="col-6 offset-sm-3 mb">
            <label for="title" class="form-label">Category</label>
            <?php include "../client/category.php"; ?>

        </div>
        <button type="submit" name="ask" class="btn btn-primary offset-sm-3 mb">Ask Question</button>
    </form>
</div>