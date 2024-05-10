<?php
require_once __DIR__ . '/boot.php';

use Illuminate\Support\Collection;

$classesCollection = new Collection(getAllClassesByTeacherId($_SESSION['user']['id']));
?>

<h1 class="mb-5">Экспорт</h1>

<form method="post" action="services/exportService.php">
    <label for="group">Группа</label>
    <select name="group" id="group">
        <option value="">Не выбрано</option>
        <?php foreach(getAllGroups() as $group) { ?>
            <option value="<?=$group['id']?>"><?=$group['group_name']?></option>
        <?php }?>
    </select>

    <label for="class">Предмет</label>
    <select name="class" id="class">
        <option value="">Не выбрано</option>
        <?php foreach($classesCollection->unique('subject')->all() as $class) { ?>
            <option value="<?=$class['id']?>"><?=$class['subject']?></option>
        <?php }?>
    </select>

    <label for="university">Выберите вуз</label>
    <select name="university" id="university">
        <option value="">Выберите ВУЗ</option>
        <?php foreach(getAllUniversities() as $university) { ?>
            <option value="<?=$university['id']?>"><?=$university['university_name']?></option>
        <?php }?>
    </select>

    <label for="dateStart">Дата "C"</label>
    <input type="date" id="dateStart" name="dateStart">

    <label for="dateEnd">Дата "До"</label>
    <input type="date" id="dateEnd" name="dateEnd">

    <input type="submit" value="Сохранить">
</form>
