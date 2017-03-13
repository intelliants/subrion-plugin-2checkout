<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2017 Intelliants, LLC <https://intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://subrion.org/
 *
 ******************************************************************************/

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