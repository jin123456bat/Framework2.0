<?php
/**
 * 模板引擎配置
 * @author 程晨
 *
 */
class viewConfig extends config
{

	function __construct()
	{
		$this->path = ROOT . '/application/template/';
		$this->suffix = 'html';
		$this->leftContainer = '{%';
		$this->rightContainer = '%}';
		$this->cachePath = ROOT.'/system/cache/template';
	}
}