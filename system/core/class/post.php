<?php
class post
{

	function __get($name)
	{
		return isset($_POST[$name]) ? (new variable($_POST[$name]))->trim() : NULL;
	}
}