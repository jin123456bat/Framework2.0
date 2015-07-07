<?php
namespace system\core;

/**
 * 视图以及模板管理
 *
 * @author 程晨
 *        
 */
class view extends base
{

	private $_config;

	private $_templateContent;

	function __construct($viewConfig, $viewname)
	{
		$this->_config = $viewConfig;
		$path = realpath($this->_config['path'] . DIRECTORY_SEPARATOR . $viewname . '.' . ltrim($this->_config->suffix, '.'));
		$this->_templateContent = file_get_contents($path);
		parent::__construct();
	}

	/**
	 * 缓存并返回模板
	 *
	 * @return mixed
	 */
	function display()
	{
		$cacheConfig = config('cache');
		if($cacheConfig['cache'])
		{
			$cache = cache::getInstance($cacheConfig);
			if($cache->check($this->http->url()))
			{
				$cache->write($this->http->url(), $this->_templateContent);
			}
		}
		return $this->_templateContent;
	}

	/**
	 * 替换模板变量
	 *
	 * @param unknown $var        	
	 * @param unknown $val        	
	 */
	function assign($var, $val)
	{
		$pattern = '/{%\s*\$' . $var . '\s*%}/';
		$this->_templateContent = preg_replace($pattern, $val, $this->_templateContent);
	}
}