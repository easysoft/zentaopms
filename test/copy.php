#!/usr/bin/env php
<?php
$sourceRoot  = '/home/xieqiyu/repo/zentaopms/module/';
$moduleName  = isset($argv[1]) ? $argv[1] : '';
$targetClass = '/home/xieqiyu/repo/zentaopms/test/class/';
$targetModel = "/home/xieqiyu/repo/zentaopms/test/model/$moduleName/";
if(!$moduleName) die('请输入模块名!!! 奥利给！');
if(!is_dir($targetModel)) mkdir($targetModel);

$sourceFile = $sourceRoot  . $moduleName . '/model.php';
$classFile  = $targetClass . $moduleName . '.class.php';

$classContent = <<<EOT
<?php
class {$moduleName}Test
{
    public function __construct()
    {
         global \$tester;
         \$this->objectModel = \$tester->loadModel('$moduleName');
    }

EOT;

$file = fopen($sourceFile, "r");
/* 循环查找原文件中的public function 关键字. */
while(!feof($file))
{
    $row = fgets($file);//读取一行
    if(strpos($row, 'public function') === false) continue;

    preg_match('/[a-z]\w+(\(.*)/', $row, $match1);
    preg_match('/[a-z]\w+\(/', $row, $match2);

    $functionNameWithParam = trim($match1[0]);
    $row = preg_replace('/(\w)\(/', '$1Test(', $row);

    $classContent .= "\n$row";
    $classContent .= "    {";
    $classContent .= "\n        \$objects = \$this->objectModel->$functionNameWithParam;";
    $classContent .= "\n\n        if(dao::isError()) return dao::getError();";
    $classContent .= "\n\n        return \$objects;";
    $classContent .= "\n    }\n";

    $functionName = trim($match2[0]);
    $functionName = rtrim($match2[0], '(');
    $modelContent = <<<EOT
#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/$moduleName.class.php';
su('admin');

/**

title=测试 {$moduleName}Model->{$functionName}();
cid=1
pid=1

*/

\$$moduleName = new {$moduleName}Test();

r(\${$moduleName}->{$functionName}Test()) && p() && e();
EOT;

    $functionName = strtolower($functionName);
    $modelFile = $targetModel . $functionName . '.php';
    file_put_contents($modelFile, $modelContent);
}

$classContent .= "}";
file_put_contents($classFile, $classContent, FILE_APPEND);
