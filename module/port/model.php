<?php
class portModel extends model
{

    /*  Port Module configs . */
    public $portConfig;

    public $portLang;

    /* From model configs .*/
    public $modelConfig;

    public $modelLang;

    /**
     * Commom Actions
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function commonActions($model = '')
    {
        $this->portConfig  = $this->config->port;
        $this->portLang    = $this->lang->port;

        if($model)
        {
            $this->loadModel($model);
            $this->modelConfig = $this->config->$model;
            $this->modelLang   = $this->lang->$model;
        }
    }

    /**
     * Init FieldList .
     *
     * @param  int    $model
     * @param  string $fields
     * @access public
     * @return void
     */
    public function initFieldList($model, $fields = '')
    {
        $this->commonActions($model);
        $this->mergeConfig($model);

        $portFieldList = $this->portConfig->fieldList;

        if(empty($fields)) return false;

        $fieldList = array();
        /* build module fieldList. */
        foreach ($fields as $key => $field)
        {
            $field = trim($field);
            $modelFieldList = isset($this->modelConfig->fieldList[$field]) ? $this->modelConfig->fieldList[$field] : array();

            foreach ($portFieldList as $portField => $value)
            {
                $funcName = 'init' . ucfirst($portField);
                if((!array_key_exists($portField, $modelFieldList)) or $portField == 'title')
                {
                  $modelFieldList[$portField] = $this->portConfig->fieldList[$portField];
                  if(strpos($this->portConfig->initFunction, $portField) !== false) $modelFieldList[$portField] = $this->$funcName($model, $field);
                }
            }

            $modelFieldList = $this->initForeignKey($model, $field, $modelFieldList);
            $modelFieldList['values'] = $this->initValues($model, $field, $modelFieldList);
            $fieldList[$field] = $modelFieldList;
        }

        return $fieldList;
    }

    /**
     * Init Title.
     *
     * @param  int    $model
     * @param  int    $field
     * @access public
     * @return void
     */
    public function initTitle($model, $field)
    {
        $title = $field;

        if(!empty($this->modelConfig->fieldList[$field]['title'])) $title = $this->modelLang->{$this->modelConfig->fieldList[$field]['title']};
        if(array_key_exists($field, $this->lang->$model))
        {
            $title = $this->lang->$model->$field;
        }
        elseif(array_key_exists(($field . 'AB'), $this->lang->$model))
        {
            $title = $this->lang->$model->{$field . 'AB'};
        }
        elseif(array_key_exists($field, $this->lang->port->reservedWord))
        {
            $title = $this->lang->port->reservedWord[$field];
        }

        return $title;
    }

    /**
     * Init Control .
     *
     * @param  int    $model
     * @param  int    $field
     * @access public
     * @return void
     */
    public function initControl($model, $field)
    {
        if(isset($this->modelLang->{$field.'List'}))        return 'select';
        if(strpos($this->portConfig->sysDataFields, $field) !== false) return 'select';
        return $this->portConfig->fieldList['control'];
    }

    /**
     * Init Values .
     *
     * @param  int    $model
     * @param  int    $field
     * @access public
     * @return void
     */
    public function initValues($model, $field, $fieldValue = '')
    {
        $values = $fieldValue['values'];

        if($values and (strpos($this->portConfig->sysDataFields, $values) !== false))
        {
            return $this->portConfig->sysDataList[$values];
        }

        if(!$fieldValue['foreignKey']) return $values;

        extract($fieldValue['foreignKeySource']); // $module, $method, $params, $pairs, $sql, $lang

        if(!empty($module) and !empty($method))
        {
            $params = !empty($params) ? $params : '';
            $pairs  = !empty($pairs)  ? $pairs : '';
            $values = $this->getSourceByModuleMethod($model, $module, $method, $params, $pairs);
        }
        elseif(!empty($sql))
        {
            $values = $this->getSourceBySql();
        }
        elseif(!empty($lang))
        {
            $values = $this->getSourceByLang($lang);
        }

        /* If empty values put system datas .*/
        if(empty($values))
        {
            if(strpos($this->modelConfig->sysLangFields, $field)) return $this->modelLang->{$field.'List'};
            if(strpos($this->modelConfig->sysDataFields, $field) and !empty($this->portConfig->sysDataList[$values])) return $this->portConfig->sysDataList[$values];
        }

        return $values;
    }

    /**
     * Init ForeignKey .
     *
     * @param  int    $model
     * @param  int    $field
     * @param  int    $fieldContent
     * @access public
     * @return void
     */
    public function initForeignKey($model, $field, $fieldContent)
    {
        $modelConfig = $this->modelConfig;
        if((strpos($modelConfig->sysLangFields, $field) or strpos($modelConfig->sysDataFields, $field)) and empty($fieldContent['foreignKey']))
        {
            $fieldContent['foreignKey'] = true;
        }

        return $fieldContent;
    }

    /**
     * Init Required.
     *
     * @param  int    $model
     * @param  int    $field
     * @access public
     * @return void
     */
    public function initRequired($model, $field)
    {
        $this->commonActions($model);

        if(empty($this->modelConfig->create->requiredFields)) return 'no';

        $requiredFields = "," . $this->modelConfig->create->requiredFields . ",";
        if(strpos($requiredFields, $field) !== false) return 'yes';
        return 'no';
    }

    /**
     * init system datafields list.
     *
     * @access public
     * @return void
     */
    public function initSysDataFields()
    {
        $this->commonActions();
        $dataList = array();

        $sysDataFields = explode(',', $this->portConfig->sysDataFields);
        foreach($sysDataFields as $field)
        {
            $dataList[$field] =  $this->loadModel($field)->getPairs();
        }

        return $dataList;
    }

    /**
     * Merge configs .
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function mergeConfig($model)
    {
        $this->commonActions($model);
        $portConfig  = $this->portConfig;
        $modelConfig = $this->modelConfig;
        if(!isset($modelConfig->export)) $modelConfig->export = new stdClass();
        if(!isset($modelConfig->import)) $modelConfig->export = new stdClass();

        $modelConfig->dateFeilds     = isset($modelConfig->dateFeilds)     ? $modelConfig->dateFeilds     : $portConfig->dateFeilds;
        $modelConfig->datetimeFeilds = isset($modelConfig->datetimeFeilds) ? $modelConfig->datetimeFeilds : $portConfig->datetimeFeilds;
        $modelConfig->sysLangFields  = isset($modelConfig->sysLangFields)  ? $modelConfig->sysLangFields  : $portConfig->sysLangFields;
        $modelConfig->sysDataFields  = isset($modelConfig->sysDataFields)  ? $modelConfig->sysDataFields  : $portConfig->sysDataFields;
        $modelConfig->listFields     = isset($modelConfig->listFields)     ? $modelConfig->listFields     : $portConfig->listFields;
        $modelConfig->import->ignoreFields   = isset($modelConfig->import->ignoreFields)   ? $modelConfig->import->ignoreFields   : $portConfig->import->ignoreFields;
    }

    /**
     * Get field values by method .
     *
     * @param  int    $model
     * @param  int    $module
     * @param  int    $method
     * @param  string $params
     * @param  string $pairs
     * @access public
     * @return void
     */
    public function getSourceByModuleMethod($model, $module, $method, $params = '', $pairs = '')
    {
        $getParams = $this->session->{$model.'PortParams'};

        if($params)
        {
            $params = explode('&', $params);
            foreach($params as $param => $value)
            {
                if(strpos($value, '$') !== false) $params[$param] = $getParams[ltrim($value, '$')];
            }
        }

        /* If this method has multiple parameters use call_user_func_array */
        if(is_array($params))
        {
            $values = call_user_func_array(array($this->loadModel($module), $method), $params);
        }
        else
        {
            $values = $this->loadModel($module)->$method($params);
        }

        if(!empty($pairs))
        {
            $valuePairs = array();
            foreach ($values as $key => $value)
            {
                if(is_object($value)) $value = get_object_vars($value);

                $valuePairs[$key] = $value[$pairs[1]];
                if(!empty($pairs[0])) $valuePairs[$value[$pairs[0]]] = $value[$pairs[1]];
            }
            $values = $valuePairs;
        }

        return $values;
    }

    /**
     * Get field values by sql .
     *
     * @access public
     * @return void
     */
    public function getSourceBySql($sql = '')
    {

        return '';
    }

    /**
     * Get field values by lang .
     *
     * @access public
     * @return void
     */
    public function getSourceByLang($lang)
    {
        $lang = isset($this->modelLang->$lang) ? $this->modelLang->$lang : '';
        return $lang;
    }

    /**
     * Get files by model;
     *
     * @param  int    $model
     * @param  int    $datas
     * @access public
     * @return void
     */
    public function getFiles($model, $datas)
    {
        $this->loadModel('file');
        $relatedFiles = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)
            ->where('objectType')->eq($model)
            ->andWhere('objectID')->in(@array_keys($datas))
            ->andWhere('extra')
            ->ne('editor')
            ->fetchGroup('objectID');

        if(empty($datas) and empty($relatedFiles)) return $datas;

        /* Set related files. */
        foreach($datas as $data)
        {
            $data->files = '';
            if(isset($relatedFiles[$data->id]))
            {
                foreach($relatedFiles[$data->id] as $file)
                {
                    $fileURL = common::getSysURL() . helper::createLink('file', 'download', "fileID={$file->id}");
                    $data->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                }
            }
        }
        return $datas;
    }

}

