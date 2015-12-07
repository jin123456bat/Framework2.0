<?php
namespace system\core;
/**
 * 控制器
 *
 * @author 程晨
 *        
 */
class control extends base
{
	/**
	 * response管理
	 *
	 * @var response
	 */
	public $response;

	/**
	 * 模板管理
	 *
	 * @var view
	 */
	public $view;

	/**
	 * 线程管理
	 *
	 * @var thread
	 */
	protected $thread;

	function __construct()
	{
		parent::__construct();
		$this->thread = new thread();
	}

	
	
	/**
	 * 调用另外一个control中的action方法
	 */
	function call($c,$a)
	{
		if(is_object($c))
		{
			return $c->$a();
		}
		else
		{
			static $class = array();
			$key = $c;
			if (!isset($class[$key]))
			{
				$path = ROOT.'/application/control/'.$c.'.php';
				include_once $path;
				$control = 'application\\control\\'.$c.'Control';
				$class[md5($c)] = (new $control());
			}
			$content = $class[$key]->$a();
			return $content;
		}
	}
}