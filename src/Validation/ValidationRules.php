<?php

namespace Franky5831\CodeIgniter4UserLibrary\Validation;

use Franky5831\CodeIgniter4UserLibrary\Models\User as UserModel;
use voku\helper\AntiXSS;

class ValidationRules
{
	private $request;
	protected $antiXss;

	public function __construct()
	{
		$this->request = service('request');
		$this->antiXss = new AntiXSS();
	}

	public function validateUser(string $str, string $fields, array $data): bool
	{
		$model = new UserModel();
		$user = $model->where("email", $data["email"])->first();

		if (!$user) {
			return false;
		}

		return (bool) password_verify($data["password"], $user["password"]);
	}

	public function validate_cloudflare_turnstile(): bool
	{
		$cfTurnstileResponse = $this->request->getPost('cf-turnstile-response');

		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$options = $config->userLibCaptchaOptions["cloudflare"];
		$cfTurnstileSecretKey = $options["secretKey"];

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
			],
			CURLOPT_POSTFIELDS => json_encode([
				'secret'   => $cfTurnstileSecretKey,
				'response' => $cfTurnstileResponse
			]),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_URL => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
		]);
		$curlResult = curl_exec($curl);
		curl_close($curl);

		return (bool) json_decode($curlResult)->success;
	}

	public function validate_recaptcha_v3(): bool
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$options = $config->userLibCaptchaOptions["recaptcha-v3"];
		$captchaV3SecretKey = $options["secretKey"];
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $captchaV3SecretKey . '&response=' . $this->request->getPost('g-recaptcha-response');

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
		]);
		$curlResult = curl_exec($curl);
		curl_close($curl);

		return (bool) json_decode($curlResult)->success;
	}

	public function validateXss($str, $fields,): bool
	{
		$originalValue = $str;
		$cleanValue = $this->antiXss->xss_clean($str);
		return $originalValue === $cleanValue;
	}
}
