# PayOnline

PHP-class for integration with IPSP [PayOnline] (http://payonline.ru/)


# Note

Documentation of API "PayOnline" provided for programmers of clients after the filing of the application for connection.


# Requirements

Contact support "PayOnline" to get your MERCHANT_ID and SECURITY_KEY.
Define the two callback URLs for the successful payments and the cancel of pay.


# Public variables of the PayOnline class

```php
	/**
	 * The language of interface payment form. Allowed values:
	 *	ru - Russian;
	 *	en - English;
	 *	fr - French;
	 *	ka - Georgian;
	 *	zn-ch - Chinese.
	 *
	 * @var string
	 */
	public $LANG = 'ru';

	/**
	 * The type page of payment form. Allowed values:
	 *	[empty] - credit card payment;
	 *	qiwi - QIWI payments;
	 *	paymaster - WebMoney;
	 *	yandexmoney - Yandex.Money;
	 *	masterpass - using MasterPass;
	 *	select - the page of payment options.
	 *
	 * @var string
	 */
	public $FORM_TYPE = '';

	/**
	 * Contains last error after parse PayOnline-response
	 *
	 * @var string
	 */
	public $LAST_ERROR = null;
```


# Public methods of the PayOnline class

```php
	/**
	 * Returns redirect URL for calling "PayOnline" forms by payments server side
	 *
	 * @param array $data Data array, according to API Documentation for "Standart"-scheme
	 *
	 * @return string URL
	 */
	public function getUrl( $data );

	/**
	 * Get a html form for request "PayOnline" interface
	 *
	 * @param array $data Data array, according to API Documentation for "Standart"-scheme.
	 * @param string $id html-attribute "id" of html-form
	 * @param string $additionHTML Additional html-content for include into form.
	 *
	 * @return string Ready html-content of html-form
	 */
	public function getForm( $data, $id, $additionHTML );

	/**
	 * Execute transaction
	 *
	 * @param string $method Name of transaction "PayOnline" (see API Documentation)
	 * @param array $data Data array (according to API Documentation for transaction)
	 *
	 * @return array Returns "PayOnline"-answer as array. Or NULL - if there is some error.
	 */
	public function transaction( $method, $data );

	/**
	 * Get a separate html-content of form for 3DS-request to issuing bank.
	 *
	 * @param string $callbackUrl URL-address of Shop(Merchant), 
	 *                            to which the response will be sent from the bank
	 * @param array $data Response data "PayOnline" on the "auth"-transaction (when demand 3DS)
	 * @param string $formId html-attribute "id" of html-form
	 * @param string $additionHTML Additional html-content for include into form.
	 *
	 * @return mixed Returns generated data array or ready html-form
	 */
	public function get3dsForm( $callbackUrl, $data, $formId='PayOnlinePaReq', $additionHTML=null );

	/**
	 * Handler of response from issued bank on 3DS-request.
	 * Execute 3DS-transaction to "PayOnline" system
	 *
	 * @param array $data Response data from the bank as array
	 *
	 * @return array Returns "PayOnline"-response according to API Documentation
	 */
	public function commit3ds( $data );

	/**
	 * Get "SecurityKey" value for specified transaction "PayOnline" 
	 *
	 * @param string $method Name of transaction "PayOnline'
	 * @param array $data Data array for transaction according to API Documentation
	 *
	 * @return string Returns MD5-SecurityKey according to API Documentation "PayOnline"
	 */
	public function getSecurityKey( $method, $data );
```

Also examples of use see in file "example.php"

