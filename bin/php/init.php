<?php
$phpcli  = $argv[1];
$pmsRoot = $argv[2];

$basePath = dirname(__FILE__, 3);
chdir($basePath);

include './framework/router.class.php';
include './framework/control.class.php';
include './framework/model.class.php';
include './framework/helper.class.php';

try
{
    $app = router::createApp('pms', $basePath, 'router');
}
catch (Exception $exception)
{
    die("Connect database fail. Please open mysql service.\n");
}

$suffix      = stripos(PHP_OS, 'WIN') !== false ? '.bat' : '.sh';
$requestType = $app->config->requestType;
$binPath     = $basePath . '/bin';
$crond       = "# system cron.\n";
$crond      .= "#min \t hour \t day \t month \t week \t command.\n";

/* Remove old cron. */
foreach(glob("$binPath/*{$suffix}") as $file)
{
    $fileName = basename($file);
    if($fileName == "init{$suffix}") continue;
    unlink($file);
}

$crons = $app->dbh->query('SELECT * FROM ' . TABLE_CRON . " WHERE `type`='zentao' AND `status`='normal' AND `buildin`='1'")->fetchAll();
foreach($crons as $cron)
{
    parse_str($cron->command, $params);
    if(empty($params)) continue;

    $command = '';
    if($requestType == 'PATH_INFO')
    {
        $command = "$phpcli $binPath/ztcli '$pmsRoot/{$params['moduleName']}-{$params['methodName']}.html'";
    }
    else
    {
        $command = "$phpcli $binPath/ztcli '$pmsRoot/index.php?m={$params['moduleName']}&f={$params['methodName']}'";
    }

    $cronFile = strtolower($params['moduleName']) . strtolower($params['methodName']) . $suffix;
    file_put_contents($binPath . '/' . $cronFile, $command);

    $crond .= "{$cron->m} \t {$cron->h} \t {$cron->dom} \t {$cron->mon} \t {$cron->dow} \t $binPath/$cronFile \t #{$cron->remark}\n";
    echo "{$cronFile} ok.\n";
}

if(!is_dir("$binPath/cron")) mkdir("$binPath/cron", 0777, true);
file_put_contents("$binPath/cron/sys.cron", $crond);
file_put_contents("$binPath/cron" . $suffix, "$phpcli $binPath/php/crond.php\n");
echo "cron{$suffix} ok\n";

file_put_contents("$binPath/ztcli{$suffix}", "$phpcli $binPath/ztcli " . ($suffix == '.bat' ? '%' : '$') . "*\n");
echo "ztcli{$suffix} ok\n";
