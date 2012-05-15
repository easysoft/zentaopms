<?php
if(empty($argv[1])) die('Must request a param');
$filePath   = $argv[1];
$phpVersion = empty($argv[2]) ? 5 : $argv[2];
$dirName = basename($filePath);
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
您版本的用户数是{\$properties['user']}，已经超过该版本的人数限制，请联系我们<br>
email：<a href='mailto:zentao@cnezsoft.com'>zentao@cnezsoft.com</a><br>
电话：4006 889923<br>
网址：<a href='http://www.zentao.net/goto.php?item=buypro'>www.zentao.net</a><br>
<br><br>
The number of users is {\$properties['user']} for the edition and has exceeded the limit, please contact us.<br>
email:<a href='mailto:zentao@cnezsoft.com'>zentao@cnezsoft.com</a><br>
tel:4006 889923<br>
Web:<a href='http://www.zentao.net/goto.php?item=buypro'>www.zentao.net</a><br>
</body>
</html>
EOD;
$limitUser =<<<EOD
if(function_exists('ioncube_file_properties')) \$properties = ioncube_file_properties();
\$user = \$this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch();
if(!empty(\$properties) and \$properties['user'] < \$user->count) die("$notice");
EOD;
$limitFunc =<<<EOD
public function __construct()
{
    parent::__construct();
    $limitUser;
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
/* construct ext module class .*/
echo "Constructing ext module class\n";
foreach(glob("$file/module/*/ext/model/*.php") as $fileName)
{
    $className  = str_replace('.php', '', basename($fileName));
    $moduleName = str_replace(array("$file/module/", "/ext/model"), '', dirname($fileName));
    $className  = $moduleName . $className;
    $modelName  = $moduleName . "Model";
    $fileContent = file_get_contents($fileName);
    $fileLines   = explode("\n", $fileContent);
    foreach($fileLines as $num =>$line)
    {
        if(strpos(trim($line), '<?php') === 0)
        {
            $fileLines[$num] = "$line \nclass $className extends $modelName \n {";
            break;
        }
    }
    $fileLines[] = '}';
    $fileContent = join("\n", $fileLines);
    file_put_contents($fileName, $fileContent);
}
/* encrypt file*/
echo "Encrypting extension\n";
if(!is_dir('/tmp/encrypt'))mkdir("/tmp/encrypt");
exec("/home/z/ioncubeEncoder/ioncube_encoder$phpVersion --expire-in 180d --property user=3 --copy config.php --copy phpexcel/ --copy tmp/ --copy hook --copy framework/ --copy config/ --copy view/ --copy lang/ $file --update-target --into /tmp/encrypt/", $outError);
foreach($outError as $error)
{
    $errorFile    = substr($error, 0 , strpos($error, ':'));
    $relativeFile = substr($errorFile, strrpos($errorFile, '/' . basename($file) . '/') + 1) . "\n";
    echo "Copying $errorFile\n";
    `cp $errorFile /tmp/encrypt/$relativeFile`;
}
echo "Ziping extension\n";
`cd /tmp/encrypt/; zip -rm -9 $dirName$phpVersion.zip $dirName`;
echo "Finished\n";
