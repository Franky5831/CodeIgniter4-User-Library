<?php

namespace Franky5831\CodeIgniter4UserLibrary\Controllers;

use CodeIgniter\Controller;
use Exception;
use Franky5831\CodeIgniter4UserLibrary\Models\User as UserModel;

class User extends Controller
{
	private $userModel;

	public function __construct()
	{
		// Loads the user helper
		helper('user_helper');
		// Adds form validation user rules
		config('Validation')->ruleSets[] = \Franky5831\CodeIgniter4UserLibrary\Validation\ValidationRules::class;

		$this->userModel = new UserModel();
	}

	public function login(): \CodeIgniter\HTTP\RedirectResponse|string
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$userCanLogin = $config->userCanLogin;
		if (!$userCanLogin) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		if (isLoggedIn()) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$validationRules = [
			'email' => [
				'label' => 'Email',
				'rules' => 'required|valid_email',
			],
			'password' => [
				'label' => 'Password',
				'rules' => 'required|validateUser[email,password]',
			],
		];

		$captchaRules = $this->getCaptchaRules();
		$validationRules = array_merge($validationRules, $captchaRules);

		if ($this->request->getMethod() == "POST") {
			if ($this->userModel->getUserCanPost() && $this->validate($validationRules)) {
				$user = $this->userModel->where("email", $this->request->getPost("email"))->first();

				$this->setUserMethod($user);

				return redirect()->to("/");
			} else {
				$this->userModel->setPostError();
			}
		}
		try {
			// Returns the view from the app's folder, if it doesn't exist, it returns the vendor's view
			return view('Views/userlib/login-view');
		} catch (\CodeIgniter\View\Exceptions\ViewException $e) {
			return view('../../vendor/franky5831/codeigniter4-user-library/src/Views/login-view');
		}
	}

	public function register(): \CodeIgniter\HTTP\RedirectResponse|string
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$userCanRegister = $config->userCanRegister;
		if (!$userCanRegister) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		if (isLoggedIn()) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$validationRules = [
			'email' => [
				'label' => 'Email',
				'rules' => 'required|valid_email|is_unique[users.email]',
			],
			'password' => [
				'label' => 'Password',
				'rules' => 'required',
			],
			'password_confirm' => [
				'label' => 'Password (confirm)',
				'rules' => 'required|matches[password]',
			],
		];

		$captchaRules = $this->getCaptchaRules();
		$userExtraAttributes = $config->userExtraAttributes;
		$validationRules = array_merge($validationRules, $captchaRules, $userExtraAttributes);

		if ($this->request->getMethod() == "POST") {
			if ($this->userModel->getUserCanPost() && $this->validate($validationRules)) {
				$userData = [
					'email' => $this->request->getPost('email'),
					'password' => $this->request->getPost('password'),
				];
				foreach ($userExtraAttributes as $attribute => $data) {
					$attributeValue = $this->request->getPost($attribute);
					$userData[$attribute] = $attributeValue;
				}

				$this->userModel->save($userData);
				$session = session();

				$user = $this->userModel->where("email", $this->request->getPost("email"))->first();
				$this->setUserMethod($user);

				$session->setFlashdata('success', "Registrazione avvenuta con successo");

				return redirect()->to('/');
			} else {
				$this->userModel->setPostError();
			}
		}

		try {
			// Returns the view from the app's folder, if it doesn't exist, it returns the vendor's view
			return view('Views/userlib/register-view');
		} catch (\CodeIgniter\View\Exceptions\ViewException $e) {
			return view('../../vendor/franky5831/codeigniter4-user-library/src/Views/register-view');
		}
	}


	private function getCaptchaRules(): array
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$validationRules = [];
		if ($config->userLibCaptcha) {
			switch ($config->userLibCaptchaType) {
				case 'cloudflare':
					$validationRules["cf-turnstile-response"] = [
						'label' => 'Captcha',
						'rules' => 'validate_cloudflare_turnstile',
					];
					break;
				case 'recaptcha-v3':
					$validationRules["recaptcha-v3-response"] = [
						'label' => 'Captcha',
						'rules' => 'validate_recaptcha_v3',
					];
					break;
				default:
					throw new \Exception("The selected captcha type does not exists", 1);
					break;
			}
		}
		return $validationRules;
	}

	private function setUserMethod($user): void
	{
		$data = [
			"id" => $user["id"],
			"email" => $user["email"],
			"isLoggedIn" => true,
			"client_ip" => getClientIp(),
			"user_agent" => getUserAgent(),
		];
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$userExtraAttributes = $config->userExtraAttributes;
		foreach ($userExtraAttributes as $attributeKey => $attributeValue) {
			$data[$attributeKey] = $user[$attributeKey];
		}

		session()->set($data);
	}

	public function logout(): \CodeIgniter\HTTP\RedirectResponse
	{
		if (!isLoggedIn()) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
		session()->destroy();
		return $this->getRedirectToRef('/');
	}

	private function getRedirectToRef($redirectTo): \CodeIgniter\HTTP\RedirectResponse
	{
		if (!isset($_SERVER['HTTP_REFERER'])) {
			return redirect()->to($redirectTo);
		}
		$refer = $_SERVER['HTTP_REFERER'];

		$isFromWebsite = str_contains($refer, site_url());;
		if (!$isFromWebsite) {
			return redirect()->to($redirectTo);
		}

		return redirect()->to($refer);
	}
}
