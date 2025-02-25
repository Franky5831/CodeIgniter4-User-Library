<?php

namespace Franky5831\CodeIgniter4UserLibrary\Models;

use voku\helper\AntiXSS;

class SecureXss
{
	protected $antiXss;
	protected $request;

	public function __construct()
	{
		$this->antiXss = new AntiXSS();
		$this->request = service('request');
	}

	public function cleanXssPost()
	{
		if ($this->request->getMethod() == "POST") {
			foreach ($this->request->getPost() as $key => $value) {
				$this->request->setPost($key, $this->antiXss->xss_clean($value));
			}
		}
	}
}
