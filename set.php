<?php

require_once 'ccounter.php';

echo 'ファイル『' . CCounter::COUNT_FILE . '』を初期化します。' . PHP_EOL;

$today_date = date('d');
file_put_contents(CCounter::COUNT_FILE, '0,0,0,' . $today_date, LOCK_EX);

echo 'ファイル『' . CCounter::COUNT_FILE . '』の初期化が完了しました。';