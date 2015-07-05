<<<<<<< HEAD
<?php
/*定义根目录*/
defined('ROOT') or define('ROOT',__DIR__);
/*导入global向导*/
require_once ROOT.'/global.php';

$config = new systemConfig();
(new webApplication($config))->run();
=======
<?php
defined('ROOT') or define('ROOT', __DIR__);
defined('Framework') or define('Framework',true);

require ROOT.'/global.php';
$app = webApplication::init(ROOT.'/system/config/system.php');
$app->run();
?>
>>>>>>> 025a0d0411b64d35527e854b9d4c9d0169b22c7b
