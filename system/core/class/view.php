<?php
class view
{

	private $config;

	function __construct($viewname)
	{
		$this->config = new viewConfig();
		$path = realpath($this->config->path . DIRECTORY_SEPARATOR . $viewname . '.' . ltrim($this->config->suffix, '.'));
		$this->templateContent = file_get_contents($path);
	}

	function display()
	{
	}

	function assign($var, $val)
	{
	}
}