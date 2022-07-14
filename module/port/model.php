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
     * export
     *
     * @param  string $model
     * @param  string $params
     * @access public
     * @return void
     */
    public function export($model = '', $params = '')
    {
        /* Split parameters into variables (executionID=1,status=open).*/
        $params = explode(',', $params);
        foreach($params as $key => $param)
        {
            $param = explode('=', $param);
            $params[$param[0]] = $param[1];
            unset($params[$key]);
        }
        extract($params);

        /* save params to session. */
        $this->session->set(($model.'PortParams'), $params);



        $fields = $this->post->exportFields;

        /* Init config fieldList */
        $fieldList = $this->initFieldList($model, $fields);

        $rows = $this->getRows($model, $fieldList);

        $list = $this->setListValue($model, $fieldList);
        if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

        /* Get export rows and fields datas */
        $exportDatas = $this->getExportDatas($fieldList, $rows);

        $this->post->set('rows',   $exportDatas['rows']);
        $this->post->set('fields', $exportDatas['fields']);
        $this->post->set('kind',   $model);
    }

    /**
     * createFromImport
     *
     * @param  int    $model
     * @param  string $params
     * @access public
     * @return void
     */
    public function createFromImport($model, $params = '')
    {
        $this->loadModel('action');
        $this->loadModel('file');
        $now   = helper::now();
        $datas = fixer::input('post')->get();
        $table = zget($this->config->objectTables, $model);

        if(!empty($_POST['id']))
        {
            $oldObjects = $this->dao->select('*')->from($table)->where('id')->in(($_POST['id']))->fetchAll('id');
        }

        $objects = array();
        $objectData = array();
        foreach ($datas as $field => $data)
        {
            if(is_array($data))
            {
                foreach($data as $key => $value) $objectData[$key][$field] = $value;
            }
        }

        a($objectData);

        die;



    }

    /**
     * Init FieldList .
     *
     * @param  int    $model
     * @param  string $fields
     * @access public
     * @return void
     */
    public function initFieldList($model, $fields = '', $withKey = true)
    {
        $this->commonActions($model);
        $this->mergeConfig($model);

        $this->config->port->sysDataList = $this->initSysDataFields();
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

            $modelFieldList['values'] = $this->initValues($model, $field, $modelFieldList, $withKey);
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
    public function initValues($model, $field, $fieldValue = '', $withKey = true)
    {
        $values = $fieldValue['values'];

        if($values and (strpos($this->portConfig->sysDataFields, $values) !== false))
        {
            return $this->portConfig->sysDataList[$values];
        }

        if(!$fieldValue['dataSource']) return $values;

        extract($fieldValue['dataSource']); // $module, $method, $params, $pairs, $sql, $lang

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

        if(is_array($values) and $withKey)
        {
            unset($values['']);
            foreach ($values as $key => $value)
            {
                $values[$key] = $value . "(#$key)";
            }
        }

        return $values;
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
     * Get ExportDatas.
     *
     * @param  int    $fieldList
     * @param  array  $rows
     * @access public
     * @return void
     */
    public function getExportDatas($fieldList, $rows = array())
    {
        $exportDatas = array();
        $dataSourceList = array();

        foreach ($fieldList as $key => $field)
        {
            $exportDatas['fields'][$key] = $field['title'];
            if($field['values'])
            {
                $exportDatas[$key]   = $field['values'];
                $dataSourceList[] = $key;
            }
        }

        if(empty($rows)) return $exportDatas;

        $exportDatas['user'] = $this->loadModel('user')->getPairs('devfirst|noclosed|nodeleted');

        foreach ($rows as $id => $values)
        {
            foreach($values as $field => $value)
            {
                if(in_array($field, $dataSourceList))
                {
                    $rows[$id]->$field = zget($exportDatas[$field], $value);
                }
                elseif(strpos($this->config->port->userFields, $field) !== false)
                {
                    $rows[$id]->$field = zget($exportDatas['user'], $value);
                }

                /* if value = 0 or value = 0000:00:00 set value = ''*/
                if(!$value or helper::isZeroDate($value))
                {
                    $rows[$id]->$field = '';
                }
            }
        }

        $exportDatas['rows'] = array_values($rows);
        return $exportDatas;
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

    /**
     * setListValue
     *
     * @param  int    $model
     * @param  int    $fieldList
     * @access public
     * @return void/array
     */
    public function setListValue($model, $fieldList)
    {
        $lists = array();

        if(!empty($this->config->$model->listFields))
        {
            $listFields = $this->config->$model->listFields;
            foreach($listFields as $field)
            {
                $listName = $field . 'List';
                if(!empty($fieldList[$field]))
                {
                    $lists[$listName] = $fieldList[$field]['values'];
                    if(strpos($this->config->$model->sysLangFields, $field)) $lists[$listName] = join(',', $fieldList[$field]['values']);
                }
            }

            $lists['listStyle'] = $listFields;
        }

        return $lists;
    }

    /**
     * Get Rows .
     *
     * @param  int    $model
     * @param  int    $fieldList
     * @access public
     * @return void
     */
    public function getRows($model, $fieldList, $orderBy = 'id_desc')
    {
        $queryCondition = $this->session->{$model . 'QueryCondition'};
        $onlyCondition  = $this->session->{$model . 'OnlyCondition'};

        $modelDatas = array();
        if($onlyCondition and $queryCondition)
        {
            $table = zget($this->config->objectTables, $model);
            if(isset($this->config->$model->port->table)) $table = $this->config->$model->port->table;
            $modelDatas = $this->dao->select('*')->from($table)->alias('t1')
                ->where($queryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->cookie->checkedItem)->fi()
                ->fetchAll('id');
        }
        elseif($queryCondition)
        {
            $stmt = $this->dbh->query($queryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
            while($row = $stmt->fetch()) $modelDatas[$row->id] = $row;
        }

        if(array_key_exists('files', $fieldList))
        {
            $modelDatas = $this->getFiles($model, $modelDatas);
        }

        $rows = !empty($_POST['rows']) ? $_POST['rows'] : '';
        if($rows)
        {
            foreach($rows as $id => $row)
            {
                $modelDatas[$id] = (object) array_merge((array)$modelDatas[$id], (array)$row);
            }
        }

        /* Deal children datas and multiple tasks .*/
        if($modelDatas)
        {
           $modelDatas = $this->updateChildDatas($modelDatas);
        }

        return $modelDatas;
    }

    /**
     * updateChildrenDatas
     *
     * @param  int    $datas
     * @access public
     * @return void
     */
    public function updateChildDatas($datas)
    {
        $children = array();
        foreach($datas as $data)
        {
            if(!empty($data->parent) and isset($datas[$data->parent]))
            {
                if(!empty($data->name)) $data->name = '>' . $data->name;
                elseif(!empty($data->title)) $data->title = '>' . $data->title;
                $children[$data->parent][$data->id] = $data;
                unset($datas[$data->id]);
            }
            if(!empty($data->mode))
            {
                $datas[$data->id]->name = '[' . $this->lang->task->multipleAB . '] ' . $data->name;
            }
        }

        /* Move child data after parent data .*/
        if(!empty($children))
        {
            $position = 0;
            foreach($datas as $data)
            {
                $position ++;
                if(isset($children[$data->id]))
                {
                    array_splice($datas, $position, 0, $children[$data->id]);
                    $position += count($children[$data->id]);
                }
            }
        }

        return $datas;
    }

    /**
     * initTmpFile
     *
     * @access public
     * @return void
     */
    public function initTmpFile()
    {
        $taskLang = $this->lang->task;
        $file    = $this->session->fileImportFileName;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

        if(file_exists($tmpFile)) return $tmpFile;

        $rows = $this->file->getRowsFromExcel($file);

        /* Check empty.*/
        if(is_string($rows))
        {
            unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            $response['result']  = 'fail';
            $response['message'] = $rows;
            return $response;
        }

        $fields = $this->config->task->templateFields;
        array_unshift($fields, 'id');

        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($taskLang->$fieldName) ? $taskLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        $objectDatas = array();

        foreach($rows as $currentRow => $row)
        {
            $tmpArray = new stdClass();
            foreach($row as $currentColumn => $cellValue)
            {
                if($currentRow == 1)
                {
                    $field = array_search($cellValue, $fields);
                    $columnKey[$currentColumn] = $field ? $field : '';
                    continue;
                }

                if(empty($columnKey[$currentColumn]))
                {
                    $currentColumn++;
                    continue;
                }

                $field = $columnKey[$currentColumn];
                $currentColumn++;

                /* Check empty data. */
                if(empty($cellValue))
                {
                    $tmpArray->$field = '';
                    continue;
                }

                $tmpArray->$field = $cellValue;
            }
            if(empty($tmpArray->name)) continue;
            $objectDatas[$currentRow] = $tmpArray;
            unset($tmpArray);
        }

        $objectDatas = $this->getNatureDatas($objectDatas);
        file_put_contents($tmpFile, serialize($objectDatas));

        if(empty($objectDatas))
        {
            unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            $response['result']  = 'fail';
            $response['message'] = $this->lang->excel->noData;
            return $response;
        }

        return $tmpFile;
    }

    /**
     * getNatureDatas
     *
     * @param  int    $datas
     * @access public
     * @return void
     */
    public function getNatureDatas($datas)
    {
        $taskLang = $this->lang->task;
        foreach($datas as $key => $data)
        {
            foreach($data as $field => $cellValue)
            {
                if(strrpos($cellValue, '(#') === false)
                {
                    if(!isset($taskLang->{$field . 'List'}) or !is_array($taskLang->{$field . 'List'})) continue;

                    /* When the cell value is key of list then eq the key. */
                    $listKey = array_keys($taskLang->{$field . 'List'});
                    unset($listKey[0]);
                    unset($listKey['']);

                    $fieldKey = array_search($cellValue, $taskLang->{$field . 'List'});
                    if($fieldKey) $datas[$key]->$field = array_search($cellValue, $taskLang->{$field . 'List'});
                }
                else
                {
                    $id = trim(substr($cellValue, strrpos($cellValue,'(#') + 2), ')');
                    $datas[$key]->$field = $id;
                }
            }

        }
        return $datas;
    }
}
