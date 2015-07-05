<?php
class base
{
	protected $post;
	protected $get;
	protected $file;
	protected $thread;
	
	function __construct()
	{
		$this->post = new post();
		$this->get = new get();
		
	}
}