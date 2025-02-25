<?php

namespace Franky5831\CodeIgniter4UserLibrary\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;

class RedirectToLogin implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null): RedirectResponse|null
	{
		// Check if the user is logged in
		helper('user_helper');
		if (isLoggedIn()) {
			return null;
		}

		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$notLoggedInCanView = $config->userCanViewByDefault;

		$routes = service("routes")->getRoutesOptions();
		$router = service("router");
		$currentRoute = $router->getMatchedRoute();
		if (isset($currentRoute[0])) {
			$currentRoute = $currentRoute[0];
		}
		if (isset($routes[$currentRoute]['userCanView'])) {
			$notLoggedInCanView = $routes[$currentRoute]['userCanView'];
		}
		if ($notLoggedInCanView) {
			return null;
		}

		// Check if the user is on the login or register page, if so, don't redirect or you will get an infinite loop
		helper('url');
		$currentUrl = current_url();
		$loginUrl = url_to('loginurl');
		$registerUrl = url_to('registerurl');

		if ($currentUrl == $loginUrl || $currentUrl == $registerUrl) {
			return null;
		}

		// Redirect to the login page
		return redirect()->to($loginUrl);
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ResponseInterface
	{
		// Nothing here
		return $response;
	}
}
