<?php
class header
{
	private $_header = array();
	
	function __construct()
	{
		$this->_header = headers_list();
	}
	
	/**
	 * 添加一个header
	 * @param unknown $key
	 * @param string $value
	 * @example
	 * add("Location: http://www.baidu.com");
	 * add("Location","http://www.baidu.com");
	 */
	function add($key,$value = NULL)
	{
		if(empty($value))
		{
			$this->_header[] = $key;
		}
		else
		{
			$this->_header[$key] = $value;
		}
	}
	
	/**
	 * 检查header是否存在
	 * @param unknown $string
	 * @return boolean
	 */
	function check($string)
	{
		if(isset($this->_header[$string]))
			return true;
		return in_array($string, $this->_header);
	}
	
	/**
	 * 删除
	 * @param unknown $string
	 */
	function delete($string)
	{
		foreach ($this->_header as $key => $value)
		{
			if($key === $string)
				unset($this->_header[$key]);
			if($key.': '.$value === $string)
				unset($this->_header[$key]);
		}
	}
	
	/**
	 * 发送一个header
	 * @param unknown $key
	 * @param string $value
	 */
	function send($key,$value = NULL)
	{
		if(empty($value))
		{
			header($key,true);
		}
		else
		{
			header($key.': '.$value,true);
		}
	}
	
	/**
	 * 发送所有hander
	 */
	function sendAll()
	{
		foreach ($this->_header as $key => $value)
		{
			if(is_int($key))
			{
				header($value,true);
			}
			else
			{
				header($key.': '.$value,true);
			}
		}
	}
}