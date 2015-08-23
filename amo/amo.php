<?php

require_once('EAmoCRM.php');

$status_request = '8309196';

$amocrm = new EAmoCrm();

$amocrm->subdomain = '111111';
$amocrm->login = '111111';
$amocrm->hash = '111111';

$result = $amocrm->ping();

if ($result) {

    $amocrm_user = '329196';

    $contact =  array(
        'person_name' => $dump['fio'],
        'contact_data' => array(
            'phone_numbers' => array(
                array('number' => $dump['phone']),
                array('location' => 'Other'),
            ),
            // 'email_addresses' => array(
            //     array('address' => $dump['email']),
            //     array('location' => 'Other')
            // ),
        ),
        'main_user_id' => $amocrm_user,
    );
    $contact_id = $amocrm->addContact($contact);
    if (empty($contact_id)) {
        $contact_id = false;
    }



    $deal = array(
        'name' => strip_tags($dump['description']) . ' (' . date('Y-m-d H:i:s') . ')',
        'status_id' => $status_request,
        'linked_contact' => $contact_id,
        'main_user_id' => $amocrm_user,
    );
    $add_deal_result = $amocrm->addDeal($deal);
    if (empty($add_deal_result['result'])) {
        $deal_id = false;
    }

    $deal_id = $add_deal_result['result'];

    $deal_note = $dump['description'];
    $deal_note = strip_tags($deal_note);

    $add_deal_note_result = $amocrm->addDealNote($deal_id, $deal_note);

    // var_dump($amocrm);

}