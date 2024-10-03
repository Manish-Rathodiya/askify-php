<div>
    <h1 class="heading">Categories</h1>
    <?php include "../common/db.php";
    $category = $conn->query("SELECT * FROM category");
    $category->execute();
    $allCategory = $category->fetchAll();
    foreach ($allCategory as $data) {
        $name = ucfirst($data['name']);
        $id = $data['id'];
        echo "<div class='question-list list-group-item'>
        <h4> <a href='?c-id=$id'>$name</a></h4>
        </div>";
    }
    ?>
</div>