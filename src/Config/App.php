<?php

namespace Franky5831\CodeIgniter4UserLibrary\Config;

use \Config\App as AppConfig;

class App extends \Config\App
{
	/*
	|--------------------------------------------------------------------------
	| UserLib
	|--------------------------------------------------------------------------
	|
	| UserLib is a CodeIgniter 4 package that provides a way to add simple user management to your application.
	|
	*/

	/*
	* Enable Registration
	*/
	public bool $userCanRegister = true;

	/*
	 * Decide if the user can view all pages by default.
	 * This rule can be overrided by using the method userCanView() or userCanNotView() from the user helper.
	 * TODO: Implement this
	 */
	public bool $userCanViewByDefault = false;

	/*
	* Enable Login
	*/
	public bool $userLibLogin = true;

	/*
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


	public function __construct()
	{
		$appConfig = config(AppConfig::class);

		$this->userLibCaptchaOptions = array();
		$this->userExtraAttributes = array();
		$this->userCanRegister = property_exists($appConfig, "userCanRegister") ? $appConfig->userCanRegister : $this->userCanRegister;
		$this->userCanViewByDefault = property_exists($appConfig, "userCanViewByDefault") ? $appConfig->userCanViewByDefault : $this->userCanViewByDefault;
		$this->userLibLogin = property_exists($appConfig, "userLibLogin") ? $appConfig->userLibLogin : $this->userLibLogin;
		$this->userLibCaptcha = property_exists($appConfig, "userLibCaptcha") ? $appConfig->userLibCaptcha : $this->userLibCaptcha;
		$this->userLibCaptchaType = property_exists($appConfig, "userLibCaptchaType") ? $appConfig->userLibCaptchaType : $this->userLibCaptchaType;
		$this->userLibCaptchaOptions = property_exists($appConfig, "userLibCaptchaOptions") ? $appConfig->userLibCaptchaOptions : $this->userLibCaptchaOptions;
		$this->userExtraAttributes = property_exists($appConfig, "userExtraAttributes") ? $appConfig->userExtraAttributes : $this->userExtraAttributes;

		$allowedCaptchas = ["cloudflare", "recaptcha-v3"];
		if (
			!in_array($this->userLibCaptchaType, $allowedCaptchas)
			&& $this->userLibCaptcha
		) {
			throw new \Exception("The selected captcha type does not exists", 1);
		}
	}
}
