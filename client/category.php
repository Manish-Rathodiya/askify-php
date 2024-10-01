<select class="form-control" name="category_id" id="category_id">
    <option value="">select a category</option>
    <?php include "../common/db.php";
    $result = $conn->query("SELECT * FROM category");
    $result->execute();
    $category = $result->fetchAll();
    foreach ($category as $row) {
        $id = ($row['id']);
        $name = ucfirst($row['name']);
        echo "<option value=$id>$name</option>";
    } ?>
</select>