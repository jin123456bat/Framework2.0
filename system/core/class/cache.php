<?php
/**
 * 缓存
 * @author 程晨
 */
class cache extends base
{
	private $_config;
	
	public function __construct($config)
	{
		$this->_config = $config;
	}
	
	/**
	 * 检查缓存是否存在
	 * @param unknown $url
	 * @return string|NULL
	 */
	public function check($url)
	{
		$md5 = md5($url);
		$file = trim($this->_config['path'],'/').'/'.$md5.'.'.$this->_config['suffix'];
		if(is_file($file))
		{
			$mtime = filemtime($file);
			if($_SERVER['REQUEST_TIME'] - $mtime < $this->_config['time'])
				return file_get_contents($file);
		}
		return NULL;
	}
	
	/**
	 * 写入缓存
	 * @param unknown $url
	 * @param unknown $content
	 * @return int
	 */
	public function write($url,$content)
	{
		$md5 = md5($url);
		$file = trim($this->_config['path'],'/').'/'.$md5.'.'.$this->_config['suffix'];
		return file_put_contents($file, $content);
	}
}