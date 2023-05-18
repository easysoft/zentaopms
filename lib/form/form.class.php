<?php
declare(strict_types=1);
/**
 * The form class file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     form
 * @link        https://www.zentao.net
 */

helper::import(dirname(dirname(__FILE__)) . '/filter/filter.class.php');

class form extends fixer
{
    /**
     * 原始 $_POST 数据。
     * The raw $_POST data.
     *
     * @var object
     */
    protected $rawdata;

    /**
     * 原始配置。
     * The raw cofig.
     *
     * @var array
     */
    protected $rawconfig;

    /**
     * 错误信息列表。
     * Error list.
     *
     * @var array
     */
    public $errors;

    /**
     * 构造方法。
     * The construct function.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rawdata = (object)$_POST;
        $this->data    = (object)array();
        $this->errors  = array();
    }

    /**
     * 获取表单数据。
     * Get the form data.
     *
     * @param array|null $configObject
     * @return form
     */
    public static function data(array $configObject = null): form
    {
        global $app, $config;
        if($configObject === null) $configObject = $config->{$app->moduleName}->form->{$app->methodName};
        return (new form)->config($configObject);
    }

    /**
     * 表单配置项。
     * Form configuration.
     *
     * @param array $config
     * @return $this
     * @throws EndResponseException
     */
    public function config(array $config)
    {
        $this->rawconfig = $config;

        foreach($this->rawconfig as $field => $fieldConfig) $this->convertField($field, $fieldConfig);
        if(!empty($this->errors))
        {
            $response = array('result' => 'fail', 'message' => $this->errors);
            helper::end(json_encode($response));
        }

        return $this;
    }

    /**
     * 获取$_POST的数据。
     * Get the data of $_POST.
     *
     * @param bool $isRaw 是否获取原始数据。Whether to get the raw data.
     * @return object
     */
    public function getAll(bool $isRaw = false): object
    {
        return $isRaw ? $this->rawdata : $this->data;
    }

    /**
     * 转换字段类型。
     * Convert the field type.
     *
     * @param string $field
     * @param array $config
     * @return void
     */
    public function convertField(string $field, array $config)
    {
        global $app;

        if(!isset($config['type']))
        {
            if(!isset($this->errors[$field])) $this->errors[$field] = array();
            $this->errors[$field][] = "Field '{$field}' need defined type";
        }

        if(isset($config['required']) && $config['required'])
        {
            if(!isset($config['default']) && (!isset($this->rawdata->$field) || $this->rawdata->$field === ''))
            {
                if(!isset($this->errors[$field])) $this->errors[$field] = array();
                $fieldName = isset($app->lang->{$app->rawModule}->$field) ? $app->lang->{$app->rawModule}->$field : $field;
                $this->errors[$field][] = sprintf($app->lang->error->notempty, $fieldName);
            }
        }

        if(isset($this->rawdata->$field))
        {
            $data = $this->rawdata->$field;
        }

        if(isset($config['default']) && !isset($this->rawdata->$field))
        {
            $data = $config['default'];
        }

        $data = helper::convertType($data, $config['type']);

        if(isset($config['filter']))
        {
            $data = $this->filter($data, $config['filter']);
        }

        $this->data->$field = $data;
    }

    /**
     * Special array.
     *
     * @param mixed $data
     * @access public
     * @return mixed
     */
    public function specialArray($data): mixed
    {
        if(!is_array($data))
        {
            if(is_string($data)) return htmlspecialchars($data, ENT_QUOTES);

            return $data;
        }

        foreach($data as &$value) $value = $this->specialArray($value);

        return $data;
    }

    /**
     * 过滤表单字段数据。
     * Filter the form field data.
     *
     * @param mixed $value
     * @param mixed $filter
     * @return string
     */
    protected function filter($value, $filter)
    {
        switch($filter)
        {
            case 'trim':
                return trim($value);
            case 'join':
                return join(',', $value);
            default:
                return $value;
        }
    }

    /**
     * 过滤富文字段数据。
     * Filter the editor fields.
     *
     * @param  string $fields
     * @access public
     * @return void
     */
    public function get(string $fields = ''): object
    {
        global $config;

        foreach($this->rawconfig as $field => $fieldConfig)
        {
            if(isset($fieldConfig['control']) && $fieldConfig['control'] == 'editor') $this->stripTags($field, $config->allowedTags);
        }

        return parent::get($fields);
    }
}
