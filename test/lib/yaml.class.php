<?php
/**
 * 本文件主要进行生成每个脚本文件对应的测试数据yaml文件。
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      liyang <liyang@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     1.0
 * @link        http://www.zentao.net/
 */

/**
 * Set fields for test data yaml file.
 *
 * @copyright Copyright 2009-2022 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @license   LGPL
 * @version   1.0
 * @Link      https://www.zentao.net
 */
class field
{
    /**
     * Global config.
     *
     * @var object
     * @access public
     */
    public $config;

    /**
     * Field Arr.
     *
     * @var array
     * @access public
     */
    public $fieldArr = array();

    /**
     * Field.
     *
     * @var int
     * @access private
     */
    private $field;

    /**
     * Yaml Dir root.
     *
     * @var int
     * @access public
     */
    public $yamlDir;

    /**
     * __construct function load config.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $config;
        $this->config  = $config;
        $this->yamlDir = dirname(__FILE__, 2) . '/model';
    }

    /**
     * Magic method, return fild.
     *
     * @param  string    $property_name
     * @access protected
     * @return object
     */
    public function __get($property_name)
    {
        $this->setField($property_name);
        return $this;
    }

    /**
     * Set yaml field.
     *
     * @param  string    $value
     * @access public
     * @return object
     */
    public function setField($value)
    {
        $this->fieldArr[$value] = array();
        $this->field = $value;
        return $this;
    }

    /**
     * Set field rang.
     *
     * @param  string    $range
     * @access public
     * @return object
     */
    public function range($range)
    {
        $this->fieldArr[$this->field]['range'] = $range;
        return $this;
    }

    /**
     * Set field prefix.
     *
     * @param  string    $prefix
     * @access public
     * @return object
     */
    public function prefix($prefix)
    {
        $this->fieldArr[$this->field]['prefix'] = $prefix;
        return $this;
    }

    /**
     * Set field postfix.
     *
     * @param  string    $postfix
     * @access public
     * @return object
     */
    public function postfix($postfix)
    {
        $this->fieldArr[$this->field]['postfix'] = $postfix;
        return $this;
    }

    /**
     * Set field type.
     *
     * @param  string    $type
     * @access public
     * @return object
     */
    public function type($type)
    {
        $this->fieldArr[$this->field]['type'] =  $type;
        return $this;
    }

    /**
     * Set field format.
     *
     * @param  string    $format
     * @access public
     * @return object
     */
    public function format($format)
    {
        $this->fieldArr[$this->field]['format'] =  $format;
        return $this;
    }

    /**
     * Set field fields.
     *
     * @param  array    $fields
     * @access public
     * @return object
     */
    public function fields($fields)
    {
        if(!is_array($fields))
        {
            echo "fields must be an array";
            return;
        }

        $this->fieldArr[$this->field]['fields'] =  $fields;
        return $this;
    }

    /**
     * Get field array.
     *
     * @access public
     * @return array
     */
    public function getFields()
    {
        var_dump($this->fieldArr);
        return $this->fieldArr;
    }

    /**
     * Assembly field generation rules.
     *
     * @param  array     $fieldArr
     * @access public
     * @return array
     */
    public function setFieldRule($fieldArr)
    {
        $ruleArr = array();
        $index   = 0;

        foreach($fieldArr as $field => $rule)
        {
            $ruleArr[$index]['field'] = $field;

            if(array_key_exists('fields', $rule))
            {
                $ruleArr[$index]['fields'] = $this->setFieldRule($rule['fields']);
            }
            else
            {
                if(!empty($rule['range'])) $ruleArr[$index]['range'] = $rule['range'];
            }

            if(!empty($rule['prefix']))  $ruleArr[$index]['prefix']  = $rule['prefix'];
            if(!empty($rule['postfix'])) $ruleArr[$index]['postfix'] = $rule['postfix'];
            if(!empty($rule['type']))    $ruleArr[$index]['type']    = $rule['type'];
            if(!empty($rule['format']))  $ruleArr[$index]['format']  = $rule['format'];
            $index++;
        }

        return $ruleArr;
    }

    /**
     * Build yaml file.
     *
     * @param  string    $model
     * @param  string    $name
     * @param  string    $version
     * @access public
     * @return void
     */
    public function build($model, $name, $version = '')
    {
        if(!is_dir($this->yamlDir . "/{$model}/data")) mkdir($this->yamlDir . "/{$model}/data", 0700);
        $yamlFile = $this->yamlDir . "/{$model}/data/{$name}.yaml";

        $yamlDataArr = array();

        $yamlDataArr['title']  = "zt_{$name}";
        $yamlDataArr['author'] = "auto_{$name}";
        $version ? $yamlDataArr['version'] = $version : $yamlDataArr['version'] = '1.0';

        if(empty($this->fieldArr)) return;
        $yamlDataArr['fields'] = $this->setFieldRule($this->fieldArr);

        yaml_emit_file($yamlFile, $yamlDataArr, YAML_UTF8_ENCODING);
    }
}

/**
 * Create test data from yaml file.
 *
 * @copyright Copyright 2009-2022 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      field
 * @license   LGPL
 * @version   1.0
 * @Link      https://www.zentao.net
 */
class yaml extends field
{
    /**
     * Insert the data into database.
     *
     * @param  string    $model
     * @param  string    $tableName
     * @param  int       $rows
     * @access public
     * @return string
     */
    function insertDB($model, $file, $tableName, $rows, $isClear = false)
    {
        $yamlFile    = $this->yamlDir . "/{$model}/data/{$file}.yaml";
        $tableSqlDir = $this->yamlDir . "/{$model}/data/sql";

        if(!is_dir($tableSqlDir)) mkdir($tableSqlDir, 0700);
        $dumpCommand = "mysqldump -u%s -p%s -h%s -P%s %s %s > {$tableSqlDir}/{$tableName}.sql";

        $runtimeRoot = dirname(dirname(__FILE__)) . '/runtime/';
        $zdPath      = $runtimeRoot . 'zd';
        $configYaml  = $runtimeRoot . 'tmp/config.yaml';

        $tableName = $this->config->db->prefix . $tableName;
        $dbName    = $this->config->db->name;
        $dbHost    = $this->config->db->host;
        $dbPort    = $this->config->db->port;
        $dbUser    = $this->config->db->user;
        $dbPWD     = $this->config->db->password;

        $command = "$zdPath -c %s -d %s -n %d -t %s -dns mysql://%s:%s@%s:%s/%s#utf8";
        if($isClear === true) $command .= ' --clear';
        $execYaml = sprintf($command, $configYaml, $yamlFile, $rows, $tableName, $dbUser, $dbPWD, $dbHost, $dbPort, $dbName);
        $execDump = sprintf($dumpCommand, $dbUser, $dbPWD, $dbHost, $dbPort, $dbName, $tableName);
        system($execDump);
        system($execYaml);
    }

    /**
     * Restore table data.
     *
     * @param  string    $model
     * @param  string    $tableName
     * @access public
     * @return mixed
     */
    public function restoreTable($model, $tableName)
    {
        $tableSql = $this->yamlDir . "/{$model}/data/sql/$tableName.sql";
        if(!is_file($tableSql)) return false;

        $dbName = $this->config->db->name;
        $dbHost = $this->config->db->host;
        $dbPort = $this->config->db->port;
        $dbUser = $this->config->db->user;
        $dbPWD  = $this->config->db->password;

        $command     = "mysql -u%s -p%s -h%s -P%s %s < %s";
        $execRestore = sprintf($command, $dbUser, $dbPWD, $dbHost, $dbPort, $dbName, $tableSql);
        system($execRestore);
    }
}
