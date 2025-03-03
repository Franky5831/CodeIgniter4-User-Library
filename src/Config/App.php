<?php

namespace Franky5831\CodeIgniter4UserLibrary\Config;

use \Config\App as AppConfig;

class App extends \Config\App
{
	/*
	|--------------------------------------------------------------------------
	| User Library settings
	|--------------------------------------------------------------------------
	|
	| UserLib is a CodeIgniter 4 package that provides a way to add simple user management to your application.
	|
	*/


	/*
	|--------------------------------------------------------------------------
	| Enable Routes
	|--------------------------------------------------------------------------
	* Enable the routes for login, register, logout
	*/
	public bool $setUserLibRoutes = true;

	/*
	* Enable Registration
	*/
	public bool $userCanRegister = true;

	/*
	* Enable Login
	*/
	public bool $userCanLogin = true;

	/*
	 * Decide if the user can view all pages by default.
	 * This rule can be overrided by using the method userCanView() or userCanNotView() from the user helper.
	 * TODO: Implement this
	 */
	public bool $userCanViewByDefault = false;


	/*
	|--------------------------------------------------------------------------
	| Captchas
	|--------------------------------------------------------------------------
	*
	* Enable Captcha
	*/
	public bool $userLibCaptcha = true;

	/*
	* Captcha Type
	* Available types: cloudflare, recaptcha
	*/
	public string $userLibCaptchaType = 'cloudflare';

	/*
	* Captcha Options
	* Example: options for the cloudflare captcha: siteKey and secretKey
	* This is just an example of the structure, the content of the array gets emptied from the constructor
	*/
	public array $userLibCaptchaOptions = [
		"cloudflare" => [
			"siteKey" => "",
			"secretKey" => "",
		],
		"recaptcha-v3" => [
			"siteKey" => "",
			"secretKey" => "",
		]
	];

	/*
	|--------------------------------------------------------------------------
	| User attributes
	|--------------------------------------------------------------------------
	* User extra attributes
	* Example: name, phone, address, etc.
	* This is just an example of the structure, the content of the array gets emptied from the constructor
	! WARNING: You also need to create the column on the database
	*/
	public array $userExtraAttributes = [
		"name" => [
			"label" => "Name",
			"type" => "text",
			"rules" => "required|max_length[255]",
		],
		"username" => [
			"label" => "Username",
			"type" => "text",
			"rules" => "required|max_length[255]",
		],
		"phone" => [
			"label" => "Phone",
			"type" => "text",
			"rules" => "required|max_length[255]|regex_match[/^[0-9]{10}$/]",
		]
	];


	/*
	|--------------------------------------------------------------------------
	| Session hijacking
	|--------------------------------------------------------------------------
	* Match IP
	* If true, the session will be destroyed if the client ip and user agent do not match
	* //! WARNING: If you enable this the session will be destroyed every time the IP changes, this can happen if the user is using a mobile device (like a phone) and switches networks
	*/
	public bool $sessionHijackingMatchIP = false;

	/*
	* Match User Agent
	* If true, the session will be destroyed if the client ip and user agent do not match
	*/
	public bool $sessionHijackingMatchUserAgent = false;


	/*
	|--------------------------------------------------------------------------
	| Brute force attacks
	|--------------------------------------------------------------------------
	* Error logger
	* If true, the user will be blocked after exceeding the maximum number of errors
	*/
	public bool $userPostErrorLogger = true;

	/*
	* Maximum number of errors
	* The maximum number of errors allowed in a row
	*/
	public int $maxPostErrors = 10;

	/*
	 * Timeout
	 * The time in seconds before the counter will be resetted and the time the user will be blocked after exceeding the maximum number of errors
	*/
	public int $userErrorTimeout = 300;


	/**
	 * Inside the constructor we check if the user has set the config options in the app config file, if they do we use their values, otherwise we use the default values
	 */
	public function __construct()
	{
		$appConfig = config(AppConfig::class);

		$this->userLibCaptchaOptions = array();
		$this->userExtraAttributes = array();
		$this->setUserLibRoutes = property_exists($appConfig, "setUserLibRoutes") ? $appConfig->setUserLibRoutes : $this->setUserLibRoutes;
		$this->userCanRegister = property_exists($appConfig, "userCanRegister") ? $appConfig->userCanRegister : $this->userCanRegister;
		$this->userCanLogin = property_exists($appConfig, "userCanLogin") ? $appConfig->userCanLogin : $this->userCanLogin;
		$this->userCanViewByDefault = property_exists($appConfig, "userCanViewByDefault") ? $appConfig->userCanViewByDefault : $this->userCanViewByDefault;
		$this->userLibCaptcha = property_exists($appConfig, "userLibCaptcha") ? $appConfig->userLibCaptcha : $this->userLibCaptcha;
		$this->userLibCaptchaType = property_exists($appConfig, "userLibCaptchaType") ? $appConfig->userLibCaptchaType : $this->userLibCaptchaType;
		$this->userLibCaptchaOptions = property_exists($appConfig, "userLibCaptchaOptions") ? $appConfig->userLibCaptchaOptions : $this->userLibCaptchaOptions;
		$this->userExtraAttributes = property_exists($appConfig, "userExtraAttributes") ? $appConfig->userExtraAttributes : $this->userExtraAttributes;
		$this->sessionHijackingMatchIP = property_exists($appConfig, "sessionHijackingMatchIP") ? $appConfig->sessionHijackingMatchIP : $this->sessionHijackingMatchIP;
		$this->sessionHijackingMatchUserAgent = property_exists($appConfig, "sessionHijackingMatchUserAgent") ? $appConfig->sessionHijackingMatchUserAgent : $this->sessionHijackingMatchUserAgent;
		$this->userPostErrorLogger = property_exists($appConfig, "userPostErrorLogger") ? $appConfig->userPostErrorLogger : $this->userPostErrorLogger;
		$this->maxPostErrors = property_exists($appConfig, "maxPostErrors") ? $appConfig->maxPostErrors : $this->maxPostErrors;
		$this->userErrorTimeout = property_exists($appConfig, "userErrorTimeout") ? $appConfig->userErrorTimeout : $this->userErrorTimeout;

		$allowedCaptchas = ["cloudflare", "recaptcha-v3"];
		if (
			!in_array($this->userLibCaptchaType, $allowedCaptchas)
			&& $this->userLibCaptcha
		) {
			throw new \Exception("The selected captcha type does not exists", 1);
		}
	}
}
