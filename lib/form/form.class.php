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
     * 原始POST数据。
     * The raw post data.
     *
     * @var object
     * @access public
     */
    public $rawdata;

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
     * @param int        $objectID
     * @param int        $flowGroupID
     * @return form
     */
    public static function data(array $configObject = null, int $objectID = 0, int $flowGroupID = 0): form
    {
        global $app, $config;

        $form = new form;
        if($configObject === null) $configObject = $config->{$app->moduleName}->form->{$app->methodName};
        $configObject = $form->appendExtendFormConfig($configObject, '', '', $objectID, $flowGroupID);
        return $form->config($configObject);
    }

    /**
     * 获取批量表单数据。
     * Get the batch form data.
     *
     * @param  array|null $configObject
     * @return form
     */
    public static function batchData(?array $configObject = null): form
    {
        global $app, $config;

        $form = new form;
        if($configObject === null) $configObject = $config->{$app->moduleName}->form->{$app->methodName};
        $configObject = $form->appendExtendFormConfig($configObject);
        return $form->config($configObject, 'batch');
    }


    /**
     * 追加工作流新增的字段到表单提交配置。
     * append workflow form config.
     *
     * @param  array  $config
     * @param  string $moduleName
     * @param  string $methodName
     * @param  int    $objectID
     * @param  int    $flowGroupID
     * @access public
     * @return array
     */
    public function appendExtendFormConfig(array $configObject, string $moduleName = '', string $methodName = '', int $objectID = 0, int $flowGroupID = 0): array
    {
        global $app, $config;
        if($config->edition == 'open' ||  !empty($app->installing)) return $configObject;

        $moduleName = $moduleName ? $moduleName : $app->rawModule;
        $methodName = $methodName ? $moduleName : $app->rawMethod;

        /* 项目发布和项目版本用自己的工作流。 */
        if($moduleName == 'projectrelease') $moduleName = 'release';
        if($moduleName == 'projectbuild')   $moduleName = 'build';
        if($moduleName == 'projectplan')    $moduleName = 'productplan';

        /* 项目复制用项目创建的工作流。 */
        if($moduleName == 'project' && $methodName == 'copyconfirm')  $methodName = 'create';
        if($moduleName == 'project' && $methodName == 'edittemplate') $methodName = 'edit';

        /* 用户需求和业务需求用自己的工作流。*/
        if($moduleName == 'story' && $app->rawModule == 'requirement') $moduleName = 'requirement';
        if($moduleName == 'story' && $app->rawModule == 'epic')        $moduleName = 'epic';

        /* 复制项目使用项目创建的工作流。 */
        if($moduleName == 'project' && $methodName == 'copyconfirm') $methodName = 'create';

        if(empty($app->control)) return $configObject;

        $app->control->loadModel('workflowgroup');
        if($flowGroupID)
        {
            $group = $app->control->workflowgroup->fetchByID($flowGroupID);
            $groupID = (empty($group) || $group->main) ? 0 : $flowGroupID;
        }

        $groupID = isset($groupID) ? $groupID : $app->control->workflowgroup->getGroupIDByDataID($moduleName, $objectID);
        $flow = $app->control->loadModel('workflow')->getByModule($moduleName, false, $groupID);
        if(!$flow) return $configObject;

        $action  = $app->control->loadModel('workflowaction')->getByModuleAndAction($flow->module, $methodName, $flow->group);
        if(!$action || $action->extensionType != 'extend') return $configObject;

        $uiID         = $app->control->loadModel('workflowlayout')->getUIByDataID($flow->module, $action->action, $objectID);
        $fieldList    = $app->control->workflowaction->getPageFields($flow->module, $action->action, true, null, $uiID, $flow->group);
        $layouts      = $app->control->workflowlayout->getFields($moduleName, $methodName, $uiID, $flow->group);
        $notEmptyRule = $app->control->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
        if($layouts)
        {
            foreach($fieldList as $key => $field)
            {
                if($field->buildin || !$field->show || !isset($layouts[$field->field])) continue;
                if($field->control == 'file') continue;

                $required = !$field->readonly && $notEmptyRule && strpos(",$field->rules,", ",{$notEmptyRule->id},") !== false;
                if($field->control == 'multi-select' || $field->control == 'checkbox')
                {
                    $configObject[$field->field] = array('required' => $required, 'type' => 'array', 'default' => array(''), 'filter' => 'join');
                }
                elseif($field->control == 'date' || $field->control == 'datetime')
                {
                    $configObject[$field->field] = array('required' => $required, 'type' => $field->control, 'default' => null);
                }
                else
                {
                    $type = 'string';
                    if($field->type == 'int')     $type = 'int';
                    if($field->type == 'decimal') $type = 'float';
                    $configObject[$field->field] = array('required' => $required, 'type' => $type, 'default' => $field->type == 'int' || $field->type == 'decimal' ? 0 : '');
                    if($field->control == 'richtext') $configObject[$field->field]['control'] = 'editor';
                }
            }
        }
        return $configObject;
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
        $config = $this->processRequiredFields($config);

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
            $this->skipRequiredCheck($config);
        }

        if(!empty($this->errors))
        {
            $response = array('result' => 'fail', 'message' => $this->errors);
            helper::end(json_encode($response));
        }

        return $this;
    }

    /**
     * 根据必填字段更新表单配置项中的必填属性。
     * Update the required attribute of the form configuration according to the required fields.
     *
     * @param  array $configObject
     * @access private
     * @return array
     */
    private function processRequiredFields(array $configObject): array
    {
        global $app, $config, $lang;

        $module = $app->getModuleName();
        $method = $app->getMethodName();

        if(in_array($app->rawMethod, array('requirement', 'torequirement')) && $module == 'story') $module = 'requirement';
        if(in_array($app->rawMethod, array('epic', 'toepic')) && $module == 'story') $module = 'epic';

        if($app->rawModule == 'requirement' && $module == 'story') $module = 'requirement';
        if($app->rawModule == 'epic'        && $module == 'story') $module = 'epic';

        if($app->rawModule == 'feedback'    && $app->rawMethod == 'touserstory') $module = 'requirement';
        if($app->rawModule == 'feedback'    && $app->rawMethod == 'toepic')      $module = 'epic';

        if($app->rawModule == 'projectrelease') $module = 'release';

        if($method == 'batchcreate') $method = 'create';
        if($method == 'batchedit')   $method = 'edit';

        if($module == 'project' && $module == 'copyconfirm') $method = 'create';

        if(empty($config->$module->$method->requiredFields)) return $configObject;

        $requiredFields = array_unique(array_filter(explode(',', $config->$module->$method->requiredFields)));

        foreach($requiredFields as $field)
        {
            $field = trim($field);
            if(!isset($configObject[$field])) continue;

            if(!isset($configObject[$field]['skipRequired']))
            {
                $configObject[$field]['required'] = true;
                if(empty($lang->{$app->rawModule}->$field)) $lang->{$app->rawModule}->$field = $lang->$module->$field;
            }
        }

        return $configObject;
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

        $rowDataList = array();
        $baseField   = '';

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
                if(isset($config['filter'])) $rowData->$field = $this->filter($rowData->$field, $config['filter'], zget($config, 'separator', ','));

                /* 检查必填字段。Check required fields. */
                if(isset($config['required']) && $config['required'] && empty($rowData->$field))
                {
                    if($app->moduleName == 'task' && $app->methodName == 'batchcreate' && $field == 'estimate' && $this->rawdata->isParent[$rowIndex] == '1') continue;

                    $errorKey  = isset($config['type']) && $config['type'] == 'array' ? "{$field}[{$rowIndex}][]" : "{$field}[{$rowIndex}]";
                    $fieldName = isset($app->lang->{$app->rawModule}->$field) ? $app->lang->{$app->rawModule}->$field : $field;
                    if(!isset($this->errors[$errorKey])) $this->errors[$errorKey] = array();
                    $this->errors[$errorKey][] = sprintf($app->lang->error->notempty, $fieldName);
                }
            }

            $rowDataList[$rowIndex] = $rowData;
        }

        $this->dataList = $rowDataList;
    }

    /**
     * 跳过必填项检查。
     * Skip the required check.
     *
     * @param  array   $fieldConfigs
     * @access public
     * @return void
     */
    public function skipRequiredCheck(array $fieldConfigs)
    {
        foreach($this->dataList as $rowIndex => $rowData)
        {
            foreach($fieldConfigs as $field => $config)
            {
                if(empty($config['required']) || empty($config['skipRequired']) || !empty($rowData->$field)) continue;

                $skip     = true;
                $errorKey = isset($config['type']) && $config['type'] == 'array' ? "{$field}[{$rowIndex}][]" : "{$field}[{$rowIndex}]";
                foreach($config['skipRequired'] as $conditionField => $conditionValue)
                {
                    if($rowData->$conditionField != $conditionValue)
                    {
                        $skip = false;
                        break;
                    }
                }

                if($skip) unset($this->errors[$errorKey]);
            }
        }
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

        if(isset($config['filter'])) $data = $this->filter($data, $config['filter'], zget($config, 'separator', ','));

        if(isset($config['required']) && $config['required'] && isset($this->rawdata->$field) && (is_null($this->rawdata->$field) || $this->rawdata->$field === '' || is_array($this->rawdata->$field)) && empty($data))
        {
            $rawModule = $app->rawModule == 'feedback' && in_array($app->rawMethod, array('touserstory', 'toepic')) ? 'story' : $app->rawModule;
            $errorKey  = isset($config['type']) && $config['type'] == 'array' ? "{$field}[]" : $field;
            $fieldName = isset($app->lang->{$rawModule}->$field) ? $app->lang->{$rawModule}->$field : $field;
            if(!isset($this->errors[$errorKey])) $this->errors[$errorKey] = array();
            $this->errors[$errorKey][] = sprintf($app->lang->error->notempty, $fieldName);
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
     * @param  mixed     $value
     * @param  mixed     $filter
     * @param  string    $separator
     * @access protected
     * @return string
     */
    protected function filter($value, $filter, $separator = ',')
    {
        switch($filter)
        {
            case 'trim':
                return trim($value);
            case 'join':
                return implode($separator, $value);
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

        if($this->formType == 'single') return parent::get($fields);
        foreach($this->dataList as $rowIndex => $data)
        {
            $this->data = $data;
            $this->dataList[$rowIndex] = parent::get($fields);
            $this->stripedFields = array();
        }
        return $this->dataList;
    }
}
