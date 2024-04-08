<?php
include_once "hota-database.php";

/*
----------------------------------------
- HotaVNDatabaseAccounts class by HotaVN
----------------------------------------
*/
class HotaVNDatabaseAccounts extends HotaDatabase
{
	private $TYPEQUERY = array(
		"accounts" => array(
			"add" => array(
				"query" => 'INSERT INTO `accounts` (`id`, `username`) VALUES (null, ?)',
				"param" => 's'
			),
			"get" => array(
				"id" => array(
					"query" => 'SELECT * FROM `accounts` WHERE `id` = ?',
					"param" => 'i'
				),
				"username" => array(
					"query" => 'SELECT * FROM `accounts` WHERE `username` = ?',
					"param" => 's'
				),
				"all" => array(
					"query" => 'SELECT * FROM `accounts` WHERE 1',
					"param" => ''
				),
			),
			"update" => array(
				"username.id" => array(
					"query" => 'UPDATE `accounts` SET `username` = ? WHERE `id` = ?',
					"param" => 'si'
				),
				"curIdVote.id" => array(
					"query" => 'UPDATE `accounts` SET `curIdVote` = ? WHERE `id` = ?',
					"param" => 'ii'
				),
				"addCurIdVote.id" => array(
					"query" => 'UPDATE `accounts` SET `curIdVote` = `curIdVote` + 1 WHERE `id` = ?',
					"param" => "i"
				),
				"curIdVoteAv.id" => array(
					"query" => 'UPDATE `accounts` SET `curIdVoteAv` = ? WHERE `id` = ?',
					"param" => 'ii'
				),
			),
			"delete" => array(
				"id" => array(
					"query" => 'DELETE FROM `accounts` WHERE `id` = ?',
					"param" => 'i'
				),
				"username" => array(
					"query" => 'DELETE FROM `accounts` WHERE `username` = ?',
					"param" => 's'
				),
			),
		),
	);

	/* Add */
	public function addAccounts(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["add"], false, false, ...$data);
	}
	/* Get */
	public function getAccountById(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["accounts"]["get"]["id"], ...$data);
	}
	public function getAccountByUsername(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["accounts"]["get"]["username"], ...$data);
	}
	public function getAllAccounts()
	{
		return $this->smart_query_list($this->TYPEQUERY["accounts"]["get"]["all"]);
	}
	/* Update */
	public function updateUsernameById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["update"]["username.id"], false, false, ...$data);
	}
	public function updateCurIdVoteById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["update"]["curIdVote.id"], false, false, ...$data);
	}
	public function addCurIdVoteById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["update"]["addCurIdVote.id"], false, false, ...$data);
	}
	public function updateCurIdVoteAvById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["update"]["curIdVoteAv.id"], false, false, ...$data);
	}
	/* Delete */
	public function deleteAccountById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["delete"]["id"], false, false, ...$data);
	}
	public function deleteAccountByUsername(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["accounts"]["delete"]["username"], false, false, ...$data);
	}
}

/*
----------------------------------------
- HotaVNDatabaseImage class by HotaVN
----------------------------------------
*/
class HotaVNDatabaseImage extends HotaVNDatabaseAccounts
{
	private $TYPEQUERY = array(
		"image" => array(
			"add" => array(
				"query" => 'INSERT INTO `image` (`id`, `url`, `name`, `idCollector`, `info`) VALUES (null, ?, ?, ?, ?)',
				"param" => 'ssis'
			),
			"get" => array(
				"id" => array(
					"query" => 'SELECT * FROM `image` WHERE `id` = ?',
					"param" => 'i'
				),
				"idCollector" => array(
					"query" => 'SELECT * FROM `image` WHERE `idCollector` = ?',
					"param" => 'i'
				),
				"name" => array(
					"query" => 'SELECT * FROM `image` WHERE `name` = ?',
					"param" => 's'
				),
				"all" => array(
					"query" => 'SELECT * FROM `image` WHERE 1',
					"param" => ''
				),
				"maxId" => array(
					"query" => 'SELECT MAX(`id`) as `maxid` FROM `image` WHERE 1',
					"param" => ""
				),
				"id+minId" => array(
					"query" => 'SELECT * FROM `image` WHERE `id` >= ? ORDER BY `id` ASC LIMIT 1',
					"param" => "i"
				),
			),
			"update" => array(
				"url.id" => array(
					"query" => 'UPDATE `image` SET `url` = ? WHERE `id` = ?',
					"param" => 'si'
				),
				"info.id" => array(
					"query" => 'UPDATE `image` SET `info` = ? WHERE `id` = ?',
					"param" => 'si'
				),
			),
			"delete" => array(
				"id" => array(
					"query" => 'DELETE FROM `image` WHERE `id` = ?',
					"param" => 'i'
				),
				"idCollector" => array(
					"query" => 'DELETE FROM `image` WHERE `idCollector` = ?',
					"param" => 'i'
				),
			),
		),
	);

	/* Add */
	public function addImage(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["image"]["add"], false, false, ...$data);
	}
	/* Get */
	public function getImageById(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["image"]["get"]["id"], ...$data);
	}
	public function getImageByIdCollector(...$data)
	{
		return $this->smart_query_list($this->TYPEQUERY["image"]["get"]["idCollector"], ...$data);
	}
	public function getImageByName(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["image"]["get"]["name"], ...$data);
	}
	public function getAllImage()
	{
		return $this->smart_query_list($this->TYPEQUERY["image"]["get"]["all"]);
	}
	public function getMaxId()
	{
		return $this->smart_query_single($this->TYPEQUERY["image"]["get"]["maxId"]);
	}
	public function getImageByIdAndMinId(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["image"]["get"]["id+minId"], ...$data);
	}
	/* Update */
	public function updateUrlById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["image"]["update"]["url.id"], false, false, ...$data);
	}
	public function updateInfoImageById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["image"]["update"]["info.id"], false, false, ...$data);
	}
	/* Delete */
	public function deleteImageById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["image"]["delete"]["id"], false, false, ...$data);
	}
	public function deleteImageByIdCollector(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["image"]["delete"]["idCollector"], false, false, ...$data);
	}
}

/*
----------------------------------------
- HotaVNDatabaseVote class by HotaVN
----------------------------------------
*/
class HotaVNDatabaseVote extends HotaVNDatabaseImage
{
	private $TYPEQUERY = array(
		"vote" => array(
			"add" => array(
				"query" => 'INSERT INTO `vote` (`id`, `idVoter`, `idImage`, `listVote`) VALUES (null, ?, ?, ?)',
				"param" => 'iis'
			),
			"get" => array(
				"id" => array(
					"query" => 'SELECT * FROM `vote` WHERE `id` = ?',
					"param" => 'i'
				),
				"idVoter" => array(
					"query" => 'SELECT * FROM `vote` WHERE `idVoter` = ?',
					"param" => 'i'
				),
				"idImage" => array(
					"query" => 'SELECT * FROM `vote` WHERE `idImage` = ?',
					"param" => 'i'
				),
				"idVoter+idImage" => array(
					"query" => 'SELECT * FROM `vote` WHERE `idVoter` = ? AND `idImage` = ?',
					"param" => 'ii'
				),
				"all" => array(
					"query" => 'SELECT * FROM `vote` WHERE 1',
					"param" => ''
				),
			),
			"update" => array(
				"listVote.id" => array(
					"query" => 'UPDATE `vote` SET `listVote` = ? WHERE `id` = ?',
					"param" => 'si'
				),
			),
			"delete" => array(
				"id" => array(
					"query" => 'DELETE FROM `vote` WHERE `id` = ?',
					"param" => 'i'
				),
				"idVoter" => array(
					"query" => 'DELETE FROM `vote` WHERE `idVoter` = ?',
					"param" => 'i'
				),
				"idImage" => array(
					"query" => 'DELETE FROM `vote` WHERE `idImage` = ?',
					"param" => 'i'
				),
			),
		),
	);

	/* Add */
	public function addVote(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["vote"]["add"], false, false, ...$data);
	}
	/* Get */
	public function getVoteById(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["vote"]["get"]["id"], ...$data);
	}
	public function getVoteByIdVoter(...$data)
	{
		return $this->smart_query_list($this->TYPEQUERY["vote"]["get"]["idVoter"], ...$data);
	}
	public function getVoteByIdImage(...$data)
	{
		return $this->smart_query_list($this->TYPEQUERY["vote"]["get"]["idImage"], ...$data);
	}
	public function getVoteByIdVoterAndIdImage(...$data)
	{
		return $this->smart_query_single($this->TYPEQUERY["vote"]["get"]["idVoter+idImage"], ...$data);
	}
	public function getAllVote()
	{
		return $this->smart_query_list($this->TYPEQUERY["vote"]["get"]["all"]);
	}
	/* Update */
	public function updateListVoteById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["vote"]["update"]["listVote.id"], false, false, ...$data);
	}
	/* Delete */
	public function deleteVoteById(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["vote"]["delete"]["id"], false, false, ...$data);
	}
	public function deleteVoteByIdVoter(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["vote"]["delete"]["idVoter"], false, false, ...$data);
	}
	public function deleteVoteByIdImage(...$data)
	{
		$this->smart_query_noreturn($this->TYPEQUERY["vote"]["delete"]["idImage"], false, false, ...$data);
	}
}

/*
----------------------------------------
- HotaVNDatabase interface class by HotaVN
----------------------------------------
*/
class HotaVNDatabase extends HotaVNDatabaseVote
{
}
