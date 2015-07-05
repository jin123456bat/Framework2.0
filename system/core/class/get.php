<?php
class get
{

	function __get($var)
	{
		return isset($_GET[$var]) ? (new variable($_GET[$var]))->trim() : NULL;
	}
}