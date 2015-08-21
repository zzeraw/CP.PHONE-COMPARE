<?php

// Проверяем загружен ли файл
if (is_uploaded_file($_FILES["filename"]["tmp_name"])) {

    $fp = fopen($_FILES["filename"]["tmp_name"], 'r');

    $phones = array();

    if ($fp) {

        while (!feof($fp)) {
            $str = fgets($fp);

            $explodes = explode(',', $str);

            foreach ($explodes as $explode) {
                $number = trim(mb_convert_encoding($explode, 'UTF-8', 'Windows-1251'));

                if (mb_strlen($number) <= 5) {
                    $phones[1][] = trim(mb_convert_encoding($explode, 'UTF-8', 'Windows-1251'));
                    continue;
                }

                $number = str_replace(' ', '', $number);
                $number = str_replace('(', '', $number);
                $number = str_replace(')', '', $number);
                $number = str_replace('-', '', $number);
                $number = str_replace('–', '', $number);
                $number = str_replace('/', '', $number);
                $number = str_replace('"', '', $number);
                // $number = str_replace('.', '', $number);
                // $number = str_replace(':', '', $number);

                if (mb_strlen($number) <= 5) {
                    $phones[1][] = trim(mb_convert_encoding($explode, 'UTF-8', 'Windows-1251'));
                    continue;
                }

                $number = preg_replace('~[^0-9+]+~','---',$number);

                $number = explode('---', $number);
                $number = $number[0];

                if (mb_strlen($number) <= 5) {
                    $phones[1][] = trim(mb_convert_encoding($explode, 'UTF-8', 'Windows-1251'));
                    continue;
                }

                if ($number[0] != '+') {
                    if ( (mb_strlen($number) == 11) && ( ($number[0] == '8') || ($number[0] == '7') ) ) {
                        $number = mb_substr($number, 1);
                        $number = '+7' . $number;
                    } elseif (mb_strlen($number) == 10) {
                        $number = '+7' . $number;
                    } elseif (mb_strlen($number) == 6) {
                        $number = '+78352' . $number;
                    }
                }

                if ($number[0] == '+') {
                    $phones[0][] = $number;
                } else {
                    $phones[1][] = trim(mb_convert_encoding($explode, 'UTF-8', 'Windows-1251'));
                }
            }

        }
    }

    $file_array = array();
    foreach ($phones[0] as $value) {
        $file_array[] = $value;
    }
    $file_array[] = '--- Необработанные номера ---';
    foreach ($phones[1] as $value) {
        $file_array[] = $value;
    }

    $file = implode(PHP_EOL, $file_array);

    $filename = 'txt_from_amo_' . date('Ymd') .'_' . date('His') . '.txt';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');

    echo $file;

} else {
    echo("Ошибка загрузки файла");
}

?>