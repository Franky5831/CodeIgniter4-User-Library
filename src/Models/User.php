<?php

namespace Franky5831\CodeIgniter4UserLibrary\Models;

use CodeIgniter\Model;

class User extends Model
{
	protected $table = 'users';
	protected $allowedFields = ['email', 'password'];
	protected $beforeInsert = ['beforeInsert'];
	protected $beforeUpdate = ['beforeUpdate'];

	public function __construct()
	{
		$allowedFields = $this->allowedFields;
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$userExtraAttributes = $config->userExtraAttributes;
		$allowedFields = array_merge($allowedFields, array_keys($userExtraAttributes));
		$this->setAllowedFields($allowedFields);
		parent::__construct();
	}

	public function beforeInsert(array $data): array
	{
		return $this->passowrdHash($data);
	}

	public function beforeUpdate(array $data): array
	{
		return $this->passowrdHash($data);
	}

	protected function passowrdHash(array $data): array
	{
		if (isset($data['data']['password'])) {
			$data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
		}
		return $data;
	}

	public function setPostError(): void
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$userPostErrorLogger = $config->userPostErrorLogger;
		if ($userPostErrorLogger) {
			$clientIp = getClientIp();
			$cache = service('cache');

			$clientIpCacheId = "client_ip_error_" . $clientIp;
			$clientIpCacheVal = $cache->get($clientIpCacheId);
			if ($clientIpCacheVal) {
				$clientIpCacheVal++;
			} else {
				$clientIpCacheVal = 1;
			}
			$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
			$userErrorTimeout = $config->userErrorTimeout;
			$cache->save($clientIpCacheId, $clientIpCacheVal, $userErrorTimeout);
		}
	}

	private function getPostError(): int
	{
		$clientIp = getClientIp();
		$cache = service('cache');

		$clientIpCacheId = "client_ip_error_" . $clientIp;
		$clientIpCacheVal = $cache->get($clientIpCacheId);
		if (!$clientIpCacheVal) {
			$clientIpCacheVal = 0;
		}

		return $clientIpCacheVal;
	}

	public function getUserCanPost(): bool
	{
		$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
		$userPostErrorLogger = $config->userPostErrorLogger;
		if ($userPostErrorLogger) {
			$maxPostErrors = $config->maxPostErrors;
			$userCanPost = $this->getPostError() < $maxPostErrors;
			if (!$userCanPost) {
				\Config\Services::validation()->setError("user_cant_post", lang('Validation.user_cant_post'));
			}
			return $userCanPost;
		}
		return true;
	}
}
