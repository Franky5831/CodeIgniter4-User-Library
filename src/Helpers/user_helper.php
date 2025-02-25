<?php

if (!function_exists('isLoggedIn')) {
	function isLoggedIn(): bool
	{
		return (bool) session()->get('isLoggedIn');
	}
}

if (!function_exists('getClientIp')) {
	function getClientIp(): string
	{
		$ip = getenv('HTTP_CLIENT_IP') ?:
			getenv('HTTP_X_FORWARDED_FOR') ?:
			getenv('HTTP_X_FORWARDED') ?:
			getenv('HTTP_FORWARDED_FOR') ?:
			getenv('HTTP_FORWARDED') ?:
			getenv('REMOTE_ADDR');
		return $ip;
	}
}

if (!function_exists('getUserAgent')) {
	function getUserAgent(): string
	{
		return getenv('HTTP_USER_AGENT');
	}
}
