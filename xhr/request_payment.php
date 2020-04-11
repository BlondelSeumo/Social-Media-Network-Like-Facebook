<?php 
if ($f == 'request_payment') {
    if (Wo_CheckSession($hash_id) === true) {
        if (empty($_POST['paypal_email']) || empty($_POST['amount'])) {
            $errors[] = $error_icon . $wo['lang']['please_check_details'];
        } else {
            if (Wo_IsUserPaymentRequested($wo['user']['user_id']) === true) {
                $errors[] = $error_icon . $wo['lang']['you_have_pending_request'];
            } else if (!filter_var($_POST['paypal_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = $error_icon . $wo['lang']['email_invalid_characters'];
            } else if (!is_numeric($_POST['amount'])) {
                $errors[] = $error_icon . $wo['lang']['invalid_amount_value'];
            } else if (($wo['user']['balance'] < $_POST['amount'])) {
                $errors[] = $error_icon . $wo['lang']['invalid_amount_value_your'] . ''.Wo_GetCurrency($wo['config']['ads_currency']) . $wo['user']['balance'];
            } else if ($wo['config']['m_withdrawal'] > $_POST['amount']) {
                $errors[] = $error_icon . $wo['lang']['invalid_amount_value_withdrawal'] . ' '.Wo_GetCurrency($wo['config']['ads_currency']) . $wo['config']['m_withdrawal'];
            }
            if (empty($errors)) {
                $userU          = Wo_UpdateUserData($wo['user']['user_id'], array(
                    'paypal_email' => $_POST['paypal_email']
                ));
                $insert_payment = Wo_RequestNewPayment($wo['user']['user_id'], $_POST['amount']);
                if ($insert_payment) {
                    $update_balance = Wo_UpdateBalance($wo['user']['user_id'], $_POST['amount'], '-');
                    $data           = array(
                        'status' => 200,
                        'message' => $success_icon . $wo['lang']['you_request_sent']
                    );
                }
            }
        }
    }
    header("Content-type: application/json");
    if (isset($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else {
        echo json_encode($data);
    }
    exit();
}
