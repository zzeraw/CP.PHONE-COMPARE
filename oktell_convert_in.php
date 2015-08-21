<?php

use com\google\i18n\phonenumbers\PhoneNumberUtil;
use com\google\i18n\phonenumbers\PhoneNumberFormat;
use com\google\i18n\phonenumbers\NumberParseException;

require_once 'PhoneNumberUtil.php';

$phoneUtil = PhoneNumberUtil::getInstance();

// Проверяем загружен ли файл
if (is_uploaded_file($_FILES["filename"]["tmp_name"])) {

    $fp = fopen($_FILES["filename"]["tmp_name"], 'r');

    $phones = array();

    if ($fp) {

        $line = 0;

        while (!feof($fp)) {

            $str = fgets($fp);

            $explodes = explode("\t", $str);

            if ($line == 0) {
                $line++;
                continue;
            }

            $line++;

            $number = $explodes[5];
            $number = mb_substr($number, 4);

            $number = explode('@', $number);
            $number = $number[0];

            if (empty($number)) {
                continue;
            }

            if (mb_strlen($number) == 6) {
                $number = '88352' . $number;
            }

            try {
                $numberProto = $phoneUtil->parseAndKeepRawInput($number, "RU");
            } catch (NumberParseException $e) {

            }
            $number = $phoneUtil->format($numberProto, PhoneNumberFormat::E164);

            $phones[] = $number;
        }
    }

    $file = implode(PHP_EOL, $phones);

    $filename = 'txt_from_oktell_in_' . date('Ymd') .'_' . date('His') . '.txt';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');

    echo $file;

} else {
    echo("Ошибка загрузки файла");
}

?>