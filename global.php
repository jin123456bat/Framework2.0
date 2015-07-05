<<<<<<< HEAD
<?php
/*
 * 引入系统函数
 */
array_map(function($filename){
	if($filename != '.' && $filename != '..')
	{
		require_once ROOT.'/system/core/function/'.$filename;
	}
},scandir(ROOT.'/system/core/function'));
/*
 * 引入用户自定义函数
 */
array_map(function($filename){
	if($filename != '.' && $filename != '..')
	{
		require_once ROOT.'/system/core/function/'.$filename;
	}
},scandir(ROOT.'/system/core/function'));
/*
 * 引用系统类库
 */
spl_autoload_register(function($classname){
	$path = array(
		ROOT.'/system/core/class/'.$classname.'.php',
		ROOT.'/system/core/config/'.$classname.'.php',
		ROOT.'/application/class/'.$classname.'.php'
	);
	array_map(function($file){
		if(is_file($file))
		{
			require_once $file;
		}
	}, $path);
});
=======
<?php
>>>>>>> 025a0d0411b64d35527e854b9d4c9d0169b22c7b
