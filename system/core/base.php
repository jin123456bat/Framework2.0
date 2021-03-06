<?php
namespace system\core;

/**
 * webApplication基类
 *
 * @author 程晨
 *        
 */
class base
{

	/**
	 * post类
	 *
	 * @var $_POST
	 */
	protected $post;

	/**
	 * get类
	 *
	 * @var $_GET
	 */
	protected $get;

	/**
	 * file类
	 *
	 * @var file
	 */
	protected $file;

	/**
	 * $_SESSION
	 * 
	 * @var unknown
	 */
	protected $session;
	
	/**
	 * $_COOKIE
	 * @var unknown
	 */
	protected $cookie;

	/**
	 * http管理
	 *
	 * @var http
	 */
	protected $http;

	function __construct()
	{
		$this->session = session::getInstance();
		$this->post = post::getInstance();
		$this->get = get::getInstance();
		$this->http = http::getInstance();
		$this->file = file::getInstance();
		$this->cookie= cookie::getInstance();
	}
	
	/**
	 * 载入数据模型
	 * @param string $name 模块名
	 * @return model
	 */
	function model($name)
	{
		static $instance = array();
		if(!isset($instance[$name]))
		{
			$path = ROOT.'/application/model/'.$name.'.php';
			if (file_exists($path))
			{
				include_once $path;
				$model = "application\\model\\" . $name . 'Model';
				$instance[$name] = new $model($name);
			}
			else
			{
				$instance[$name] = new model($name);
			}
		}
		return $instance[$name];
	}
	
	/**
	 * 获取request参数
	 * @param unknown $name
	 * @param string $default
	 * @param callable $callback
	 */
	function request($name,$default = NULL,callable $callback = NULL)
	{
		if (isset($_REQUEST[$name]))
		{
			$result = $_REQUEST[$name];
		}
		$result = $default;
		if (is_callable($callback))
		{
			if (is_string($result))
			{
				return call_user_func($callback,$_POST[$name]);
			}
			else if (is_array($result))
			{
				return call_user_func_array($callback, $_POST[$name]);
			}
		}
		return $result;
	}
	
	/**
	 * 获取get参数
	 * @param unknown $name
	 * @param string $default
	 * @param callable $callback
	 * @return mixed|string
	 */
	function get($name,$default = NULL,callable $callback = NULL)
	{
		if (isset($_GET[$name]))
		{
			$result = $_GET[$name];
		}
		else
		{
			$result = $default;
		}
		if (is_callable($callback))
		{
			return $callback($result);
		}
		return $result;
	}
	
	/**
	 * 获取post参数
	 * @param unknown $name
	 * @param string $default
	 * @param callable $callback
	 * @return unknown|string
	 */
	function post($name,$default = NULL,callable $callback = NULL)
	{
		if (isset($_POST[$name]))
		{
			$result = $_POST[$name];
		}
		else
		{
			$result = $default;
		}
		if (is_callable($callback))
		{
			return $callback($result);
		}
		return $result;
	}
}