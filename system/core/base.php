<?php
namespace system\core;

/**
 * webApplication基类
 *
 * @author 程晨
 *        
 */
class base
{

	/**
	 * post类
	 *
	 * @var $_POST
	 */
	protected $post;

	/**
	 * get类
	 *
	 * @var $_GET
	 */
	protected $get;

	/**
	 * file类
	 *
	 * @var $_FILES
	 */
	protected $file;

	/**
	 * 线程类
	 *
	 * @var class thread
	 */
	protected $thread;

	/**
	 * http管理
	 *
	 * @var unknown
	 */
	protected $http;

	function __construct()
	{
		$this->post = new post();
		$this->get = new get();
		$this->http = new http();
	}
}