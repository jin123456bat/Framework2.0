<?php
class get
{

	function __get($name)
	{
		return isset($_GET[$name]) ? (new variable($_GET[$name]))->trim() : NULL;
	}
}