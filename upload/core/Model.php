<?php
// +----------------------------------------------------------------------
// | HuajiBBS
// +----------------------------------------------------------------------
// | Copyright © 2022 HuajiMC. All rights reserved.
// +----------------------------------------------------------------------
// | License: GNU General Public License 3.0
// +----------------------------------------------------------------------
// | Author: 滑稽mc（HuajiMC） <HuajiMC@126.com>
// +----------------------------------------------------------------------

namespace core;
defined('ACCESS') or die('You have no access.');

class Model {
	private $mysqli;
	public $table;
	public $data;
	public $prefix;
	public $result;
	public $options = [];
	public $clear = true;
	
	public function __construct() {
		$host = C('mysql_host');
		$username = C('mysql_username');
		$password = C('mysql_password');
		$dbname = C('mysql_dbname');
		$port = C('mysql_port');
		$charset = C('mysql_charset');
		$prefix = C('mysql_prefix');
		
		$mysqli = @new \mysqli($host,$username,$password,$dbname,$port);
		if(! $mysqli) {
			throw new \Exception($mysqli->connect_error,$mysqli->connect_errno);
		}
		$this->mysqli = $mysqli;
		$this->prefix = $prefix;
		$this->query('set names ' . $charset);
		$this->table($this->table);
	}
	
	public function query($sql) {
		if($result = $this->mysqli->query($sql)) {
			return $result;
		} else {
			throw new \Exception($this->mysqli->error . " (Error Sql: {$sql})",$this->mysqli->errno);
		}
		$this->options = array();
	}
	
	public function table($name) {
		$this->table = $this->prefix . $name;
		return $this;
	}
	
	public function and(...$conditions) {
		if($this->options['where']) $this->options['where'] .= ' and ';
		else $this->options['where'] .= ' where ';
		$this->options['where'] .= ' ( ' . implode(' and ',$conditions) . ' ) ';
		return $this;
	}
	
	public function or(...$conditions) {
		if($this->options['where']) $this->options['where'] .= ' and ';
		else $this->options['where'] .= ' where ';
		$this->options['or'] .= ' ( ' . implode(' or ',$conditions) . ' ) ';
		return $this;
	}
	
	public function select(...$column) {
		$column = $column ? implode(',',$column) : '*';
		$sql = "select {$column} from {$this->table} {$this->options['join']} {$this->options['where']} {$this->options['group']} {$this->options['having']} {$this->options['order']} {$this->options['limit']}";
		$this->result = $this->query($sql);
		$this->clearOptions();

		return $this;
	}
	
	public function getOne() {
		return $this->result->fetch_assoc();
	}
	
	public function getAll() {
		return $this->result->fetch_all(MYSQLI_ASSOC);
	}
	
	public function update($data) {
		$update = '';
		foreach($data as $key => $value) {
			if($key = preg_replace('/(\[\+\])$/','',$key,-1,$count) and $count > 0) {
				$update .= "`{$key}` = `{$key}` + {$value},";
			} else if($key = preg_replace('/(\[\-\])$/','',$key,-1,$count) and $count > 0) {
				$update .= "`{$key}` = `{$key}` - {$value},";
			} else {
				$update .= "`{$key}` = '{$value}',";
			}
		}
		
		$update = rtrim($update,',');
		
		$result = $this->query("update {$this->table} {$this->options['join']} set {$update} {$this->options['where']} {$this->options['group']} {$this->options['having']} {$this->options['order']} {$this->options['limit']}");
		$this->clearOptions();

		return $this->affected_rows();
	}
	
	public function insert($data) {
		$keys = '`' . implode('`,`',array_keys($data)) . '`';
		$values = implode("','",$data);
		$result = $this->query("insert into {$this->table} ({$keys}) values ('{$values}')");
		
		return $this->insert_id();
	}
	
	public function insert_id() {
		return $this->mysqli->insert_id;
	}
	
	public function delete() {
		$result = $this->query("delete from {$this->table}  {$this->options['join']} {$this->options['where']} {$this->options['group']} {$this->options['having']} {$this->options['order']} {$this->options['limit']}");
		$this->clearOptions();
		
		return $this;
	}
	
	public function affected_rows() {
		return $this->mysqli->affected_rows;
	}
	
	public function order($column,$type = 'asc') {
		$this->options['order'] = ' order by ' . $column . ' ' . $type . ' ';
		return $this;
	}
	
	public function limit(...$limit) {
		$limit = implode(',',$limit);
		$this->options['limit'] = ' limit ' . $limit;
		return $this;
	}
	
	public function join($table,$type = '') {
		$this->options['join'] = " {$type} join {$this->prefix}{$table} ";
		return $this;
	}
	
	public function total() {
		return $this->select('count(*) as total')->getOne()['total'];
	}
	
	public function page($page,$count = 30) {
		$start = abs((intval($page) - 1) * $count);
		return $this->limit($start,$count);
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function list() {
		return $this->select()->getAll();
	}
	
	public function last() {
		return $this->order('time','desc')->limit(1)->select()->getOne();
	}
	
	public function from($time,$column = 'time') {
		$time = is_string($time) ? strtotime($time) : $time;
		return $this->and($column . ' >= ' . $time);
	}
	
	public function to($time,$column = 'time') {
		$time = is_string($time) ? strtotime($time) : $time;
		return $this->and($column . ' <= ' . $time);
	}
	
	public function sum($column) {
		return $this->select("sum({$column}) as sum")->getOne()['sum'];
	}
	
	public function clearOptions() {
		if($this->clear) {
			$this->options = [];
		}
	}
	
	public function setClear($clear) {
		$this->clear = $clear;
		return $this;
	}
	
	public function __get($key) {
		return $this->select()->getOne()[$key];
	}
	
	public function __set($key,$value) {
		return $this->update([$key => $value]);
	}
	
	public function __destruct() {
		$this->mysqli->close();
	}
}