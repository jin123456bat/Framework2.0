<?php
class mysqlConfig extends config
{
	public $db_type = 'mysql';
	public $db_server = 'localhost';
	public $db_dbname = '';
	public $db_user = 'root';
	public $db_password = '';
	public $db_forever = false;
	public $db_charset = 'utf-8';
}