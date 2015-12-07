<?php
namespace system\core;

use system\core\db\mysql;
/**
 * 数据模型
 *
 * @author 程晨
 *        
 */
class model
{

	private $_db;

	private $_memcache;

	private $_redis;

	private $_table;

	public $_temp;
	
	private $_sql;
	
	private $_fields;

	function __construct($table)
	{
		$this->_table = $table;
		$this->__loadDB();
		$this->__loadMemcache();
		$this->__loadRedis();
		
		$this->getTableName();
	}
	
	public function model($table)
	{
		static $array = [];
		if (!isset($array[$table]) || empty($array[$table]))
		{
			$array[$table] = new model($table);
		}
		return $array[$table];
	}
	
	public function getFields()
	{
		return $this->_fields;
	}
	
	public function setFields($fields)
	{
		$this->_fields = $fields;
	}
	
	
	/**
	 * 获取表的字段名
	 */
	private function getTableName()
	{
		if (empty($this->_fields))
		{
			$result = $this->query('select COLUMN_NAME from information_schema.COLUMNS where table_name = ? and table_schema = ?',[$this->_table,$this->_db->getDBName()]);
			foreach ($result as $field)
			{
				$this->_fields[] = $field['COLUMN_NAME'];
			}
		}
	}

	/**
	 * 载入数据库
	 */
	private function __loadDB()
	{
		$_dbConfig = config('db');
		$this->_db = mysql::getInstance($_dbConfig);
	}

	/**
	 * 载入memcache
	 */
	private function __loadMemcache()
	{
		if(memcached::ready())
		{
			$this->_memcache = new memcached(config('memcached'));
		}
	}
	
	/**
	 * 过滤式搜索
	 * @param array $filter
	 * @return \system\core\Ambigous
	 */
	function fetch(array $filter = array())
	{
		$parameter = isset($filter['parameter'])?$filter['parameter']:'*';
		if (isset($filter['start']) && isset($filter['length']))
		{
			$this->limit($filter['start'],$filter['length']);
		}
		if (isset($filter['sort']))
		{
			if (is_string($filter['sort']))
			{
				$this->orderby($filter['sort']);
			}
			else if (is_array($filter['sort']))
			{
				if (is_array($filter['sort'][0]))
				{
					foreach ($filter['sort'] as $value)
					{
						$this->orderby($value[0],$value[1]);
					}
				}
				else
				{
					$this->orderby($filter['sort'][0],$filter['sort'][1]);
				}
			}
		}
		return $this->select($parameter);
	}
	
	/**
	 * 查询一条数据
	 * @param string $parameter
	 * @return Ambigous <NULL, \system\core\Ambigous>
	 */
	function find($parameter = '*')
	{
		$result = $this->limit(1)->select($parameter);
		return isset($result[0])?$result[0]:NULL;
	}

	/**
	 * 载入redis
	 */
	private function __loadRedis()
	{
		
	}

	/**
	 * 查询多条记录
	 * @param string $field
	 * @return Ambigous <boolean, multitype:>
	 */
	public function select($field = '*',$debug = false)
	{
		$sql = 'select ' . $field . ' from ' . $this->_table.(isset($this->_temp['table'])?$this->_temp['table']:'') . ' ' . (isset($this->_temp['where'])?$this->_temp['where']:'') .(isset($this->_temp['groupby'])?$this->_temp['groupby']:'').' '.(isset($this->_temp['orderby'])?$this->_temp['orderby']:'').' '.(isset($this->_temp['limit'])?$this->_temp['limit']:'');
		$array = empty($this->_temp['where']) ? [] : $this->_temp['array'];
		if ($debug)
			return [$sql,$array];
		$result = $this->query($sql, $array);
		unset($this->_temp);
		return $result;
	}
	
	function getSql()
	{
		return $this->_sql;
	}
	
	/**
	 * 保存执行的sql
	 */
	protected function initSql($sql,$parameter = array())
	{
		$this->_sql = new \stdClass();
		$this->_sql->sql = $sql;
		$this->_sql->parameter = $parameter;
	}

	/**
	 * 增加条件
	 * 
	 * @param string $sql        	
	 * @param array $array        	
	 * @return \system\core\model
	 */
	public function where($sql, array $array = array(),$combine = 'and')
	{
		//where语句中的in操作符单独使用
		if (substr_count($sql, 'in')==1)
		{
			if(empty($array))
			{
				return $this;
			}
			$replace = implode(',', array_fill(0, count($array), '?'));
			$sql = str_replace('?', $replace, $sql);
		}
		if (isset($this->_temp['where'])) {
			$this->_temp['where'] = $this->_temp['where'] .' '. $combine.' ' .'('. $sql.')';
		} else {
			$this->_temp['where'] = 'where' . ' (' . $sql.')';
		}
		if (isset($this->_temp['array'])) {
			$this->_temp['array'] = array_merge($this->_temp['array'], $array);
		} else {
			$this->_temp['array'] = $array;
		}
		return $this;
	}
	
	

	/**
	 * 插入
	 * 
	 * @param array $array        	
	 * @return Ambigous <boolean, multitype:>
	 */
	public function insert(array $array,$debug = false)
	{
		$fields = empty($this->getFields())?'':'(`'.implode('`,`', $this->getFields()).'`)';
		if (!array_key_exists(0, $array))
		{
			//对于非数字下标的数组，重新组合数组，以满足表中的字段顺序
			$temp = [];
			foreach ($this->getFields() as $field)
			{
					$temp[$field] = $array[$field];
			}
			$array = $temp;
		}
		$parameter = '';
		foreach ($array as $key => $value) {
			if (is_int($key)) {
				$parameter .= '?,';
			} else {
				$parameter .= ':' . $key . ',';
			}
		}
		$parameter = rtrim($parameter, ',');
		$sql = 'insert into ' . $this->_table.(isset($this->_temp['table'])?$this->_temp['table']:'') .$fields.' values (' . $parameter . ')';
		if ($debug)
			return [$sql,$array];
		$result = $this->query($sql, $array);
		unset($this->_temp);
		return $result;
	}

	/**
	 * 更改
	 * 
	 * @param string|array $key
	 * @param string|NULL $value        	
	 * @return Ambigous <boolean, multitype:>
	 */
	public function update($key, $value = '',$debug = false)
	{
		if(is_array($key))
		{
			$parameter = '';
			$value = array();
			foreach ($key as $a => $b)
			{
				$parameter .= ($a.' = ?,');
				$value[] = $b;
			}
			$parameter = rtrim($parameter,',');
			$sql = 'update '.$this->_table.(isset($this->_temp['table'])?$this->_temp['table']:'').' set '.$parameter.' '.$this->_temp['where'];
		}
		else
		{
			$sql = 'update '. $this->_table.(isset($this->_temp['table'])?$this->_temp['table']:'') . ' set ' . $key . ' = ? ' . $this->_temp['where'];
			$value = array($value);
		}
		$value = isset($this->_temp['array'])?array_merge($value,$this->_temp['array']):$value;
		if ($debug)
			return [$sql,$value];
		$result = $this->query($sql, $value);
		unset($this->_temp);
		return $result;
	}

	/**
	 * 自增
	 * 
	 * @param unknown $key        	
	 * @param number $num        	
	 * @return Ambigous <boolean, multitype:>
	 */
	public function increase($key, $num = 1,$debug = false)
	{
		$sql = 'update ' . $this->_table.(isset($this->_temp['table'])?$this->_temp['table']:'') . ' set ' . $key . ' = ' . $key . ' + ? ' . $this->_temp['where'];
		$array = array_merge([$num], $this->_temp['array']);
		if ($debug)
			return [$sql,$array];
		$result = $this->_db->query($sql,$array);
		unset($this->_temp);
		return $result;
	}

	/**
	 * 删除
	 * 
	 * @return Ambigous <boolean, multitype:>
	 */
	public function delete($table = '',$debug = false)
	{
		$sql = 'delete '.$table.(isset($this->_temp['table'])?$this->_temp['table']:'').' from ' . $this->_table . ' ' . $this->_temp['where'];
		$array = empty($this->_temp['array'])?[]:$this->_temp['array'];
		if ($debug)
			return [$sql,$array];
		$result = $this->query($sql, $array);
		unset($this->_temp);
		return $result;
	}
	
	/**
	 * 增加限制规则
	 * @param unknown $start
	 * @param number $length
	 * @return \system\core\model
	 */
	public function limit($start,$length = 0)
	{
		if(empty($length))
			$this->_temp['limit'] = 'limit '.$start;
		else
			$this->_temp['limit'] = 'limit '.$start.','.$length;
		return $this;
	}
	
	/**
	 * 添加排序规则,最先添加的排序规则比重大于后面的排序规则
	 * @param string $field 排序字段
	 * @param string $asc 排序规则 默认为asc从小到大
	 * @return $this
	 */
	public function orderby($field,$asc = 'asc')
	{
		if(isset($this->_temp['orderby']))
		{
			$this->_temp['orderby'] = $this->_temp['orderby'].','.$field.' '.$asc;
		}
		else
		{
			$this->_temp['orderby'] = 'order by '.$field.' '.$asc;
		}
		return $this;
	}
	
	/**
	 * 添加分组查询条件
	 */
	public function groupby($group)
	{
		$this->_temp['groupby'] = ' group by '.$group;
	}
	
	
	/**
	 * 增加搜索表
	 * @param unknown $table
	 * @param string $mode
	 * @param string $on
	 */
	public function table($table,$mode = ',',$on = '')
	{
		if(!isset($this->_temp['table']))
		{
			$this->_temp['table'] = ' '.$mode.' '.$table.' on '.$on;
		}
		else
		{
			$this->_temp['table'] .= ' '.$mode.' '.$table.' on '.$on;
		}
		return $this;
	}
	
	public function query($sql,array $array = array(),$debug = false)
	{
		$this->initSql($sql,$array);
		if ($debug)
			return [$sql,$array];
		return $this->_db->query($sql,$array);
	}
	
	public function transaction()
	{
		return $this->_db->transaction();
	}
	
	public function commit()
	{
		return $this->_db->commit();
	}
	
	public function rollback()
	{
		return $this->_db->rollback();
	}
	
	public function lastInsertId()
	{
		return $this->_db->lastInsert();
	}
}