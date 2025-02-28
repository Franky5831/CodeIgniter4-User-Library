<?php

namespace Franky5831\CodeIgniter4UserLibrary\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;

class CheckUserSession implements FilterInterface
{
	/**
	 * Checks if the user is logged in and if the client ip and user agent are the same as the ones stored in the session
	 * This could be caused by a session hijacking attack
	 */
	public function before(RequestInterface $request, $arguments = null): RedirectResponse|null
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		// Check if the user is logged in
		helper('user_helper');
		if (!isLoggedIn()) {
			return null;
		}

		// Checks if the client ip and user agent are the same as the ones stored in the session
		// If they are not the same then destroy the session and redirect to the login page if not already on the login page
		$clientIpMatch = session()->client_ip == getClientIp();
		$clientAgentMatch = session()->user_agent == getUserAgent();
		$enableMatchIP = $config->sessionHijackingMatchIP;
		$enableMatchUserAgent = $config->sessionHijackingMatchUserAgent;

		if (
			(!$clientIpMatch && $enableMatchIP)
			|| (!$clientAgentMatch && $enableMatchUserAgent)
		) {
			session()->destroy();

			$currentUrl = current_url();
			$loginUrl = url_to('loginurl');
			if ($currentUrl == $loginUrl) {
				return null;
			}
			return redirect()->to($loginUrl);
		}

		return null;
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ResponseInterface
	{
		// Nothing here
		return $response;
	}
}
