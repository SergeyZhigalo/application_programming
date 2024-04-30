<?php

require_once __DIR__ . '/boot.php';

use IcsReader\IcsReader;

//$file = file_get_contents(__DIR__.'/schedule/export_202404301559.ics');
//
//$reader = new IcsReader();
//$ics = $reader->parse($file);
//
//dd(collect($ics->getCalendar()["VEVENT"]));

//pdo();

//echo '<form action="upload.php" method="post" enctype="multipart/form-data">
//    <input type="file" name="file[]" multiple>
//    <input type="submit" value="Upload">
//</form>';
?>

<?php if (check_auth()) { ?>

    <h1>Welcome back, <?=htmlspecialchars($_SESSION['user']['name'])?>!</h1>

    <form class="mt-5" method="post" action="logout.php">
        <button type="submit" class="btn btn-primary">Logout</button>
    </form>

<?php }?>
