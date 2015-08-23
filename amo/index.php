<?php

require_once('EAmoCRM.php');

$amocrm = new EAmoCrm();

$amocrm->subdomain = '1111111';
$amocrm->login = '1111111';
$amocrm->password = '1111111';

$result = $amocrm->ping();

if ($result) {

    $dump = array();

    $end_of_list = false;

    $page = 1;

    while ($end_of_list == false) {
        $contacts = $amocrm->listContacts($page, 500);

        if (!isset($contacts['contacts']['contact'])) {
            $end_of_list = true;
        } else {
            foreach ($contacts['contacts']['contact'] as $contact) {
                if (isset($contact['phones']['phone'])) {
                    foreach ($contact['phones']['phone'] as $phone) {
                        if (!in_array($phone['value'], $dump)) {
                            $dump[] = $phone['value'];
                        }
                    }
                }
            }
        }

        $page++;
    }



    var_dump($dump);
}

?>