<?php
class portModel extends model
{
    /*  Port Module configs . */
    public $portConfig;

    public $portLang;

    /* From model configs .*/
    public $modelConfig;

    public $modelLang;

    public $maxImport;

    public $modelFieldList;

    public $templateFields;

    public $exportFields;

    public $modelListFields;

    /**
     * The construc method, to do some auto things.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->maxImport  = isset($_COOKIE['maxImport']) ? $_COOKIE['maxImport'] : 0;
        $this->portConfig = $this->config->port;
        $this->portLang   = $this->lang->port;
    }

    /**
     * Commom Actions
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function commonActions($model = '')
    {
        if($model)
        {
            $this->loadModel($model);
            $this->modelConfig     = $this->config->$model;
            $this->modelLang       = $this->lang->$model;
            $this->modelFieldList  = $this->config->$model->datatable->fieldList;
            $this->modelListFields = explode(',', $this->config->$model->listFields);
        }
    }

    /**
     * Check tmpFile.
     *
     * @access public
     * @return void
     */
    public function checkTmpFile()
    {
        $file      = $this->session->fileImportFileName;
        $tmpPath   = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile   = $tmpPath . DS . md5(basename($file));

        if($this->maxImport and file_exists($tmpFile)) return $tmpFile;
        return false;
    }

    /**
     * Check suhosin info.
     *
     * @param  array  $datas
     * @access public
     * @return void
     */
    public function checkSuhosinInfo($datas = array())
    {
        if(empty($datas)) return;
        $current = (array)current($datas);

        /* Judge whether the editedTasks is too large and set session. */
        $countInputVars  = count($datas) * count($current); // Count all post datas
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo) return extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);
        return '';
    }

    /**
     * Create tmpFile.
     *
     * @param  int    $objectDatas
     * @access public
     * @return void
     */
    public function createTmpFile($objectDatas)
    {
        $file      = $this->session->fileImportFileName;
        $tmpPath   = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile   = $tmpPath . DS . md5(basename($file));

        if(file_exists($tmpFile)) unlink($tmpFile);
        file_put_contents($tmpFile, serialize($objectDatas));
        $this->session->set('tmpFile', $tmpFile);
    }

    /**
     * Check Required fields .
     *
     * @param  int    $model
     * @param  int    $line
     * @param  int    $data
     * @access public
     * @return void
     */
    public function checkRequired($model, $line, $data)
    {
        if(isset($this->config->$model->create->requiredFields))
        {
            $requiredFields = explode(',', $this->config->$model->create->requiredFields);
            foreach($requiredFields as $requiredField)
            {
                $requiredField = trim($requiredField);
                if(empty($data[$requiredField])) dao::$errors[] = sprintf($this->lang->port->noRequire, $line, $this->lang->$model->$requiredField);
            }
        }
    }

    /**
     * Delete sheet2 from import xlsx or xls file.
     *
     * @param  string $filePath
     * @access public
     * @return void
     */
    public function cutFile($filePath = '')
    {
        if(file_exists($filePath))
        {
            $tmpPath = $this->app->getAppRoot() . 'tmp/import/excel';
            $this->app->loadClass('pclzip', true);
            $zip   = new pclzip($filePath);
            $zip->extract(PCLZIP_OPT_PATH, $tmpPath);

            $sheet2Path = $tmpPath . '/xl/worksheets/sheet2.xml';
            if(file_exists($sheet2Path))
            {
                $sheet2xmlPath  = $tmpPath . '/[Content_Types].xml';
                $sheet2reslPath = $tmpPath . '/xl/_rels/workbook.xml.rels';
                $sheet2wookbookPath = $tmpPath . '/xl/workbook.xml';

                $sheet2xml  = '<Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
                $sheet2resl = '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet2.xml"/>';

                $xml  = file_get_contents($sheet2xmlPath);
                $resl = file_get_contents($sheet2reslPath);
                $wookbook = file_get_contents($sheet2wookbookPath);

                $xml  = str_ireplace($sheet2xml,'', $xml);
                $resl = str_ireplace($sheet2resl,'', $resl);
                $wookbook = preg_replace('/<sheet[^>]*sheetId="2[^>]*>/', '', $wookbook);

                file_put_contents($sheet2xmlPath, $xml);
                file_put_contents($sheet2reslPath, $resl);
                file_put_contents($sheet2wookbookPath, $wookbook);
                @unlink($sheet2Path);
            }

            $result = $zip->create($tmpPath,PCLZIP_OPT_REMOVE_PATH,$tmpPath);
            $this->deleteDir($tmpPath);
        }
    }

    /**
     * Delete directory.
     *
     * @param string $dir
     */
    public function deleteDir($dir)
    {
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            } elseif (is_file($dir . '/' . $file)) {
                unlink($dir . '/' . $file);
            } elseif (is_dir($dir . '/' . $file)) {
                $this->deleteDir($dir . '/' . $file);
            }
        }

        rmdir($dir);
    }

    /**
     * export
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function export($model = '')
    {
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
     * Init postFields.
     *
     * @access public
     * @return void
     */
    public function initPostFields($model = '')
    {
        $datas = fixer::input('post')->get();
        $objectData = array();
        foreach ($datas as $field => $data)
        {
            if(is_array($data))
            {
                foreach($data as $key => $value)
                {
                    if(is_array($value) and array_key_exists($field, $this->config->$model->fieldList) and $this->config->$model->fieldList[$field]['control'] == 'multiple') $value = implode(',', $value);
                    $objectData[$key][$field] = $value;
                }
            }
        }
        return $objectData;
    }

    /**
     * Init FieldList .
     *
     * @param  int    $model
     * @param  string $fields
     * @param  int    $withKey
     * @access public
     * @return void
     */
    public function initFieldList($model, $fields = '', $withKey = true)
    {
        $this->commonActions($model);
        $this->mergeConfig($model);

        $this->portConfig->sysDataList = $this->initSysDataFields();
        $portFieldList = $this->portConfig->fieldList;

        if(empty($fields)) return false;

        if(!is_array($fields)) $fields = explode(',', $fields);
        $fieldList = array();
        /* build module fieldList. */
        foreach ($fields as $key => $field)
        {
            $field = trim($field);
            $modelFieldList = isset($this->modelFieldList[$field]) ? $this->modelFieldList[$field] : array();

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

        $this->commonActions($model);

        if(!empty($this->modelConfig->fieldList[$field]['title'])) return $this->modelLang->{$this->modelConfig->fieldList[$field]['title']};
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
     * @param  string $fieldValue
     * @param  int    $withKey
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
            if(strpos($this->modelConfig->sysLangFields, $field) and !empty($this->modelLang->{$field.'List'})) return $this->modelLang->{$field.'List'};
            if(strpos($this->modelConfig->sysDataFields, $field) and !empty($this->portConfig->sysDataList[$field])) return $this->portConfig->sysDataList[$field];
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
     * Init system datafields list.
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
     * Init tmpFile.
     *
     * @param  int    $created
     * @access public
     * @return void
     */
    public function initTmpFile($created = true)
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
            if(file_exists($this->session->fileImportFileName)) unlink($this->session->fileImportFileName);
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

        if($created) $this->createTmpFile($tmpFile, $objectDatas);

        return $objectDatas;
    }

    /**
     * Get showImport datas.
     *
     * @param  string $model
     * @param  string $filter
     * @access public
     * @return void
     */
    public function format($model = '', $filter = '')
    {
        /* Bulid import paris (field => name) .*/
        $fields  = $this->getImportFields($model);

        /* Check tmpfile. */
        $tmpFile = $this->checkTmpFile();

        /* If tmpfile not isset create tmpfile .*/
        if(!$tmpFile)
        {
            $rows      = $this->getRowsFromExcel();

            $modelData = $this->processRows4Fields($rows, $fields);

            $modelData = $this->getNatureDatas($model, $modelData, $filter);

            $this->createTmpFile($modelData);
        }
        else
        {
            $modelData = $this->getDatasByFile($tmpFile);
        }

        return $modelData;
    }

    /**
     * Get rows from excel.
     *
     * @access public
     * @return void
     */
    public function getRowsFromExcel()
    {
        $rows = $this->file->getRowsFromExcel($this->session->fileImportFileName);
        if(is_string($rows))
        {
            if($this->session->fileImportFileName) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            echo js::alert($rows);
            die(js::locate('back'));
        }
        return $rows;

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
     * Set list value.
     *
     * @param  int    $model
     * @param  int    $fieldList
     * @access public
     * @return void/array
     */
    public function setListValue($model, $fieldList)
    {
        $lists = array();
        $this->commonActions($model);

        if(!empty($this->modelListFields))
        {
            $listFields = $this->modelListFields;
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

        if(!empty($this->modelConfig->cascade))
        {
            $lists = $this->getCascadeList($model, $lists);
            $lists['cascade'] = $this->modelConfig->cascade;
        }

        return $lists;
    }

    /**
     * Get cascade list for export excel.
     *
     * @param  int    $model
     * @param  int    $lists
     * @access public
     * @return void
     */
    public function getCascadeList($model, $lists)
    {
        $this->commonActions($model);
        if(!isset($this->modelConfig->cascade)) return $lists;

        $cascadeArray = $this->modelConfig->cascade;

        foreach($cascadeArray as $field => $linkFiled)
        {
            $fieldList     = $field . 'List';
            $linkFieldList = $linkFiled . 'List';
            $tmpFieldList = array();
            if(!empty($lists[$fieldList]) and !empty($lists[$linkFieldList]))
            {
                $table = zget($this->config->objectTables, $field);
                if(empty($table)) continue;

                $fieldIDList     = array_keys($lists[$fieldList]);
                $linkFieldIDList = array_keys($lists[$linkFieldList]);

                $fieldDatas = $this->dao->select("id, $linkFiled")->from($table)->where('id')->in($fieldIDList)->fetchPairs();

                if(empty($fieldDatas)) continue;
                foreach($fieldDatas as $id => $linkFieldID)
                {
                    $tmpFieldList[$linkFieldID][$id] = $lists[$fieldList][$id];
                }

                $lists[$fieldList] = $tmpFieldList;
            }
        }

        return $lists;
    }

    /**
     * Get Rows .
     *
     * @param  int    $model
     * @param  int    $fieldList
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getRows($model, $fieldList, $orderBy = 'id_desc')
    {
        $modelDatas = $this->getQueryDatas($model);

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
     * Get query datas.
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function getQueryDatas($model = '')
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
            $stmt = $this->dbh->query($queryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->cookie->checkedItem})" : ''));
            while($row = $stmt->fetch()) $modelDatas[$row->id] = $row;
        }

        return $modelDatas;
    }

    /**
     * Get related objects pairs.
     *
     * @param  string $model
     * @param  string $object
     * @param  string $pairs
     * @access public
     * @return void
     */
    public function getRelatedObjects($model = '', $object = '', $pairs = '')
    {
        /* Get objects. */
        $datas = $this->getQueryDatas($model);

        /* Get related objects id lists. */
        $relatedObjectIdList = array();
        $relatedObjects      = array();

        foreach($datas as $data)
        {
            $relatedObjectIdList[$data->$object]  = $data->$object;
        }

        if($object == 'openedBuild') $object = 'build';

        /* Get related objects title or names. */
        $table = $this->config->objectTables[$object];
        if($table) $relatedObjects = $this->dao->select($pairs)->from($table) ->where('id')->in($relatedObjectIdList)->fetchPairs();

        if($object == 'build') $relatedObjects= array('trunk' => $this->lang->trunk) + $relatedObjects;

        return $relatedObjects;
    }

    /**
     * Get nature datas.
     *
     * @param  int    $model
     * @param  int    $datas
     * @param  string $filter
     * @access public
     * @return void
     */
    public function getNatureDatas($model, $datas, $filter = '')
    {
        $lang = $this->lang->$model;
        foreach($datas as $key => $data)
        {
            foreach($data as $field => $cellValue)
            {
                if(strpos($filter, $field) !== false) continue;
                if(is_array($cellValue)) continue;
                if(strrpos($cellValue, '(#') === false)
                {
                    if(!isset($lang->{$field . 'List'}) or !is_array($lang->{$field . 'List'})) continue;

                    /* When the cell value is key of list then eq the key. */
                    $listKey = array_keys($lang->{$field . 'List'});
                    unset($listKey[0]);
                    unset($listKey['']);

                    $fieldKey = array_search($cellValue, $lang->{$field . 'List'});
                    if($fieldKey) $datas[$key]->$field = array_search($cellValue, $lang->{$field . 'List'});
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

    /**
     * Get datas by file.
     *
     * @access public
     * @return void
     */
    public function getDatasByFile($file)
    {
        if(file_exists($file)) return  unserialize(file_get_contents($file));
    }

    /**
     * Get pagelist for datas.
     *
     * @param  int    $datas
     * @param  int    $pagerID
     * @access public
     * @return void
     */
    public function getPageDatas($datas, $pagerID = 1)
    {
        $result = new stdClass();
        $result->allCount = count($datas);
        $result->allPager = 1;
        $result->pagerID  = $pagerID;

        $maxImport = $this->maxImport;
        if($result->allCount > $this->config->file->maxImport)
        {
            if(empty($maxImport))
            {
                $result->maxImport = $maxImport;
                $result->datas     = $datas;
                return $result;
            }

            $result->allPager = ceil($result->allCount / $maxImport);
            $datas = array_slice($datas, ($pagerID - 1) * $maxImport, $maxImport, true);
        }

        $result->maxImport = $maxImport;
        $result->isEndPage = $pagerID >= $result->allPager;
        $result->datas     = $datas;

        if(empty($datas)) die(js::locate('back'));
        return $result;
    }

    /**
     * Get import fields.
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function getImportFields($model = '')
    {
        $this->commonActions($model);
        $modelLang = $this->lang->$model;
        $fields    = explode(',', $this->modelConfig->templateFields);
        array_unshift($fields, 'id');
        foreach($fields as $key => $fieldName)
        {
            $fieldName = trim($fieldName);
            $fields[$fieldName] = isset($modelLang->$fieldName) ? $modelLang->$fieldName : $fieldName;
            unset($fields[$key]);
        }

        return $fields;
    }

    /**
     * Get WorkFlow fields .
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function getWorkFlowFields($model)
    {
        if($this->config->edition != 'open')
        {
            $appendFields = $this->loadModel('workflowaction')->getFields($model, 'showimport', false);

            foreach($appendFields as $appendField) $this->config->$model->exportFields .= ',' . $appendField->field;

            $this->session->set('appendFields', $appendFields);
            $this->session->set('notEmptyRule', $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty'));
        }
    }

    /**
     * Get view datas.
     *
     * @param  string $model
     * @param  string $datas
     * @param  string $title
     * @param  int    $pagerID
     * @access public
     * @return void
     */
    public function getViewDatas($model = '', $datas = '', $pagerID = 1, $insert)
    {
        $suhosinInfo = $this->checkSuhosinInfo($datas->datas);

        $fields      = $this->config->$model->templateFields;

        $datas->requiredFields = $this->config->$model->create->requiredFields;
        $datas->allPager       = isset($datas->allPager) ? $datas->allPager : 1;
        $datas->pagerID        = $pagerID;
        $datas->isEndPage      = $pagerID >= $datas->allPager;
        $datas->maxImport      = $this->maxImport;
        $datas->dataInsert     = $insert;
        $datas->fields         = $this->initFieldList($model, $fields, false);
        $datas->suhosinInfo    = $suhosinInfo;
        $datas->model          = $model;

        return $datas;
    }

    /**
     * Update children datas.
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
     * Process rows for fields.
     *
     * @param  array  $rows
     * @param  array  $fields
     * @access public
     * @return void
     */
    public function processRows4Fields($rows = array(), $fields = array())
    {
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

            if(!empty($tmpArray->title))
            {
                $objectDatas[$currentRow] = $tmpArray;
            }
            elseif(!empty($tmpArray->name))
            {
                $objectDatas[$currentRow] = $tmpArray;
            }
            unset($tmpArray);
        }

        if(empty($objectDatas))
        {
            if(file_exists($this->session->fileImportFileName)) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            echo js::alert($this->lang->excel->noData);
            die(js::locate('back'));
        }

        return $objectDatas;
    }

    /**
     * Process child datas (task, story).
     *
     * @param  int    $objectID
     * @param  int    $data
     * @access public
     * @return void
     */
    public function processChildData($lastID, $data)
    {
        $parentID = $this->session->parentID ? $this->session->parentID : 0;
        if(strpos($data['name'], '&gt;') === 0)
        {
            if(!$parentID)
            {
                $parentID = $lastID;
                $this->session->set('parentID', $parentID);
            }
            $data['parent'] = $parentID;
            $data['name'] = ltrim($data['name'], '&gt;');
            $this->dao->update(TABLE_TASK)->set('parent')->eq('-1')->where('id')->eq($parentID)->exec();
        }
        else
        {
            $this->session->set('parentID', 0);
        }
        return $data;
    }

    /**
     * Save import datas.
     *
     * @param  int    $model
     * @param  int    $datas
     * @access public
     * @return void
     */
    public function saveImportDatas($model, $datas = '')
    {
        if(empty($datas)) $datas = $this->initPostFields($model);

        foreach($datas as $key => $data)
        {
            $subDatas = array();
            if(isset($data['subDatas']))
            {
                $subDatas = $data['subDatas'];
                unset($data['subDatas']);
            }

            if($this->post->insert) unset($data['id']);

            /* Check required field*/
            $this->checkRequired($model, $key, $data);

            if(!empty($objectID) and in_array($model, $this->config->port->hasChildDataFields))
            {
                $data = $this->processChildData($objectID, $data);
            }

            $table = zget($this->config->objectTables, $model);
            $this->dao->replace($table)->data($data)->autoCheck()->checkFlow()->exec();
            $objectID = $this->dao->lastInsertID();

            if(dao::isError()) die(js::error(dao::getError()));

            if(!empty($subDatas)) $this->saveSubTable($objectID, $subDatas);
            /* Create action*/
            if($this->post->insert) $this->loadModel('action')->create($model, $objectID, 'Opened', '');
        }

        if($this->post->isEndPage)
        {
            unlink($this->session->fileImportFileName);
            unlink($this->session->tmpFile);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
        }
    }

    /**
     * Save SubTable datas.
     *
     * @param  int    $lastInsertID
     * @param  int    $datas
     * @access public
     * @return void
     */
    public function saveSubTable($lastInsertID, $datas)
    {
        $table = zget($this->config->objectTables, $datas['name']);

        foreach($datas['datas'] as $key => $value)
        {
            $value[$datas['foreignkey']] = $lastInsertID;
            $this->dao->replace($table)->data($value)->autoCheck()->exec();
        }
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
    }

    /**
     * Read excel and format data .
     *
     * @param  string $model
     * @param  int    $pagerID
     * @param  string $insert
     * @param  string $filter
     * @access public
     * @return void
     */
    public function readExcel($model = '', $pagerID = 1, $insert = '', $filter = '')
    {
        /* Formatting excel data .*/
        $formatDatas  = $this->format($model, $filter);
        /* Get page by datas.*/
        $datas        = $this->getPageDatas($formatDatas, $pagerID);

        $suhosinInfo  = $this->checkSuhosinInfo($datas->datas);

        $importFields = $this->config->$model->templateFields;

        $this->getWorkFlowFields($model);

        $datas->requiredFields = $this->config->$model->create->requiredFields;
        $datas->allPager       = isset($datas->allPager) ? $datas->allPager : 1;
        $datas->pagerID        = $pagerID;
        $datas->isEndPage      = $pagerID >= $datas->allPager;
        $datas->maxImport      = $this->maxImport;
        $datas->dataInsert     = $insert;
        $datas->fields         = $this->initFieldList($model, $importFields, false);
        $datas->suhosinInfo    = $suhosinInfo;
        $datas->model          = $model;

        return $datas;
    }
}
