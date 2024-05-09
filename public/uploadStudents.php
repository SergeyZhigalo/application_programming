<?php
require_once __DIR__ . '/boot.php';
?>

<h1 class="mb-5">Импорт студентов</h1>

<?php flash(); ?>

<form action="services/uploadStudentsService.php" method="post" enctype="multipart/form-data">
    <label for="file">Выберите файл</label>
    <input type="file" name="file[]" id="file" multiple>
    <label for="group">Выберите группу</label>
    <select name="group" id="group">
        <option value="">Выберите группу</option>
        <?php foreach(getAllGroups() as $group) { ?>
            <option value="<?=$group['id']?>"><?=$group['group_name']?></option>
        <?php }?>
    </select>
    <input type="submit" value="Импорт">
</form>
