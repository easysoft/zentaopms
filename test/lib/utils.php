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

define('RUNTIME_ROOT', dirname(dirname(__FILE__)) . '/runtime/');
define('LIB_ROOT', dirname(dirname(__FILE__)) . '/lib/');

/**
 * Get version type of ZenTaoPMS.
 *
 * @param  $version
 * @access public
 * @return void
 */
function getVersionType($version)
{
    global $config;

    if(strpos($version, 'max') !== false) return 'max';
    if(strpos($version, 'biz') !== false) return 'biz';
    if(strpos($version, 'pro') !== false) return 'pro';

    return 'zentao';
}

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

    $ztfPath = RUNTIME_ROOT . 'ztf';
    $command = "$ztfPath $dir";
    system($command);
}

/**
 * Extract comments to steps and expects.
 *
 * @param  string    $dir
 * @access public
 * @return void
 */
function ztfExtract($dir)
{
    global $config;

    $ztfPath = RUNTIME_ROOT . 'ztf';
    $command = "$ztfPath extract $dir";
    system($command);
}

/**
 * Run yaml files of zendata.
 *
 * @access public
 * @return void
 */
function zdRun()
{
    $frameworkRoot = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;
    include $frameworkRoot . 'helper.class.php';
    include LIB_ROOT . 'spyc.php';

    $zdRoot  = dirname(dirname(__FILE__)) . '/data/';
    $zdPath  = RUNTIME_ROOT . 'zd';

    set_time_limit(0);
    try
    {
        $config = new stdclass();
        $config->db = new stdclass();

        include dirname(dirname(dirname(__FILE__))) . '/config/config.php';

        $versionType = getVersionType($config->version);
        $configRoot  = $zdRoot . $versionType . '/';
        include $configRoot . 'config.php';

        /* Connect to MySQL. */
        $dsn = "mysql:host={$config->db->host}:{$config->db->port};dbname={$config->db->name}";
        $dbh = new PDO($dsn, $config->db->user, $config->db->password);
        echo 'MySQL connect success' . PHP_EOL;
    
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 设置报错处理方式。
        $dbh->exec("SET GLOBAL sql_mode = '';");
        $dbh->exec("set names 'utf8'");
        $dbh->beginTransaction();

        /* Copy common to tmp. */
        $tmpCommonPath = RUNTIME_ROOT . 'tmp/common';
        if(file_exists($tmpCommonPath)) system("rm -rf $tmpCommonPath");
        system("cp -r {$zdRoot}common $tmpCommonPath");

        /* Generate SQL files. */
        $command = "$zdPath -c %s -d $zdRoot%s -n %s -o {$zdRoot}sql/%s.sql -table %s";
        $tables  = array();
        foreach($builder as $key => $info)
        {
            /* Build zendata. */
            $configData = array('fields' => array());
            foreach($info['data'] as $fileKey => $fileName)
            {
                if($fileKey == 0) continue; // Skip default file.

                $data = Spyc::YAMLLoad($configRoot . $fileName . '.yaml');
                foreach($data['fields'] as $field)
                {
                    $fieldName  = $field['field'];
                    $fieldExist = false;
                    foreach($configData['fields'] as $key => $configField)
                    {
                        if($configField['field'] == $fieldName)
                        {
                            $configData['fields'][$key] = $field;
                            $fieldExist = true;
                            break;
                        }
                    }

                    if(!$fieldExist) $configData['fields'][] = $field;
                }
            }

            /* Refresh runtime/tmp/config.yaml. */
            $spyc = new Spyc();
            $spyc->setting_dump_force_quotes = true;

            $content = $spyc->dump($configData);
            $yaml    = fopen(RUNTIME_ROOT . 'tmp/config.yaml', 'w');
            fwrite($yaml, $content);
            fclose($yaml);

            /* Create sql files. */
            $defaultName  = $info['data'][0];
            $tableName    = $config->db->prefix . $defaultName;

            $execYaml = sprintf($command, RUNTIME_ROOT . 'tmp/config.yaml', $defaultName . '.yaml', $info['rows'], $fileName, $tableName);
            system($execYaml);

            echo $execYaml . "\n";
            echo 'Execute: ' . $fileName . PHP_EOL;

            $tables[$tableName] = $tableName;
        }

        /* Truncate tables. */
        foreach($tables as $table)
        {
            $dbh->exec('truncate table ' . $table);
        }

        /* Export SQL files to MySQL. */
        $sqlDir      = $zdRoot . '/sql/';
        $sqlFilePath = scandir($sqlDir);

        foreach($sqlFilePath as $file)
        {
            if(!strpos($file, '.sql')) continue;

            $tableName = basename($file, '.sql');

            $sql   = file_get_contents($sqlDir . $file);
            $count = $dbh->exec($sql);
            echo $tableName . ' insert rows ' . $count . PHP_EOL;
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
