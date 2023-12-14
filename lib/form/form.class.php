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
     * 批量处理的数据。
     * The data to be fixed.
     *
     * @var object
     * @access public
     */
    public $dataList;

    /**
     * 类型。single|batch
     * Type. single|batch
     *
     * @var string
     */
    protected $formType = 'single';

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
     * 获取批量表单数据。
     * Get the batch form data.
     *
     * @param  array|null $configObject
     * @return form
     */
    public static function batchData(array $configObject = null): form
    {
        global $app, $config;

        if($configObject === null) $configObject = $config->{$app->moduleName}->form->{$app->methodName};
        return (new form)->config($configObject, 'batch');
    }

    /**
     * 设置表单配置项。
     * Set form configuration.
     *
     * @param  array  $config
     * @param  string $type   single|batch
     * @return $this
     * @throws EndResponseException
     */
    public function config(array $config, string $type = 'single')
    {
        $this->rawconfig = $config;
        $this->formType  = $type;

        if($type == 'single')
        {
            foreach($this->rawconfig as $field => $fieldConfig)
            {
                if(isset($fieldConfig['control']) && in_array($fieldConfig['control'], array('textarea', 'richtext'))) $this->skipSpecial($field);
                $this->convertField($field, $fieldConfig);
            }
        }
        else
        {
            $this->batchConvertField($config);
        }

        if(!empty($this->errors))
        {
            $response = array('result' => 'fail', 'message' => $this->errors);
            helper::end(json_encode($response));
        }

        return $this;
    }

    /**
     * 批量转换字段类型。
     * Batch convert the field type.
     *
     * @param  array $config
     * @return void
     */
    public function batchConvertField(array $fieldConfigs)
    {
        global $app;

        $rowDataList   = array();
        $baseField = '';

        foreach($fieldConfigs as $field => $config)
        {
            /* 在第一行提示类型未定义。 Display type error in the first row. */
            if(!isset($config['type']))
            {
                if(empty($this->errors)) $this->errors[1] = array();
                if(!isset($this->errors[1][$field])) $this->errors[1][$field] = array();

                $this->errors[1][$field][] = "Field '{$field}' need defined type";
            }

            /* 以该字段为标准，判断某一行是否要构造数据。 If the value of the field in a row is empty, skip that row. */
            if(!empty($config['base'])) $baseField = $field;

            if(isset($config['control']) && in_array($config['control'], array('textarea', 'richtext'))) $this->skipSpecial($field);
        }

        /* 在第一行提示标准字段不能为空。 Display the field error in the first row. */
        if(!isset($this->rawdata->$baseField))
        {
            if(empty($this->errors)) $this->errors[1] = array();
            if(!isset($this->errors[1][$field])) $this->errors[1][$field] = array();
            $this->errors[1][$field][] = "Field '{$field}' not empty";
        }

        /* 构造批量表单数据。Construct batch form data. */
        foreach($this->rawdata->$baseField as $rowIndex => $value)
        {
            if(empty($value)) continue;

            $rowData = new stdclass();
            foreach($fieldConfigs as $field => $config)
            {
                $defaultValue = zget($config, 'default', '');

                $rowData->$field = isset($this->rawdata->$field) ? zget($this->rawdata->$field, $rowIndex, $defaultValue) : $defaultValue;
                $rowData->$field = helper::convertType($rowData->$field, $config['type']);
                if(isset($config['filter'])) $rowData->$field = $this->filter($rowData->$field, $config['filter']);

                /* 检查必填字段。Check required fields. */
                if(isset($config['required']) && $config['required'] && empty($rowData->$field))
                {
                    $fieldName = isset($app->lang->{$app->rawModule}->$field) ? $app->lang->{$app->rawModule}->$field : $field;
                    if(!isset($this->errors["{$field}[{$rowIndex}]"])) $this->errors["{$field}[{$rowIndex}]"] = array();
                    $this->errors["{$field}[{$rowIndex}]"] = sprintf($app->lang->error->notempty, $fieldName);
                }
            }

            $rowDataList[$rowIndex] = $rowData;
        }

        $this->dataList = $rowDataList;
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

        if(isset($this->rawdata->$field)) $data = $this->rawdata->$field;

        /* Assign the default value to the data if the default value exists and the data is not exist or null or empty string. */
        if(isset($config['default']) && (!isset($this->rawdata->$field) || is_null($this->rawdata->$field) || $this->rawdata->$field === '')) $data = $config['default'];

        if(isset($data)) $data = helper::convertType($data, $config['type']);

        if(isset($config['filter'])) $data = $this->filter($data, $config['filter']);

        if(isset($config['required']) && $config['required'] && empty($data))
        {
            $fieldName = isset($app->lang->{$app->rawModule}->$field) ? $app->lang->{$app->rawModule}->$field : $field;
            if(!isset($this->errors[$field])) $this->errors[$field] = array();
            $this->errors[$field][] = sprintf($app->lang->error->notempty, $fieldName);
        }

        $this->data->$field = isset($data) ? $data : null;
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
                return implode(',', $value);
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
     * @return mixed
     */
    public function get(string $fields = ''): mixed
    {
        global $config;

        foreach($this->rawconfig as $field => $fieldConfig)
        {
            if(isset($fieldConfig['control']) && $fieldConfig['control'] == 'editor') $this->stripTags($field, $config->allowedTags);
        }

        if($this->formType == 'single') return parent::get($fields);
        foreach($this->dataList as $rowIndex => $data)
        {
            $this->data = $data;
            $this->dataList[$rowIndex] = parent::get($fields);
        }
        return $this->dataList;
    }
}
