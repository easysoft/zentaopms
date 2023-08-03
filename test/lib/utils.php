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
define('BASE_ROOT', dirname(dirname(dirname(__FILE__))));

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
function ztfRun($dir, $concurrency = false)
{
    global $config;

    $ztfPath = RUNTIME_ROOT . 'ztf';

    $runTestPath = '';
    if($dir == 'model' || $dir == 'pipeline')
    {
        $dir = implode(' ', getCaseModelDir());
    }
    elseif($dir == 'api')
    {
        $dir = TEST_BASEHPATH . '/api';
    }
    elseif(is_array($dir))
    {
        foreach($dir as $model) $runTestPath .= ' ' . BASE_ROOT . "/module/$model/test";
    }
    else
    {
        if($dir == 'mapi') $dir = 'api';
        $dir = BASE_ROOT . "/module/$dir/test";
    }

    if($runTestPath) $dir = $runTestPath;
    $command = "$ztfPath $dir";
    if($concurrency && !isset($config->dbPool) && is_numeric($concurrency)) $command .= "-C $concurrency";
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

    if($dir == 'model') $dir = implode(' ', getCaseModelDir());
    if($dir == 'api')   $dir = TEST_BASEHPATH . '/api';

    $command = "$ztfPath extract $dir";
    system($command);
}

/**
 * Get to the directory of test cases.
 *
 * @access public
 * @return void
 */
function getCaseModelDir()
{
    $moduleList = scandir(BASE_ROOT . '/module');
    foreach($moduleList as $index => $module)
    {
        if($module == '.' or $module == '..') unset($moduleList[$index]);
    }

    $dirs = array();
    foreach($moduleList as $index => $module)
    {
        $dirs[$index] = BASE_ROOT . "/module/$module/test";
    }

    return $dirs;
}

/**
 * Run yaml files of zendata.
 *
 * @access public
 * @return void
 */
function zdRun($isDev = false)
{
    global $config, $dao;

    include LIB_ROOT . 'spyc.php';

    $zdRoot = dirname(dirname(__FILE__)) . '/data/';
    if($isDev === true)
    {
        if(isset($config->db->devDbName)) $dao->exec("set global sql_mode = ''; set global max_allowed_packet = 1000000000; set global net_buffer_length = 10000000;  use {$config->db->devDbName};");
        $zdRoot = dirname(dirname(__FILE__)) . "/devdata/";
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

        $processor = new Processor();
        $processor->init();
    }
    catch (EndResponseException $e)
    {
        die('Error!: ' . $e->getContent() . PHP_EOL);
    }
}

/**
 * 根据数据库的建表语句初始化数据库。
 * According to the database to build predicate sentence to initialize the database.
 *
 * @param  string $dbName
 * @access public
 * @return void
 */
function initDB($dbName)
{
    global $config;
    $dbFile  = BASE_ROOT . DS . "db/zentao.sql";
    $version = $config->version;

    if(!$dbName) $dbName = $config->db->name;
    $dbHost = $config->db->host;
    $dbUser = $config->db->user;
    $dbPWD  = $config->db->password;
    $dbPort = $config->db->port;

    $tableContent = preg_replace('/__DELIMITER__+/', ";", file_get_contents($dbFile));
    $tableContent = preg_replace('/__TABLE__+/', $dbName, $tableContent);
    $tableContent = preg_replace('/^CREATE\s+FUNCTION+/m', "DELIMITER ;;\nCREATE FUNCTION", $tableContent);
    $tableContent = preg_replace('/END;+/', "END;; \nDELIMITER ;", $tableContent);

    if(!is_dir(BASE_ROOT . DS . 'tmp/testdb')) mkdir(BASE_ROOT . DS . 'tmp/testdb', 777);
    $dbFile = BASE_ROOT . DS . "tmp/testdb/{$dbName}.sql";
    file_put_contents($dbFile, $tableContent);

    `mysql -uroot -p{$dbPWD} -h{$dbHost} -P{$dbPort} -e "DROP DATABASE IF EXISTS $dbName; CREATE DATABASE IF NOT EXISTS $dbName COLLATE 'utf8_general_ci';"`;
    `mysql -uroot -p{$dbPWD} -h{$dbHost} -P{$dbPort} -D{$dbName} < $dbFile`;

    /* 插入一条公司数据和版本信息 */
    system("mysql -uroot -p{$dbPWD} -h{$dbHost} -D{$dbName} -P{$dbPort} -e \"INSERT INTO zt_company (name, admins) VALUES('unittest', ',admin,');\"");
    system("mysql -uroot -p{$dbPWD} -h{$dbHost} -D{$dbName} -P{$dbPort} -e \"INSERT INTO zt_config (\`owner\`, \`module\`, \`section\`, \`key\`, \`value\`) VALUES('system', 'common', 'global', 'version', '{$version}');\"");
    system("mysql -uroot -p{$dbPWD} -h{$dbHost} -D{$dbName} -P{$dbPort} -e \"INSERT INTO zt_user (\`company\`, \`account\`, \`password\`) VALUES('1', 'admin', 'a0933c1218a4e745bacdcf572b10eba7');\"");

    echo "初始化数据库{$dbName}成功！" . PHP_EOL;
}

/**
 * 批量初始化数据库。
 * Batch to initialize the database.
 *
 * @param  int    $dbCount
 * @access public
 * @return void
 */
function batchInitDB($dbCount)
{
    global $config;
    if(isset($config->dbPool)) return;

    $dbList = array("{$config->db->name}");
    for($i = 1; $i <= $dbCount; $i++)
    {
        $dbList[] = "{$config->db->name}_{$i}";
    }

    $dbConfig = '$config->dbPool = array();' . PHP_EOL . PHP_EOL;
    foreach($dbList as $key => $db)
    {
        initDB($db);
        $dbConfig .= "\$config->dbPool[{$key}] = array();" . PHP_EOL;
        $dbConfig .= "\$config->dbPool[{$key}]['name'] = '{$db}';" . PHP_EOL;
    }

    $configFile = TEST_BASEHPATH . DS . 'config' . DS . 'my.php';
    if(!file_exists($configFile) || !is_writable($configFile)) return;
    file_put_contents($configFile, $dbConfig, FILE_APPEND);
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
