<?php
if(empty($argv[1])) die('Must request a param');
$filePath   = $argv[1];
$users      = empty($argv[2]) ? 0 : $argv[2];
$company    = empty($argv[3]) ? '' : $argv[3]; // 如果没有，try (type = 180d)
$type       = empty($argv[4]) ? '' : $argv[4]; // try ->30d   year ->365d  or 60 -> 60d
$ip         = empty($argv[5]) ? '' : $argv[5];
$mac        = empty($argv[6]) ? '' : $argv[6];
$dirName    = basename($filePath);
define('PASSWORD', md5(md5('Zentao Pro editor') . 'cnezsoft'));
echo "Removing file\n";
`rm -rf /tmp/$dirName`;
echo "Copying file\n";
`cp -rf $filePath /tmp`;
include dirname(dirname(__FILE__)) . '/framework/control.class.php';
$filePath = "/tmp/$dirName";
$defaultValue = "";
$moduleDir    = $filePath . '/module/';
$modules      = glob($moduleDir . '*');
echo "Seting default value for control\n";
$notice = <<<EOD
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>Error</title>
</head>
<body>
您版本的用户数是{\$properties['user']['value']}，已经超过该版本的人数限制，请联系我们<br>
email：<a href='mailto:zentao@cnezsoft.com'>zentao@cnezsoft.com</a><br>
电话：4006 889923<br>
网址：<a href='http://www.zentao.net/goto.php?item=buypro'>www.zentao.net</a><br>
<br><br>
The number of users is {\$properties['user']['value']} for the edition and has exceeded the limit, please contact us.<br>
email:<a href='mailto:zentao@cnezsoft.com'>zentao@cnezsoft.com</a><br>
tel:4006 889923<br>
Web:<a href='http://www.zentao.net/goto.php?item=buypro'>www.zentao.net</a><br>
</body>
</html>
EOD;
$limitUser =<<<EOD
if(function_exists('ioncube_license_properties')) \$properties = ioncube_license_properties();
\$user = \$this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch();
if(!empty(\$properties['user']) and \$properties['user']['value'] < \$user->count) die("$notice");
EOD;
$limitFunc =<<<EOD
public function __construct()
{
    parent::__construct();
    $limitUser
}
EOD;
foreach($modules as $module)
{
    $controlPath = $module . '/control.php';
    $className   = basename($module);
    if(file_exists($controlPath))
    {
        echo "Seting value for $className\n";
        include $controlPath;
        $reflection = new ReflectionClass($className);
        if(method_exists($className, '__construct'))
        {
            $construct = new ReflectionMethod($className, '__construct');
            $fileName  = $construct->getFileName();
            $controlContent  = file_get_contents($controlPath);
            $controlLines    = explode("\n", $controlContent);
            if($controlPath == $fileName)
            {
                $endLine = $construct->getEndLine() - 1;
                $controlLines[$endLine] = $limitUser . "\n" . $controlLines[$endLine];
            }
            else
            {
                $methods = $reflection->getMethods();
                $startLine = $methods[0]->getStartLine() - 1;
                $controlLines[$startLine] = $limitFunc . "\n" . $controlLines[$startLine];
            }
            $controlContent = join("\n", $controlLines);
            file_put_contents($controlPath, $controlContent);
        }
        foreach($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            $methodName = strtolower($method->name);
            /* This method is not extend from parent */
            if($method->getFileName() != $controlPath) continue;
            if($methodName == '__construct') continue;
            $params = $method->getParameters();
            foreach($params as $param)
            {
                $paramName = $param->getName();
                if($param->isDefaultValueAvailable()) $defaultValue .= "\$paramDefaultValue['$className']['$methodName']['$paramName'] = '" . $param->getDefaultValue() . "';\n";
            }
        }
    }
    /* Set ext control default value.*/
   $extControlPath = $module . '/ext/control/';
   if(is_dir($extControlPath))
   {
       echo "Seting value for extension of $className\n";
       chdir($extControlPath);
       $extControls = glob($extControlPath . '*');
       if(empty($extControls)) continue;
       foreach($extControls as $extControl)
       {
           $methodName = strtolower(substr(basename($extControl), 0, strrpos(basename($extControl), '.')));
           $extFile    = file_get_contents($extControl);
           $extLines = explode("\n", $extFile);
           foreach($extLines as $line)
           {
               $line = trim($line);
               if(preg_match("/^class +($className) +extends +/i", $line, $class) == 1)   $extClassName = strtolower($class[1]);
               if(preg_match("/^class +(my$className) +extends +/i", $line, $class) == 1) $extClassName = strtolower($class[1]);
               if(preg_match("/^(public)?\s+function\s+$methodName/i", $line) == 1)
               {
                   $params = strstr($line, '(');
                   if(preg_match('/^(.+)$/', $params) == 0) continue;
                   $params = trim(str_replace(array('(', ')'), '', $params));
                   $params = preg_replace('/, *\$/', ',\$', $params);
                   $params = explode(',$', trim($params, '$'));
                   foreach($params as $param)
                   {
                       if(strpos($param, '=') === false) continue;
                       $pair          = explode('=', $param);
                       $paramName     = trim($pair[0]);
                       $paramValue    = trim(end($pair));
                       $defaultValue .= "\$paramDefaultValue['$extClassName']['$methodName']['$paramName'] = $paramValue;\n";
                   }
               }
           }
       }
   }
}
if($defaultValue)
{
    echo "Writing default value to file\n";
    $valueFile  = "<?php\n";
    $valueFile .= $defaultValue;
    $tmp = $filePath . '/tmp/';
    $defaultDir = $tmp . 'defaultvalue/';
    if(!is_dir($tmp))
    {
        mkdir($tmp);
        chmod($tmp, 0777);
    }
    if(!is_dir($defaultDir))
    {
        mkdir($defaultDir);
        chmod($defaultDir, 0777);
    }
    file_put_contents($defaultDir . basename($filePath) . '.php', $valueFile);
}
$file = "/tmp/$dirName";
$noLoader = "
'<html>
  <head>
    <meta http-equiv=\'content-type\' content=\'text/html; charset=utf-8\' />
    <title>error</title>
  </head>
  <body>
    <h1 style=\'color:red;text-align:center\'>未安装 ioncube loader</h1>
    <p>网站错误：文件'.__file__.'需要安装ioncube php loader 解析，该网站需要安装'.basename(\\\$__ln).'。如果你是网站所有者，请使用<a href=\'http://www.ioncube.com/lw/\'>ioncube loader安装向导</a>。</p>
  </body>
</html>'
";

/* encrypt file*/
echo "Encrypting extension\n";
if(!is_dir('/tmp/encrypt'))mkdir("/tmp/encrypt");

$withLicense = '';
$order->account = $company;
$order->users   = $users;
$order->ip      = $ip;
$order->mac     = $mac;
$order->type    = $type;
$passphrase     = PASSWORD;
createLicense($order, $dirName, '/tmp/encrypt/');
$withLicense = "--with-license config/license/" . basename($file) . ".txt --passphrase $passphrase";
$callbackFile = dirname(__FILE__) . "/callback.php";
echo `cp $callbackFile /tmp/encrypt/$dirName/config/license/`;
$callback = "--callback-file config/license/callback.php";

exec("/usr/local/ioncube/ioncube_encoder5 --copy config.php --copy phpexcel/ --copy tmp/ --copy hook/ --copy framework/ --copy config/ --copy view/ --copy lang/ $withLicense $callback --message-if-no-loader \"$noLoader\" $file --update-target --into /tmp/encrypt/", $outError);
foreach($outError as $error)
{
    $errorFile    = substr($error, 0 , strpos($error, ':'));
    $relativeFile = substr($errorFile, strrpos($errorFile, '/' . basename($file) . '/') + 1) . "\n";
    echo "Copying $errorFile\n";
    `cp $errorFile /tmp/encrypt/$relativeFile`;
}
echo "Ziping extension\n";
if(file_exists("/tmp/encrypt/$dirName$company.zip")) `rm /tmp/encrypt/$dirName$company.zip`;
`cd /tmp/encrypt/; zip -rm -9 $dirName$company.zip $dirName`;
echo "$dirName$company.zip Finished\n";

function createLicense($order, $saveName, $encryptPath)
{
    echo "Creating license.\n";
    if(!is_dir($encryptPath . $saveName))mkdir($encryptPath . $saveName);
    if(!is_dir($encryptPath . $saveName . "/config"))mkdir($encryptPath . $saveName . '/config');
    if(!is_dir($encryptPath . $saveName . "/config/license"))mkdir($encryptPath . $saveName . "/config/license");

    $property  = empty($order->account) ? "company='try'" : "company='$order->account'";
    $property .= $order->users == 0 ? '' : ",user=$order->users";
    $property = "--property \"$property\"";

    $server = empty($order->ip) ? '' : '127.0.0.1,' . $order->ip;
    $server = !empty($order->mac) ? empty($server) ? "'{{$order->mac}}'" : "'$server{{$order->mac}}'" : $server;
    $server = empty($server) ? '' : '--allowed-server ' . $server;

    $expire   = empty($order->account) ? '--expire-in 186d' : '';
    $expire  = $order->type == 'year' ? "--expire-in 372d" : $expire;
    $expire  = $order->type == 'try' ? "--expire-in 31d" : $expire;
    $expire  = is_numeric($order->type) ? "--expire-in {$order->type}d" : $expire;
    $expire  = $order->type == 'life' ? "" : $expire;

    $passphrase = PASSWORD;
    $license = $encryptPath . $saveName . '/config/license/' . $saveName . '.txt';
    echo `/usr/local/ioncube/make_license $property $server $expire --passphrase $passphrase -o $license`;
}
