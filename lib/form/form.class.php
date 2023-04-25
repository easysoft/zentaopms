<?php declare(strict_types=1);
/**
 * The form class file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.easycorp.cn)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     form
 * @link        https://www.zentao.net
 */

helper::import(dirname(dirname(__FILE__)) . '/base/filter/filter.class.php');

class form extends baseFixer
{
    public function __construct()
    {
        $this->rawdata = (object)$_POST;
        $this->data    = (object)array();
    }

    public static function use(array $configObject = null): form
    {
        global $app, $config;
        if($configObject === null) $configObject = $config->{$app->moduleName}->form->{$app->methodName};
        return (new form)->config($configObject);
    }

    public function config(array $config)
    {
        $this->rawconfig = $config;

        foreach($this->rawconfig as $key => $value) $this->convertField($key, $value);

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

    public function get($fields = '')
    {
        if(empty($fields)) return $this->data;
        if(strpos($fields, ',') === false) return $this->data->$fields;

        $fields = array_flip(explode(',', $fields));
        foreach($this->data as $field => $value)
        {
            if(!isset($fields[$field])) unset($this->data->$field);
        }

        return $this->data;
    }

    public function convertField($field, $config)
    {
        if(isset($config['required']) && $config['required'] && !isset($this->rawdata->$field))
        {
            throw new InvalidArgumentException("Field $field is required");
        }

        if(isset($config['required']) && !$config['required'] && !isset($config['default']))
        {
            throw new InvalidArgumentException("Field $field is not required, but need default value");
        }

        if(!isset($config['type']))
        {
            throw new InvalidArgumentException("Field $field need defined type");
        }

        if(isset($this->rawdata->$field))
        {
            $data = $this->rawdata->$field;
        }

        if(isset($config['default']) && !isset($this->rawdata->$field))
        {
            $data = $config['default'];
        }

        $data = $this->convertType($data, $config['type']);

        if(isset($config['filter']))
        {
            $data = $this->filter($data, $config['filter']);
        }

        $this->data->$field = $data;
    }

    public function a()
    {
        a($this->data);
    }

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

    protected function convertType($value, $type)
    {
        switch($type)
        {
            case 'int':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'bool':
                return (bool)$value;
            case 'array':
                return (array)$value;
            case 'object':
                return (object)$value;
            case 'string':
            default:
                return (string)$value;
        }
    }
}
