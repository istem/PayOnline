<?php
/**
 *
 * Example of "PayOnline" Manager
 * The simple example of using "payonline" class
 *
 * ------------------------------------------------------------------------------
 *
 * Copyright (c) 2016 ML <create@li.ru>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * ------------------------------------------------------------------------------
 *
 */
class payonlineManagerExample {

	public $currency = 'RUB';
	
	protected $_merchantId  = '{MERCHANT_ID}'; // Your MERCHANT_ID 
	protected $_securityKey = '{SECURITY_KEY}'; // Your SECURITY_KEY

	protected $_returnUrl   = '{URL_FOR_REDIRECT_AFTER_SUCCESS_PAYMENT}'; // http:://my.site/success?orderId=
	protected $_failUrl     = '{URL_FOR_REDIRECT_AFTER_CANCEL_PAYMENT}'; // http:://my.site/fail

	/**
	 * @var PayOnline
	 */
	private $payOnline = null;

	function __construct() {

		//
		// Initialize PayOnline
		//
		$this->payOnline = new PayOnline(
					$this->_merchantId,
					$this->_securityKey
				);
	}
	
	/**
	 * Redirect to PayOnline payments form (The payment form is on the side "Payonline")
	 * 
	 * @param int $userId The USER_ID, for which created payment
	 * @param float $amount Amount of payment
	 * 
	 * @return <nothing> Send "Location" header and exit
	 */
	public function goToPayOnline( $userId, $amount  ) {
		
		$amount = number_format( round( $amount, 2 ), 2, '.', '' );
		
		$orderId = $this->_createBill( $userId, $amount ); // Something like working with database

		$data = array(

			'OrderId'          => $orderId,
			'Amount'           => $amount,
			'Currency'         => $this->currency,
			'OrderDescription' => "Deposit of user N" . $userId,
			'ReturnUrl'        => $this->_returnUrl . $orderId,
			'FailUrl'          => $this->_failUrl,
		);
		
		$url = $this->payOnline->getUrl( $data );
		
		header('Location: ' . $url);
		exit();
	}

	/**
	 * Create bill and return html-form to make a payment.
	 *
	 * @param int $userId The USER_ID, for which created payment
	 * @param float $amount Amount of payment
	 *
	 * @return string Return html-form
	 */
	public function createBill( $userId, $amount ) {

		$amount = number_format( round( $amount, 2 ), 2, '.', '' );
		
		$orderId = $this->_createBill( $userId, $amount ); // Something like working with database

		$data = array(

			'OrderId'          => $orderId,
			'Amount'           => $amount,
			'Currency'         => $this->currency,
			'OrderDescription' => "Deposit of user N" . $userId,
			'ReturnUrl'        => $this->_returnUrl . $orderId,
			'FailUrl'          => $this->_failUrl,
		);

		// Example the additional html for form
		$submitButton = '<input type="submit" value="Go to PayOnline"/>';

		return $this->payOnline->getForm( $data, 'payOnlineForm', $submitButton );
	}
	
// *****************************************************************************
	
	/**
	 * Create new bill
	 */
	private function _createBill( $userId, $amount ) {

		// Something like working with database
		return $newOrderId;
	}

// *****************************************************************************
	
	/**
	 * Receiver data from "PayOnline" server
	 *
	 * @param string $type The type of response ("success" or "fail")
	 */
	public function receiver( $type ) {

		$out = null;

		$data = $_POST;

		switch( $type ) {

			case 'success': // The "success" response. Approve previously saved payment and enroll funds
			case 'fail': // The "fail" response. Update previously saved payment as "fail"
				
					// Check the security key
					$securityKey = $this->payOnline->getSecurityKey('callback', $data);
					if ( $data['SecurityKey'] == $securityKey ) {
						
						$out = $this->_updateBill( $type, $data );
					}
				break;
			default:
		}
		return $out;
	}

// *****************************************************************************
	
	/**
	 * Update the previously saved bill
	 */
	private function _updateBill( $status, $data ) {

		// Something like working with database
	}

// *** Example of transactions methods *****************************************

	/**
	 * Execute transaction 
	 *	Such as: "auth", "complete", "rebill", "void", "refund", "check", "search", "list".
	 *
	 * @param string $name Name of transaction "PayOnline"
	 * @param array $data Data array (according to API-specification for specified transaction)
	 *
	 * @return array Returns "PayOnline"-answer as array. Or NULL - if there is some error.
	 */
	public function transaction( $name, $data ) {

		return $this->payOnline->transaction( $name, $data );
	}

	/**
	 * Returns a html-form for 3DS-request to issuing bank.
	 *
	 * @param string $callbackUrl Valid URL which will be handle response from the issuer bank (see method "$this->commit3ds")
	 * @param array $data Data array (Array of data obtained from the method of "auth" with the code 6001)
	 */
	public function get3dsForm( $callbackUrl, $data ) {

		$additionHTML = '<input type="submit" value="Go to Issuer Bank"/>';
		return $this->payOnline->get3dsForm( $callbackUrl, $data, 'PayOnline3ds', $additionHTML );
	}

	/**
	 * Handler of 3DS response from issuer bank. Completion 3DS request
	 *
	 * @param array $data Response data from the issuer bank as array 
	 */
	public function commit3ds( $data ) {

		return $this->payOnline->commit3ds( $data );
	}
}
