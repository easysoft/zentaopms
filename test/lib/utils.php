<?php
/**
 * Utils for ZenTaoPMS testing.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guanxing <guanxiying@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

/**
 * Run test cases of a directory using ztf.
 *
 * @param  string    $dir
 * @access public
 * @return void
 */
function ztfRun($dir)
{
    global $config;

    $ztfPath = dirname(dirname(__FILE__)) . '/tools/ztf';
    $command = "$ztfPath $dir";
    system($command);
}

/**
 * Run scripts of a directory using zendata.
 *
 * @param  string    $dir
 * @access public
 * @return void
 */
function zdRun($dir)
{
    $frameworkRoot = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;
    include $frameworkRoot . 'helper.class.php';

    $zdRoot  = dirname(dirname(__FILE__)) . '/' . $dir;
    $zdPath  = dirname(dirname(__FILE__)) . '/tools/zd';

    set_time_limit(0);
    try
    {
        $config = new stdclass();
        $config->db = new stdclass();
        include dirname(dirname(dirname(__FILE__))) . '/config/my.php';

        $dsn = "mysql:host={$config->db->host}:{$config->db->port};dbname={$config->db->name}";
        $dbh = new PDO($dsn, $config->db->user, $config->db->password);
        echo 'Connect success' . PHP_EOL;
    
        $command = "$zdPath -c $zdRoot/%s -d %s -n %s -o $zdRoot/sql/%s.sql -table %s";
        $yamlFilePath = scandir($zdRoot);
        foreach($yamlFilePath as $file)
        {
            if(strpos($file, '.yaml'))
            {
                $fileName = basename($file, '.yaml');
                $count    = isset($zdataCount[$fileName]) ? $zdataCount[$fileName] : 10;
                $execYaml = sprintf($command, $file, $file, $count, $fileName, $fileName);
                exec($execYaml); // create sql files.
    
                echo 'Execute:' . $execYaml . PHP_EOL;
            }
        }
    
        /* Export SQL files to mysql. */
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
        $dbh = null;
    }
    catch (PDOException $e)
    {
        die('Error!: ' . $e->getMessage() . PHP_EOL);
    }
}

/**
 * Print usage of zdtest.
 *
 * @access public
 * @return void
 */
function printUsage()
{
    echo <<<EOT
zdtest is a test tool for ZenTaoPMS.

Usage:

    zdtest <command>

    The commands are:

        init         only init data and files
        control      run unit test for controllers
        model        run unit test for models
        ui           ui testing 
        all          run all tests
        clean        remove data, files and cached files
    

EOT;
}
