<?php
include __DIR__ . '/bootstrap.php';
$db = new PDO('mysql:host=localhost;dbname=ddd', 'root', '', [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
]);
$errormsg = null;
if (isset($_POST['submit'])) {
    //$post = $_POST['post'];
    $db->beginTransaction();
    try {
        $stm = $db->prepare('INSERT INTO posts (title, content) VALUES (?, ?)');
        $stm->exec([
            $_POST['title'],
            $_POST['content']
        ]);
        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        $errormsg = 'Post could not be created! :(';
    }
}

$conn = $db;
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
    $sql = $conn->prepare('SELECT * FROM posts');
    $sql->execute();
    $posts = $sql->fetchAll();
}catch(PDOException $e){
        echo "ERROR: " . $e->getMessage();
}

?>
<?php include __DIR__ . '/header.php'; ?>
<?php if (null !== $errormsg): ?>
<div class="alert error"><?php echo $errormsg; ?></div>
<?php else: ?>
<div class="alert success">Bravo! Post was created successfully!</div> <?php endif; ?>
<table>
<thead><tr><th>ID</th><th>TITLE</th><th>ACTIONS</th></tr></thead> <tbody>
<?php foreach ($posts as $post): ?>
<tr>
<td><?php echo $post['id']; ?></td> <td><?php echo $post['title']; ?></td> <td><?php echo $post['content']; ?></td>
</tr>
<?php endforeach; ?>
        </tbody>
        </table>
<?php include __DIR__ . '/footer.php'; ?>
