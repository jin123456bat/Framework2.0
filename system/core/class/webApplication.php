<?php
class webApplication extends base
{
	private $_config;
	
	function __construct($config)
	{
		$this->_config = $config;
		parent::__construct();
	}

	/**
	 * 入口点
	 */
	function run()
	{
		$cacheConfig = new cacheConfig();
		if($cacheConfig['cache'])
		{
			$cache = new cache($cacheConfig);
			if($content = $cache->check($this->http->url()))
			{
				echo $content;
			}
		}
		try 
		{
			$handler = $this->parseUrl();
			if(is_array($handler))
			{
				list($control,$action) = $handler;
				include ROOT.'/application/control/'.$control.'.php';
				$class = $control.'Control';
				if(class_exists($class))
				{
					$class = new $class();
					if(method_exists($class, $action))
					{
						$this->__200($class,$action);
					}
					else
					{
						$this->__404();
					}
				}
				else
				{
					$this->__404();
				}
			}
		}
		catch(Exception $e)
		{
			$this->__500();
		}
	}
	
	/**
	 * Url解析
	 */
	function parseUrl()
	{
		
		switch($this->_config['pathmode'])
		{
			case 'pathinfo':
				$pos = stripos($_SERVER['REQUEST_URI'], '.php/');
				if($pos)
				{
					$path = substr( $_SERVER['REQUEST_URI'], $pos+5);
					$path = explode('/', $path);
					$control = !isset($path[0]) || empty($path[0])?$this->_config['default_control']:$path[0];
					$action = !isset($path[1]) || empty($path[1])?$this->_config['default_action']:$path[1];
					for($i = 2;$i<count($path);$i+=2)
					{
						$_GET[$path[$i]] = $path[$i+1];
						$_REQUEST[$path[$i]] = $path[$i+1];
					}
				}
				else
				{
					$control = $this->_config['default_control'];
					$action = $this->_config['default_action'];
				}
				break;
			default:
				$control = $this->get->c;
				$action = $this->get->a;
		}
		if ($control === $this->_config['thread_enter'])
		{
			return $action;
		}
		return array($control,$action);
	}
	
	function __200($control,$action)
	{
		$this->http->header('ok', 200);
		ob_start();
		$control->$action();
		$content = ob_get_contents();
		ob_end_clean();
		echo $content;
	}
	
	function __404()
	{
		
	}
	
	function __500()
	{
		
	}
}