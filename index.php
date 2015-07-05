<?php
/* 定义根目录 */
defined('ROOT') or define('ROOT', __DIR__);
/* 导入global向导 */
require_once ROOT . '/global.php';

$config = new systemConfig();
foreach ($config as $key => $value) {
	echo $key . '=>' . $value . '<br>';
}
exit();
(new webApplication($config))->run();