<?php
include_once "logger.php";

/*
----------------------------------------
- HotaDatabase class by HotaVN
- Requied: Logger by HotaVN
----------------------------------------
*/
class HotaDatabase extends Logger
{
	public $NUMDATABASES = 1;
	public $MAXROW = 1000000;
	public $MAXSIZE = 2900;
	public $VERSION = '2.5';
	public $idexConnecting = 0;
	protected $USERNAMES = array("root");
	protected $PASSWORDS = array("");
	protected $HOSTS 	   = array("localhost");
	protected $DATABASES = array("cnnimage");
	protected $conn;

	protected $TYPEQUERY_system = array(
		"system" => array(
			//Get info_table
			"get_size_table" => array(
				"query" => 'SELECT table_schema AS "database", ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS "size" FROM information_schema.TABLES WHERE table_schema = ? GROUP BY table_schema;',
				"param" => 's'
			),
			"get_num_row" => array(
				"query" => 'SELECT TABLE_SCHEMA, SUM(TABLE_ROWS) AS `numRow` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? GROUP BY TABLE_SCHEMA;',
				"param" => 's'
			),
		)
	);

	/* Function database */
	public function connectDatabaseR($host, $username, $password, $database)
	{
		return (($this->conn = mysqli_connect($host, $username, $password, $database)) ? true : false);
	}
	public function connectDatabase($index)
	{
		$this->idexConnecting = $index;
		return (($this->conn = mysqli_connect($this->HOSTS[$index], $this->USERNAMES[$index], $this->PASSWORDS[$index], $this->DATABASES[$index])) ? true : false);
	}
	public function closeConnect()
	{
		$this->conn->close();
	}
	public function okConnections()
	{
		$cntOkConnection = 0;
		for ($i = 0; $i < $this->NUMDATABASES; $i++) {
			if ($this->connectDatabase($i)) {
				$this->closeConnect();
				$cntOkConnection++;
			}
		}
		return $cntOkConnection;
	}
	public function okAll()
	{
		if ($this->okConnections() == $this->NUMDATABASES) return true;
		return false;
	}
	public function okConnection()
	{
		return (($this->conn) ? true : false);
	}

	/* Base query */
	public function query_base($query_address, ...$data)
	{
		$data_adress = $query_address;
		if (!$this->okConnection()) return false;
		$stmt = $this->conn->prepare($data_adress['query']);
		if (!$stmt) return false;
		if (count($data) > 0) {
			$stmt->bind_param($data_adress['param'], ...$data);
		}
		$stmt->execute();
		return $stmt;
	}
	/* Base query plus */
	public function query_list($query_address, ...$data)
	{
		$stmt = $this->query_base($query_address, ...$data);
		if (!$stmt) return false;
		$result = $stmt->get_result();
		if (!$result) return array();
		$listRow = [];
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) array_push($listRow, $row);
		return $listRow;
	}
	public function query_single($query_address, ...$data)
	{
		$stmt = $this->query_base($query_address, ...$data);
		if (!$stmt) return false;
		$result = $stmt->get_result();
		if (!$result) return null;
		return $result->fetch_assoc();
	}
	public function query_noreturn($query_address, ...$data)
	{
		$stmt = $this->query_base($query_address, ...$data);
		if (!$stmt) return false;
		return true;
	}

	/* Query table */
	public function checkSize()
	{
		if ($row = $this->query_single($this->TYPEQUERY_system['system']['get_size_table'], $this->DATABASES[$this->idexConnecting])) {
			return (float) $row['size'];
		}
		return $this->MAXSIZE;
	}
	public function checkNumRow()
	{
		if ($row = $this->query_single($this->TYPEQUERY_system['system']['get_num_row'], $this->DATABASES[$this->idexConnecting])) {
			return (int) $row['numRow'];
		}
		return $this->MAXROW;
	}

	/* Function Plus */
	public function getTimeVietnam()
	{
		$timestamp = time();
		return date("Y-m-d", strtotime("7 hours", $timestamp));
	}

	/* Base query smart */
	public function smart_query_base($fun_query, $fun_add, $return_cur = true, $checker_maxrow = false, $checker_maxsize = false)
	{
		for ($i = 0; $i < $this->NUMDATABASES; $i++) {
			if ($this->connectDatabase($i)) {
				if (($this->checkNumRow() >= $this->MAXROW && $checker_maxrow) || ($this->checkSize() >= $this->MAXSIZE && $checker_maxsize)) {
					$this->closeConnect();
					continue;
				}
				if ($listRow = $fun_query()) {
					$this->closeConnect();
					if ($return_cur) return $fun_add($listRow);
					else $fun_add($listRow);
				} else $this->closeConnect();
			}
		}
	}
	public function smart_query_list($query_address, ...$data)
	{
		$result = [];
		$this->smart_query_base(
			function () use ($query_address, $data) {
				return $this->query_list($query_address, ...$data);
			},
			function ($row) use (&$result) {
				$result = array_merge($result, $row);
				return true;
			},
			false,
			false,
			false
		);
		return $result;
	}
	public function smart_query_single($query_address, ...$data)
	{
		$result = false;
		$this->smart_query_base(
			function () use ($query_address, $data) {
				return $this->query_single($query_address, ...$data);
			},
			function ($row) use (&$result) {
				return $result = $row;
			},
			true,
			false,
			false
		);
		return $result;
	}
	public function smart_query_noreturn($query_address, $checker_maxrow, $checker_maxsize, ...$data)
	{
		return $this->smart_query_base(
			function () use ($query_address, $data) {
				return $this->query_noreturn($query_address, ...$data);
			},
			function ($row) {
				return $row;
			},
			true,
			$checker_maxrow,
			$checker_maxsize
		);
	}
}
/*
----------------------------------------
- End HotaDatabase class by HotaVN
----------------------------------------
*/
