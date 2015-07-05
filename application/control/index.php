<?php
class indexControl extends control
{
	function index()
	{
		$this->view->assign("index","你好啊");
	}
}