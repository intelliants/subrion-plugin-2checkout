<?php
//##copyright##

$form_values = array();

if ($iaCore->get('2checkout_demo'))
{
	$form_values['demo'] = 'Y';
}

$form_values['sid'] = $iaCore->get('2checkout_id');
$form_values['currency_code'] = $iaCore->get('2checkout_currency');
$form_values['total'] = $plan['cost'];
$form_values['custom'] = $plan['title'];

$form_values['id_type'] = 1;
$form_values['cart_order_id'] = time();
$form_values['x_Receipt_Link_URL'] = IA_RETURN_URL . 'completed' . IA_URL_DELIMITER;
$form_values['id_account'] = iaUsers::hasIdentity() ? iaUsers::getIdentity()->id : 0;
$form_values['item_number'] = $plan['title'];
$form_values['vip'] = $_SERVER['REMOTE_ADDR'];

// print form values
if (isset($iaLog))
{
	$iaLog->logInfo('2checkout form values', $form_values);
}

// require 2co API library
require_once dirname(__FILE__) . '/lib/Twocheckout.php';

Twocheckout_Charge::redirect($form_values);