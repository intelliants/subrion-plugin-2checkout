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

if (isset($_POST['item_number']) && !empty($_POST['item_number']))
{
	$params = array();
	foreach($_POST as $k => $v)
	{
		$params[$k] = $v;
	}

	// mandatory to get correct hash
	if ($iaCore->get('2checkout_demo'))
	{
		$params['order_number'] = '1';
	}

	if (isset($iaLog))
	{
		$iaLog->logInfo('2checkout $_POST response', $params);
	}

	// require 2co API library
	require_once dirname(__FILE__) . '/lib/Twocheckout.php';

	// validate response
	$result = Twocheckout_Return::check($params, $iaCore->get('2checkout_secret'), 'array');

	if (isset($iaLog))
	{
		$iaLog->logInfo('2checkout response result', $result);
	}

	if ('Fail' == $result['response_code'])
	{
		$error = true;
		$messages[] = $result['response_message'];
	}
	else
	{
		$str_arr = explode('|', base64_decode(urldecode($_POST['item_number'])));

		if (!$iaCore->get('2checkout_demo') && $iaDb->exists("`order_number` = '{$_POST['order_number']}'", null, iaTransaction::getTable()))
		{
			$error = true;
			$messages[] = iaLanguage::get('transaction_exists');
		}

		$order = array(
			'payment_gross' => $_POST['total'],
			'payment_date' => date('Y-m-d H:i:s'),
			'payment_status' => iaTransaction::PASSED,
			'first_name' => iaUtil::checkPostParam('first_name'),
			'last_name' => iaUtil::checkPostParam('last_name'),
			'payer_email' => $_POST['email'],
			'txn_id' => $_POST['order_number'],
			'mc_currency' => $iaCore->get('currency'),
		);

		if (isset($iaLog))
		{
			$iaLog->logInfo('2checkout order information', $order);
		}

		$transaction = $temp_transaction;
		$transaction['email'] = iaUtil::checkPostParam('email');
		$transaction['status'] = iaTransaction::FAILED;

		if (!$error)
		{
			$transaction['reference_id'] = iaUtil::checkPostParam('order_number');
			$transaction['status'] = iaTransaction::PASSED;
			$transaction['amount'] = $_POST['total'];

			$messages[] = iaLanguage::get('2checkout_payment_completed');
		}
	}
}