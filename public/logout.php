<?php

require_once __DIR__.'/boot.php';

$_SESSION['user'] = null;
header('Location: /');
