<?php
class portModel extends model
{
    /**
     * Init FieldList .
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function initFieldList($model)
    {
        $this->mergeConfig($model);
        $portFieldList = $this->config->port->fieldList;

        $this->loadModel($model);
        $fields = isset($this->config->$model->exportFields) ? $this->config->$model->exportFields : '';

        if(empty($fields)) return false;

        $fields = explode(',', $fields);

        /* build module fieldList. */
        foreach ($fields as $field)
        {
            $field = trim($field);

            if(!isset($this->config->$model->fieldList[$field])) $this->config->$model->fieldList[$field] = array();
            $modelFieldList = $this->config->$model->fieldList[$field];

            foreach ($portFieldList as $portField => $value)
            {
                $funcName = 'init' . ucfirst($portField);
                if(!array_key_exists($portField, $modelFieldList))
                {
                    $modelFieldList[$portField] = $this->config->port->fieldList[$portField];
                    if(strpos($this->config->port->initFunction, $portField) !== false) $modelFieldList[$portField] = $this->$funcName($model, $field);
                }
            }

            $modelFieldList = $this->initForeignKey($model, $field, $modelFieldList);
            $modelFieldList['values'] = $this->initValues($model, $field, $modelFieldList);

            $this->config->$model->fieldList[$field] = $modelFieldList;
        }

        return $this->config->$model->fieldList;
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
        $this->app->loadLang($model);

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
        if(isset($this->lang->$model->{$field.'List'}))        return 'select';
        if(strpos($this->config->port->sysDataFields, $field) !== false) return 'select';
        return $this->config->port->fieldList['control'];
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
        $values = '';
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
            $values = $this->getSourceByLang();
        }

        if(empty($values))
        {
            if(strpos($this->config->$model->sysLangFields, $field)) return $this->lang->$model->{$field.'List'};
            if(strpos($this->config->$model->sysDataFields, $field))
            {

            }
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
        $modelConfig = $this->config->$model;
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
        if(empty($this->config->$model->create->requiredFields)) return 'no';

        $requiredFields = "," . $this->config->$model->create->requiredFields . ",";

        if(strpos($requiredFields, $field) !== false) return 'yes';
        return 'no';
    }

    /**
     * Merge config .
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function mergeConfig($model)
    {
        $portConfig  = $this->config->port;
        $modelConfig = $this->config->$model;
        if(!isset($modelConfig->export)) $modelConfig->export = new stdClass();
        if(!isset($modelConfig->import)) $modelConfig->export = new stdClass();

        $modelConfig->dateFeilds     = isset($modelConfig->dateFeilds)     ? $modelConfig->dateFeilds     : $portConfig->dateFeilds;
        $modelConfig->datetimeFeilds = isset($modelConfig->datetimeFeilds) ? $modelConfig->datetimeFeilds : $portConfig->datetimeFeilds;
        $modelConfig->sysLangFields  = isset($modelConfig->sysLangFields)  ? $modelConfig->sysLangFields  : $portConfig->sysLangFields;
        $modelConfig->sysDataFields  = isset($modelConfig->sysDataFields)  ? $modelConfig->sysDataFields  : $portConfig->sysDataFields;
        $modelConfig->listFields     = isset($modelConfig->listFields)     ? $modelConfig->listFields     : $portConfig->listFields;
        $modelConfig->import->ignoreFields   = isset($modelConfig->import->ignoreFields)   ? $modelConfig->import->ignoreFields   : $portConfig->import->ignoreFields;
    }

    public function getSourceByModuleMethod($model, $module, $method, $params = '', $pairs = '')
    {
        $getParams = $this->session->{$model.'ExportParams'};

        $params = empty($params) ? '' : $params;
        if(!empty($params))
        {
            $sourceParams = explode(',', $params);
            foreach($sourceParams as $key => $param)
            {
                if(strpos($param, '$') !== false) $sourceParams[$key] = $getParams[ltrim($param, '$')];
            }
            $params = join(',', $sourceParams);
        }

        $values = $this->loadModel($module)->$method($params);
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

    public function getSourceBySql()
    {
        return '';
    }

    public function getSourceByLang()
    {
        return '';
    }

}

