<?php
class viewConfig
{

	function __construct()
	{
		$this->path = ROOT . '/application/template';
		$this->suffix = 'html';
		$this->leftContainer = '{%';
		$this->rightContainer = '%}';
	}
}