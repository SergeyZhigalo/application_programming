<?php
require_once __DIR__ . '/boot.php';
?>

<h1 class="mb-5">Импорт расписания</h1>

<?php flash(); ?>

<form action="services/uploadScheduleService.php" method="post" enctype="multipart/form-data">
    <label for="file">Выберите файл</label>
    <input type="file" name="file[]" id="file" multiple>
    <label for="university">Выберите вуз</label>
    <select name="university" id="university">
        <option value="">Выберите ВУЗ</option>
        <?php foreach(getAllUniversities() as $university) { ?>
            <option value="<?=$university['id']?>"><?=$university['university_name']?></option>
        <?php }?>
    </select>
    <input type="submit" value="Upload">
</form>
