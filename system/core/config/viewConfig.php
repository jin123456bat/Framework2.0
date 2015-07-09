<?php
namespace system\core\config;

use system\core\inter\config;

/**
 * 模板引擎配置
 *
 * @author 程晨
 *        
 */
class viewConfig extends config
{

	function __construct()
	{
		$this->path = ROOT . '/system/template/';
		$this->suffix = 'html';
		$this->leftContainer = '{%';
		$this->rightContainer = '%}';
		
		/**
		 * 标签嵌套最大次数
		 */
		$this->containerTimes = 3;
	}
}