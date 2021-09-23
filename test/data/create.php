<?php
$dbms     = 'mysql';     // 数据库类型。
$host     = 'localhost'; // 数据库主机名。
$port     = '3306';      // 数据库主机端口。
$dbName   = 'zdata';     // 使用的数据库。
$user     = 'root';      // 数据库连接用户名。
$password = 'root';      // 对应的密码。

$dsn = "$dbms:host=$host:$port;dbname=$dbName";

$dirPath = 'demo/story'; // 自定义数据的zdata文件路径。

/* 定义每个yaml文件生成SQL数量，自定义yaml文件名需和数据库表名对应。以下定义中键代表数据表名，值代表生成数量。*/
$zdataCount = array();
$zdataCount['zt_product']     = 100;
$zdataCount['zt_productplan'] = 500;
$zdataCount['zt_project']     = 100;
$zdataCount['zt_module']      = 1000;
$zdataCount['zt_story']       = 10000;
$zdataCount['zt_storyspec']   = 10000;
$zdataCount['zt_action']      = 10000;

$zdataExec = "zd -c $dirPath/%s -d %s -n %s -o $dirPath/sql/%s.sql -table %s";

set_time_limit(0);
try
{
    $dbh = new PDO($dsn, $user, $password); // 初始化一个PDO对象。
    echo $dbms . '连接成功' . PHP_EOL;

    $yamlFilePath = scandir($dirPath);
    foreach($yamlFilePath as $file)
    {
        if(strpos($file, '.yaml'))
        {
            $fileName = basename($file, '.yaml');
            $count    = isset($zdataCount[$fileName]) ? $zdataCount[$fileName] : 10;
            $execYaml = sprintf($zdataExec, $file, $file, $count, $fileName, $fileName);
            exec($execYaml); // 执行zdata命令调用yaml文件生成SQL数据。

            echo '执行命令:' . $execYaml . PHP_EOL;
        }
    }

    /* 读取生成的SQL文件,导入数据到数据库。*/
    $sqlDir      = $dirPath . '/sql/';
    $sqlFilePath = scandir($sqlDir);

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 设置报错处理方式。
    $dbh->exec("SET GLOBAL sql_mode = '';");
    $dbh->exec("set names 'utf8'");
    $dbh->beginTransaction();

    foreach($sqlFilePath as $file)
    {
        if(strpos($file, '.sql'))
        {
            $tableName = basename($file, '.sql');

            $truncate  = 'truncate table ' . $tableName;
            $dbh->exec($truncate);

            $sql   = file_get_contents($sqlDir . $file);
            $count = $dbh->exec($sql);
            echo $tableName . '表影响行数:' . $count . PHP_EOL;
        }
    }

    $dbh->commit();
    $dbh = null; // 关闭连接。
}
catch (PDOException $e)
{
    die('Error!: ' . $e->getMessage() . PHP_EOL);
}
