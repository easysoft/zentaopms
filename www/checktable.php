<?php
error_reporting(0);

include '../framework/helper.class.php';
include '../config/config.php';
define('DS', DIRECTORY_SEPARATOR);
session_start();
?>
<?php
$webRoot   = $config->webRoot;
$themeRoot = $webRoot . "theme/";

$type = !isset($_GET['type']) ? 'check' : $_GET['type'];
if($type != 'check' and $type != 'repair') die();
if(!isset($_SESSION['checkFileName']))
{
    $checkFileName = dirname(__FILE__) . DS . uniqid('repair_') . '.txt';
    $_SESSION['checkFileName'] = $checkFileName;
}

$checkFileName = $_SESSION['checkFileName'];

$status = '';
if(!file_exists($checkFileName) or (time() - filemtime($checkFileName)) > 60 * 10) $status = 'createFile';

$lang = new stdclass();
$lang->misc = new stdclass();
include '../module/misc/lang/zh-cn.php';
if($status == 'createFile')
{
    $lang->user = new stdclass();
    $lang->projectCommon = '';
    include '../module/user/lang/zh-cn.php';
}
else
{
    $dsn = "mysql:host={$config->db->host}; port={$config->db->port}; dbname={$config->db->name}";
    $dbh = new PDO($dsn, $config->db->user, $config->db->password, array(PDO::ATTR_PERSISTENT => $config->db->persistant));
    $dbh->exec("SET NAMES {$config->db->encoding}");

    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = array();
    $stmt = $dbh->query("show full tables");
    while($table = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $tableName = $table["Tables_in_{$config->db->name}"];
        $tableType = strtolower($table['Table_type']);
        if($tableType == 'base table')
        {
            $tableStatus = $dbh->query("$type table $tableName")->fetch();
            $tables[$tableName] = strtolower($tableStatus->Msg_text);
        }
    }
    $status = 'check';
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name="renderer" content="webkit"> 
  <link rel='stylesheet' href='<?php echo $themeRoot . 'zui/css/min.css'?>' type='text/css' media='screen' />
</head>
<body>
<div class='alert alert-info'><strong><?php echo $lang->misc->repairTable;?></strong></div>
<div class='container mw-700px'>
<?php if($status == 'createFile'):?>
  <div class='panel-body'>
    <?php
    $checkFileName = $_SESSION['checkFileName'];
    if(!($_SERVER['SERVER_ADDR'] == '127.0.0.1' or filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false))
    {
        $checkFileName = str_replace(dirname(dirname(__FILE__)) . DS, '', $checkFileName);
    }
    printf($lang->user->noticeResetFile, $checkFileName);
    ?>
  </div>
  <p><a href='<?php echo $config->webRoot . 'checktable.php';?>' class='btn'><i class='icon-refresh'></i></a></p>
<?php elseif($status == 'check'):?>
  <div class='panel'>
    <table class='table table-form'>
      <thead>
        <tr>
          <th><?php echo $lang->misc->tableName?></th>
          <th><?php echo $lang->misc->tableStatus?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php $needRepair = false;?>
      <?php foreach($tables as $tableName => $tableStatus):?>
      <?php if($tableStatus != 'ok') $needRepair = true;?>
        <tr>
          <td><?php echo $tableName;?></td>
          <td><span style='color:<?php echo $tableStatus == 'ok' ? 'green' : 'red'?>'><?php echo $tableStatus;?></span></td>
          <td><?php if($type == 'repair' and $tableStatus != 'ok') echo "修复失败，请到该数据库的数据目录下，尝试执行<code>myisamchk -r -f {$tableName}.MYI</code>进行修复。"?></td>
        </tr>
      </tbody>
      <?php endforeach;?>
      <?php if($needRepair):?>
      <tfoot>
        <tr><td class='text-center' colspan='3'><a href='<?php echo $config->webRoot . 'checktable.php?type=repair'?>' class='btn btn-primary'><?php echo $lang->misc->needRepair?></a></td></tr>
      </tfoot>
      <?php endif;?>
    </table>
  </div>
<?php endif;?>
</div>
</body>
</html>
