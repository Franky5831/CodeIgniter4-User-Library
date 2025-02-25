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
}
