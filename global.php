<?php
/*
 * 引入系统函数
 */
array_map(function ($filename) {
	if ($filename != '.' && $filename != '..') {
		require ROOT . '/system/core/function/' . $filename;
	}
}, scandir(ROOT . '/system/core/function'));
/*
 * 引入用户自定义函数
 */
array_map(function ($filename) {
	if ($filename != '.' && $filename != '..') {
		require ROOT . '/application/function/' . $filename;
	}
}, scandir(ROOT . '/application/function/'));
/*
 * 引用系统类库
 */
spl_autoload_register(function ($classname) {
	$path = array(
		ROOT . '/system/core/interface/' . $classname . '.php',
		ROOT . '/system/core/config/' . $classname . '.php',
		ROOT . '/system/core/class/' . $classname . '.php',
		ROOT . '/application/class/' . $classname . '.php'
	);
	array_map(function ($file) {
		if (is_file($file)) {
			require_once $file;
		}
	}, $path);
});