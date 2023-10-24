<?php
/**
 * Utils for ZenTaoPMS testing.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guanxing <guanxiying@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: $
 * @link        http://www.zentao.net
 */

define('RUNTIME_ROOT', dirname(dirname(__FILE__)) . '/runtime/');
define('LIB_ROOT', dirname(dirname(__FILE__)) . '/lib/');
define('TEST_BASEHPATH', dirname(dirname(__FILE__)));

include LIB_ROOT . 'init.php';

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
    $modelPath = TEST_BASEHPATH . '/model';

    $runTestPath = '';
    if(is_array($dir))
    {
        foreach($dir as $model) $runTestPath .= " $modelPath/$model";
    }

    if($runTestPath) $dir = $runTestPath;
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
    $testPath = TEST_BASEHPATH;
    $command = "$ztfPath extract $testPath/$dir";
    system($command);
}

/**
 * Run yaml files of zendata.
 *
 * @access public
 * @return void
 */
function zdRun($dataVersion = '', $dbFile = '')
{
    global $config, $dao, $db;

    $frameworkRoot = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;
    include LIB_ROOT . 'spyc.php';

    if($dataVersion && $dbFile)
    {
        $db->replaceDBConfig($dbFile);
        $zdRoot = dirname(dirname(__FILE__)) . "/data/{$dataVersion}/";
    }
    else
    {
        $zdRoot = dirname(dirname(__FILE__)) . '/data/';
    }
    $zdPath = RUNTIME_ROOT . 'zd';

    set_time_limit(0);
    try
    {
        $versionType = getVersionType($config->version);
        $configRoot  = $zdRoot . $versionType . '/';
        include $configRoot . 'config.php';
        include $configRoot . 'processor.php';

        /* Connect to MySQL. */
        $dao = new dao();
        echo 'MySQL connect success' . PHP_EOL;

        $dao->begin();

        /* Copy common to tmp. */
        $tmpCommonPath = RUNTIME_ROOT . 'tmp/common';
        if(file_exists($tmpCommonPath)) system("rm -rf $tmpCommonPath");
        system("cp -r {$zdRoot}common $tmpCommonPath");
        if(file_exists("{$zdRoot}sql/")) system("find {$zdRoot}sql/ -type f -delete");

        /* Generate SQL files. */
        $tables = array();
        $count  = 1;
        foreach($builder as $key => $info)
        {
            /* Build ZenData. */
            $tableName  = '';
            $files      = array();
            if(!empty($info['extends']))
            {
                foreach($info['extends'] as $fileKey => $fileName)
                {
                    if(!$tableName) $tableName = $fileName;
                    $files[] = $zdRoot . $fileName . '.yaml';
                }
            }
            if(!empty($info['data']))
            {
                if(!$tableName) $tableName = $info['data'];
                $files[] = $configRoot . $info['data'] . '.yaml';
            }
            $defaultFile = array_shift($files);

            /* Parse files, Generate $configYaml. */
            if(count($files) == 1) // If only one file need to parse, use is direactly.
            {
                $configYaml = $files[0];
            }
            else
            {
                $configData  = array('fields' => array());
                foreach($files as $file)
                {
                    $data = Spyc::YAMLLoad($file);
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

                $configYaml = RUNTIME_ROOT . 'tmp/config.yaml';
            }

            /* Create SQL files. */
            $tableName = $config->db->prefix . $tableName;
            $command  = "$zdPath -c %s -d %s -n %s -o {$zdRoot}sql/%03d_%s.sql -table %s";
            $execYaml = sprintf($command, $configYaml, $defaultFile, $info['rows'], $count, $fileName, $tableName);
            system($execYaml);
            // echo $execYaml . "\n";

            $tables[$tableName] = $tableName;
            $count++;
        }

        /* Truncate tables. */
        foreach($tables as $table)
        {
            $dao->exec('truncate table ' . $table);
        }

        /* Export SQL files to MySQL. */
        $sqlDir      = $zdRoot . '/sql/';
        $sqlFilePath = scandir($sqlDir);

        foreach($sqlFilePath as $file)
        {
            if(!strpos($file, '.sql')) continue;

            $tableName = basename($file, '.sql');

            $sql   = file_get_contents($sqlDir . $file);
            $count = $dao->exec($sql);
            echo $tableName . ' insert rows ' . $count . PHP_EOL;
        }

        $dao->commit();

        $processor = new Processor();
        $processor->init();
    }
    catch (PDOException $e)
    {
        die('Error!: ' . $e->getMessage() . PHP_EOL);
    }
}

/**
 * copy init DB.
 *
 * @access public
 * @return void
 */
function copyDB()
{
    global $config, $dao;
    $sqlFile = TEST_BASEHPATH . DS . 'tmp/raw.sql';
    if($config->db->host = 'localhost' and $config->db->port = '3306')
    {
        $dumpCommand = "mysqldump -u%s -p%s %s --add-drop-table=false > %s";
        $dumpCommand  = sprintf($dumpCommand, $config->db->user, $config->db->password, $config->test->rawDB, $sqlFile);
    }
    else
    {
        $dumpCommand = "mysqldump -h%s -P%s -u%s -p%s %s --add-drop-table=false > %s";
        $dumpCommand  = sprintf($dumpCommand, $config->db->host, $config->db->port, $config->db->user, $config->db->password, $config->test->rawDB, $sqlFile);
    }

    $currentDBNum = $dao->query("select count(*) as num from information_schema.SCHEMATA where SCHEMA_NAME like '" . $config->test->dbPrefix . "%'")->fetch();
    shell_exec($dumpCommand);

    $dbNum = $config->test->dbNum;

    $dbUsed = array();
    for($i = 1; $i <= $dbNum; $i++)
    {
        $dbUsed[] =  $config->test->dbPrefix . $i;
    }

    foreach($dbUsed as $db)
    {
        if($currentDBNum->num > 0) $dao->query('DROP DATABASE IF EXISTS ' . $db);
        $dao->query('CREATE DATABASE ' . $db);
        if($config->db->host = 'localhost' and $config->db->port = '3306')
        {
            shell_exec("mysql -u{$config->db->user} -p{$config->db->password} $db < $sqlFile");
        }
        else
        {
            shell_exec("mysql -h{$config->db->host} -P {$config->db->port} -u{$config->db->user} -p{$config->db->password} $db < $sqlFile");
        }
        echo '数据库<' . $db . '>复制成功！' . PHP_EOL;
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
        extract      extract comment of testcase
        api          run unit test for api
        control      run unit test for controllers
        model        run unit test for models
        ui           ui testing
        all          run all tests
        clean        remove data, files and cached files


EOT;
}
