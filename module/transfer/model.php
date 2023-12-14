<?php
class transferModel extends model
{
    /* transfer Module configs. */
    public $transferConfig;

    public $transferLang;

    /* From model configs. */
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
        $this->transferConfig = $this->config->transfer;
        $this->transferLang   = $this->lang->transfer;
    }

    /**
     * Common Actions.
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
            $this->modelFieldList  = $this->config->$model->dtable->fieldList ?? array();
            $this->modelListFields = explode(',', $this->config->$model->listFields ?? '');
        }
    }

    /**
     * Check tmp file.
     *
     * @access public
     * @return void
     */
    public function checkTmpFile()
    {
        $file    = $this->session->fileImportFileName;
        $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
        $tmpFile = $tmpPath . DS . md5(basename($file));

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
        return;
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
     * Check Required fields.
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
                if(empty($data[$requiredField])) dao::$errors[] = sprintf($this->lang->transfer->noRequire, $line, $this->lang->$model->$requiredField);
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
            $tmpPath = $this->app->getAppRoot() . 'tmp/import/excel' . $this->app->user->account . time();
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
            $zfile  = $this->app->loadClass('zfile');
            $zfile->removeDir($tmpPath);
        }
    }

    /**
     * Export module data.
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function export($model = '')
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','100');

        $fields = $this->post->exportFields;

        /* Init config fieldList. */
        $fieldList = $this->initFieldList($model, $fields);

        $rows = $this->getRows($model, $fieldList);
        if($model == 'story')
        {
            $product = $this->loadModel('product')->getByID((int)$this->session->storyTransferParams['productID']);
            if($product and $product->shadow) foreach($rows as $id => $row) $rows[$id]->product = '';
        }

        $list = $this->setListValue($model, $fieldList);
        if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

        /* Get export rows and fields datas. */
        $exportDatas = $this->getExportDatas($fieldList, $rows);

        $fields = $exportDatas['fields'];
        $rows   = !empty($exportDatas['rows']) ? $exportDatas['rows'] : array();

        if($this->config->edition != 'open') list($fields, $rows) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $rows, $model);

        $this->post->set('rows',   $rows);
        $this->post->set('fields', $fields);
        $this->post->set('kind',   $model);
    }

    /**
     * Init postFields.
     *
     * @param  string $model
     * @access public
     * @return void
     */
    public function initPostFields($model = '')
    {
        $this->commonActions($model);
        $datas = fixer::input('post')->get();
        $objectData = array();
        foreach($datas as $field => $data)
        {
            if(is_array($data))
            {
                foreach($data as $key => $value)
                {
                    if(is_array($value)) $value = implode(',', $value);
                    $objectData[$key][$field] = $value;
                }
            }
        }
        return $objectData;
    }

    /**
     * Init FieldList.
     *
     * @param  string $model
     * @param  string $fields
     * @param  bool   $withKey
     * @access public
     * @return array
     */
    public function initFieldList($model, $fields = '', $withKey = true)
    {
        $this->commonActions($model);
        $this->mergeConfig($model);

        $this->transferConfig->sysDataList = $this->initSysDataFields();
        $transferFieldList = $this->transferConfig->fieldList;

        if(empty($fields)) return false;

        if(!is_array($fields)) $fields = explode(',', $fields);

        $fieldList = array();
        /* build module fieldList. */
        foreach($fields as $field)
        {
            $field = trim($field);
            if($model == 'bug' and $this->session->currentProductType == 'normal' and $field == 'branch') continue;

            $modelFieldList = isset($this->modelFieldList[$field]) ? $this->modelFieldList[$field] : array();

            foreach($transferFieldList as $transferField => $value)
            {
                if((!isset($modelFieldList[$transferField])) or $transferField == 'title')
                {
                    $modelFieldList[$transferField] = $this->transferConfig->fieldList[$transferField];

                    if(strpos($this->transferConfig->initFunction, $transferField) !== false)
                    {
                        $funcName = 'init' . ucfirst($transferField);
                        $modelFieldList[$transferField] = $this->$funcName($model, $field);
                    }
                }
            }

            $modelFieldList['values'] = $this->initValues($model, $field, $modelFieldList, $withKey);
            $fieldList[$field] = $modelFieldList;
        }

        if(!empty($fieldList['mailto']))
        {
            $fieldList['mailto']['control'] = 'multiple';
            $fieldList['mailto']['values']  = $this->transferConfig->sysDataList['user'];
        }

        if($this->config->edition != 'open')
        {
            /* Set workflow fields. */
            $workflowFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)
                ->where('module')->eq($model)
                ->andWhere('buildin')->eq(0)
                ->fetchAll('id');

            foreach($workflowFields as $field)
            {
                if(!in_array($field->control, array('select', 'radio', 'multi-select'))) continue;
                if(!isset($fields[$field->field]) and !array_search($field->field, $fields)) continue;
                if(empty($field->options)) continue;

                $field   = $this->loadModel('workflowfield')->processFieldOptions($field);
                $options = $this->workflowfield->getFieldOptions($field, true);
                if($options)
                {
                    $control = $field->control == 'multi-select' ? 'multiple' : 'select';
                    $fieldList[$field->field]['title']   = $field->name;
                    $fieldList[$field->field]['control'] = $control;
                    $fieldList[$field->field]['values']  = $options;
                    $fieldList[$field->field]['from']    = 'workflow';
                    $this->config->$model->listFields .=  ',' . $field->field;
                }
            }
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
        if(isset($this->lang->$model->$field))
        {
            $title = $this->lang->$model->$field;
        }
        elseif(isset($this->lang->$model->{$field . 'AB'}))
        {
            $title = $this->lang->$model->{$field . 'AB'};
        }
        elseif(isset($this->lang->transfer->reservedWord[$field]))
        {
            $title = $this->lang->transfer->reservedWord[$field];
        }

        return $title;
    }

    /**
     * Init Control.
     *
     * @param  string $field
     * @access public
     * @return void
     */
    public function initControl($model, $field)
    {
        if(isset($this->modelFieldList[$field]['control']))    return $this->modelFieldList[$field]['control'];
        if(isset($this->modelLang->{$field.'List'}))           return 'select';
        if(isset($this->modelFieldList[$field]['dataSource'])) return 'select';

        if(strpos($this->transferConfig->sysDataFields, $field) !== false) return 'select';
        return $this->transferConfig->fieldList['control'];
    }

    /**
     * Init Values.
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

        if($values and (strpos($this->transferConfig->sysDataFields, $values) !== false)) return $this->transferConfig->sysDataList[$values];

        if(!$fieldValue['dataSource']) return $values;

        extract($fieldValue['dataSource']); // $module, $method, $params, $pairs, $sql, $lang

        if(!empty($module) and !empty($method))
        {
            $params = !empty($params) ? $params : '';
            $pairs  = !empty($pairs)  ? $pairs : '';
            $values = $this->getSourceByModuleMethod($model, $module, $method, $params, $pairs);
        }
        elseif(!empty($lang))
        {
            $values = $this->getSourceByLang($lang);
        }

        /* If empty values put system datas. */
        if(empty($values))
        {
            if(strpos($this->modelConfig->sysLangFields, $field) !== false and !empty($this->modelLang->{$field.'List'})) return $this->modelLang->{$field.'List'};
            if(strpos($this->modelConfig->sysDataFields, $field) !== false and !empty($this->transferConfig->sysDataList[$field])) return $this->transferConfig->sysDataList[$field];
        }

        if(is_array($values) and $withKey)
        {
            unset($values['']);
            foreach($values as $key => $value) $values[$key] = $value . "(#$key)";
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
     * @return array
     */
    public function initSysDataFields()
    {
        $this->commonActions();
        $dataList = array();

        $sysDataFields = explode(',', $this->transferConfig->sysDataFields);

        foreach($sysDataFields as $field)
        {
            $dataList[$field] = $this->loadModel($field)->getPairs();
            if(!isset($dataList[$field][0])) $dataList[$field][0] = '';

            sort($dataList[$field]);

            if($field == 'user')
            {
                $dataList[$field] = $this->loadModel($field)->getPairs('noclosed|nodeleted|noletter');

                unset($dataList[$field]['']);

                if(!in_array(strtolower($this->app->methodName), array('ajaxgettbody', 'ajaxgetoptions', 'showimport'))) foreach($dataList[$field] as $key => $value) $dataList[$field][$key] = $value . "(#$key)";
            }
        }

        return $dataList;
    }

    /**
     * Get showImport datas.
     *
     * @param  string $model
     * @param  string $filter
     * @access public
     * @return array
     */
    public function format($model = '', $filter = '')
    {
        /* Bulid import paris (field => name). */
        $fields  = $this->getImportFields($model);

        /* Check tmpfile. */
        $tmpFile = $this->checkTmpFile();

        /* If tmpfile not isset create tmpfile. */
        if(!$tmpFile)
        {
            $rows      = $this->getRowsFromExcel();
            $modelData = $this->processRows4Fields($rows, $fields);
            $modelData = $this->getNatureDatas($model, $modelData, $filter, $fields);

            $this->createTmpFile($modelData);
        }
        else
        {
            $modelData = $this->getDatasByFile($tmpFile);
        }

        $this->mergeConfig($model);
        $modelData = $this->processDate($modelData);
        if(isset($fields['id'])) unset($fields['id']);
        $this->session->set($model . 'TemplateFields',  implode(',', array_keys($fields)));

        return $modelData;
    }

    /**
     * Process datas, convert date to YYYY-mm-dd, convert datetime to YYYY-mm-dd HH:ii:ss.
     *
     * @param  array   $datas
     * @access public
     * @return array
     */
    public function processDate($datas)
    {
        foreach($datas as $index => $data)
        {
            foreach($data as $field => $value)
            {
                if(strpos($this->modelConfig->dateFields, $field) !== false or strpos($this->modelConfig->datetimeFields, $field) !== false) $data->$field = $this->loadModel('common')->formatDate($value);
            }
            $datas[$index] = $data;
        }
        return $datas;
    }

    /**
     * Get rows from excel.
     *
     * @access public
     * @return array
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
            return print(js::locate('back'));
        }
        return $rows;

    }

    /**
     * Get field values by method.
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
        $getParams = $this->session->{$model . 'TransferParams'};

        if($params)
        {
            $params = explode('&', $params);
            foreach($params as $param => $value)
            {
                if(strpos($value, '$') !== false) $params[$param] = $getParams[ltrim($value, '$')];
            }
        }

        /* If this method has multiple parameters use call_user_func_array. */
        if(is_array($params))
        {
            $values = call_user_func_array(array($this->loadModel($module), $method), $params);
        }
        elseif($params)
        {
            $values = $this->loadModel($module)->$method($params);
        }
        else
        {
            $values = $this->loadModel($module)->$method();
        }

        if(!empty($pairs))
        {
            $valuePairs = array();
            foreach($values as $key => $value)
            {
                if(is_object($value)) $value = get_object_vars($value);

                $valuePairs[$key] = $value[$pairs[1]];
                if(!empty($pairs[0])) $valuePairs[$value[$pairs[0]]] = $value[$pairs[1]];
            }
            $values = $valuePairs;
            if(reset($values) and (current($values) or (current($values) == 0))) $values = array('');
        }

        return $values;
    }

    /**
     * Get field values by lang.
     *
     * @access public
     * @return void|array
     */
    public function getSourceByLang($lang)
    {
        return isset($this->modelLang->$lang) ? $this->modelLang->$lang : '';
    }

    /**
     * Get ExportDatas.
     *
     * @param  array  $fieldList
     * @param  array  $rows
     * @access public
     * @return array
     */
    public function getExportDatas($fieldList, $rows = array())
    {
        if(empty($fieldList)) return array();

        $exportDatas    = array();
        $dataSourceList = array();

        foreach($fieldList as $key => $field)
        {
            $exportDatas['fields'][$key] = $field['title'];
            if($field['values'])
            {
                $exportDatas[$key] = $field['values'];
                $dataSourceList[]  = $key;
            }
        }

        if(empty($rows)) return $exportDatas;

        $exportDatas['user'] = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter');

        foreach($rows as $id => $values)
        {
            foreach($values as $field => $value)
            {
                if(isset($fieldList[$field]['from']) and $fieldList[$field]['from'] == 'workflow') continue;
                if(in_array($field, $dataSourceList))
                {
                    if($fieldList[$field]['control'] == 'multiple')
                    {
                        $multiple     = '';
                        $separator    = $field == 'mailto' ? ',' : "\n";
                        $multipleLsit = explode(',', $value);

                        foreach($multipleLsit as $key => $tmpValue) $multipleLsit[$key] = zget($exportDatas[$field], $tmpValue);
                        $multiple = implode($separator, $multipleLsit);
                        $rows[$id]->$field = $multiple;
                    }
                    else
                    {
                        $rows[$id]->$field = zget($exportDatas[$field], $value, $value);
                    }
                }
                elseif(strpos($this->config->transfer->userFields, $field) !== false)
                {
                    /* if user deleted when export set userFields is itself. */
                    $rows[$id]->$field = zget($exportDatas['user'], $value);
                }

                /* if value = 0 or value = 0000:00:00 set value = ''. */
                if(is_string($value) and ($value == '0' or substr($value, 0, 4) == '0000')) $rows[$id]->$field = '';
            }
        }

        $exportDatas['rows'] = array_values($rows);
        return $exportDatas;
    }

    /**
     * Get files by model.
     *
     * @param  string $model
     * @param  array  $datas
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
                    $fileURL      = common::getSysURL() . helper::createLink('file', 'download', "fileID={$file->id}");
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
                if(empty($field)) continue;
                $listName = $field . 'List';
                if(!empty($_POST[$listName])) continue;
                if(!empty($fieldList[$field]))
                {
                    $lists[$listName] = $fieldList[$field]['values'];
                    if(strpos($this->config->$model->sysLangFields, $field)) $lists[$listName] = implode(',', $fieldList[$field]['values']);
                }
                if(is_array($lists[$listName])) $this->config->excel->sysDataField[] = $field;
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
            $tmpFieldList  = array();
            if(!empty($lists[$fieldList]) and !empty($lists[$linkFieldList]))
            {
                $table = zget($this->config->objectTables, $field);
                if(empty($table)) continue;

                $fieldIDList     = array_keys($lists[$fieldList]);
                $fieldDatas      = $this->dao->select("id, $linkFiled")->from($table)->where('id')->in($fieldIDList)->fetchPairs();

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
     * Get Rows.
     *
     * @param  string        $model
     * @param  object|string|array $fieldList
     * @access public
     * @return void
     */
    public function getRows(string $model, object|string|array $fieldList)
    {
        $modelDatas = $this->getQueryDatas($model);

        if(is_object($fieldList)) $fieldList = (array) $fieldList;
        if(isset($fieldList['files'])) $modelDatas = $this->getFiles($model, $modelDatas);

        $rows = !empty($_POST['rows']) ? $_POST['rows'] : '';
        if($rows)
        {
            foreach($rows as $id => $row)
            {
                $modelDatas[$id] = (object) array_merge((array)$modelDatas[$id], (array)$row);
            }
        }

        /* Deal children datas and multiple tasks. */
        if($modelDatas) $modelDatas = $this->updateChildDatas($modelDatas);

        /* Deal linkStories datas. */
        if($modelDatas and isset($fieldList['linkStories'])) $modelDatas = $this->updateLinkStories($modelDatas);

        return $modelDatas;
    }

    /**
     * Update LinkStories datas.
     *
     * @param  array $stories
     * @access public
     * @return array
     */
    public function updateLinkStories($stories)
    {
        $productIDList = array();
        foreach($stories as $story) $productIDList[] = $story->product;
        $productIDList = array_unique($productIDList);

        $storyDatas = end($stories);
        $lastType   = $storyDatas->type;

        if($storyDatas->type == 'requirement')
        {
            $stories = $this->loadModel('story')->mergePlanTitleAndChildren($productIDList, $stories, $lastType);
        }
        elseif($storyDatas->type == 'story')
        {
             return $stories;
        }

        return $stories;
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
        $queryCondition    = $this->session->{$model . 'QueryCondition'};
        $onlyCondition     = $this->session->{$model . 'OnlyCondition'};
        $transferCondition = $this->session->{$model . 'TransferCondition'};

        $modelDatas = array();

        if($transferCondition)
        {
            $selectKey = 'id';
            $stmt = $this->dbh->query($transferCondition);
            while($row = $stmt->fetch())
            {
                if($selectKey !== 't1.id' and isset($row->$model) and isset($row->id)) $row->id = $row->$model;
                $modelDatas[$row->id] = $row;
            }

            return $modelDatas;
        }

        /* Fetch the scene's cases. */
        if($model == 'testcase') $queryCondition = preg_replace("/AND\s+t[0-9]\.scene\s+=\s+'0'/i", '', $queryCondition);

        $checkedItem = $this->post->checkedItem ? $this->post->checkedItem : $this->cookie->checkedItem;

        if($onlyCondition and $queryCondition)
        {
            $table = zget($this->config->objectTables, $model);
            if(isset($this->config->$model->transfer->table)) $table = $this->config->$model->transfer->table;
            if($model == 'story') $queryCondition = str_replace('story', 'id', $queryCondition);
            $modelDatas = $this->dao->select('*')->from($table)->alias('t1')
                ->where($queryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($checkedItem)->fi()
                ->fetchAll('id');
        }
        elseif($queryCondition)
        {
            $selectKey = 'id';
            if($model == 'testcase') $model = 'case';
            preg_match_all('/[`"]' . $this->config->db->prefix . $model .'[`"] AS ([\w]+) /', $queryCondition, $matches);
            if(isset($matches[1][0])) $selectKey = "{$matches[1][0]}.id";

            $stmt = $this->dbh->query($queryCondition . ($this->post->exportType == 'selected' ? " AND $selectKey IN(" . ($checkedItem ? $checkedItem : '0') . ")" : ''));
            while($row = $stmt->fetch())
            {
                if($selectKey !== 't1.id' and isset($row->$model) and isset($row->id)) $row->id = $row->$model;
                $modelDatas[$row->id] = $row;
            }
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

        foreach($datas as $data) $relatedObjectIdList[$data->$object] = $data->$object;

        if($object == 'openedBuild') $object = 'build';

        /* Get related objects title or names. */
        $table = $this->config->objectTables[$object];
        if($table) $relatedObjects = $this->dao->select($pairs)->from($table) ->where('id')->in($relatedObjectIdList)->fetchPairs();

        if($object == 'build') $relatedObjects = array('trunk' => $this->lang->trunk) + $relatedObjects;

        return $relatedObjects;
    }

    /**
     * Get nature datas.
     *
     * @param  int    $model
     * @param  int    $datas
     * @param  string $filter
     * @param  string $fields
     * @access public
     * @return void
     */
    public function getNatureDatas($model, $datas, $filter = '', $fields = '')
    {
        $fieldList = $this->initFieldList($model, array_keys($fields), false);
        $lang = $this->lang->$model;

        foreach($datas as $key => $data)
        {
            foreach($data as $field => $cellValue)
            {
                if(empty($cellValue)) continue;
                if(strpos($this->transferConfig->dateFields, $field) !== false and helper::isZeroDate($cellValue)) $datas[$key]->$field = '';
                if(is_array($cellValue)) continue;

                if(!empty($fieldList[$field]['from']) and in_array($fieldList[$field]['control'], array('select', 'multiple')))
                {
                    $control = $fieldList[$field]['control'];
                    if($control == 'multiple')
                    {
                        $cellValue = explode("\n", $cellValue);
                        foreach($cellValue as &$value) $value = array_search($value, $fieldList[$field]['values'], true);
                        $datas[$key]->$field = implode(',', $cellValue);
                    }
                    else
                    {
                        $datas[$key]->$field = array_search($cellValue, $fieldList[$field]['values']);
                    }
                }
                elseif(strrpos($cellValue, '(#') === false)
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
                    $control = !empty($this->modelFieldList[$field]['control']) ? $this->modelFieldList[$field]['control'] : '';
                    if($control == 'multiple')
                    {
                        $cellValue = explode("\n", $cellValue);
                        foreach($cellValue as &$value)
                        {
                            $value = trim(substr($value, strrpos($value,'(#') + 2), ')');
                        }
                        $cellValue = array_filter($cellValue, function($v) {return (empty($v) && $v == '0') || !empty($v);});
                        $datas[$key]->$field = implode(',', $cellValue);
                    }
                }
            }

        }
        return $datas;
    }

    /**
     * Get datas by file.
     *
     * @param  int    $file
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

        if(!$maxImport) $this->maxImport = $result->allCount;
        $result->maxImport = $maxImport;
        $result->isEndPage = $pagerID >= $result->allPager;
        $result->datas     = $datas;

        $this->session->set('insert', true);

        foreach($datas as $data)
        {
            if(isset($data->id)) $this->session->set('insert', false);
        }

        if(empty($datas)) return print(js::locate('back'));
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

        if($this->config->edition != 'open')
        {
            $appendFields = $this->loadModel('workflowaction')->getFields($model, 'showimport', false);
            foreach($appendFields as $appendField)
            {
                if(!$appendField->buildin and $appendField->show) $fields[$appendField->field] = $appendField->name;
            }
        }

        return $fields;
    }

    /**
     * Get WorkFlow fields.
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

        /* Move child data after parent data. */
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

            if(!empty($tmpArray->title) and !empty($tmpArray->name)) $objectDatas[$currentRow] = $tmpArray;
            unset($tmpArray);
        }

        if(empty($objectDatas))
        {
            if(file_exists($this->session->fileImportFileName)) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
            echo js::alert($this->lang->excel->noData);
            return print(js::locate('back'));
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

            /* Check required field. */
            $this->checkRequired($model, $key, $data);

            if(!empty($objectID) and in_array($model, $this->config->transfer->hasChildDataFields))
            {
                $data = $this->processChildData($objectID, $data);
            }

            $table = zget($this->config->objectTables, $model);
            $this->dao->replace($table)->data($data)->autoCheck()->checkFlow()->exec();
            $objectID = $this->dao->lastInsertID();

            if(dao::isError()) return print(js::error(dao::getError()));

            if(!empty($subDatas)) $this->saveSubTable($objectID, $subDatas);

            /* Create action. */
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

        foreach($datas['datas'] as $value)
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
        $transferConfig  = $this->transferConfig;
        $modelConfig = $this->modelConfig;
        if(!isset($modelConfig->export)) $modelConfig->export = new stdClass();
        if(!isset($modelConfig->import)) $modelConfig->export = new stdClass();

        $modelConfig->dateFields     = isset($modelConfig->dateFields)     ? $modelConfig->dateFields     : $transferConfig->dateFields;
        $modelConfig->datetimeFields = isset($modelConfig->datetimeFields) ? $modelConfig->datetimeFields : $transferConfig->datetimeFields;
        $modelConfig->sysLangFields  = isset($modelConfig->sysLangFields)  ? $modelConfig->sysLangFields  : $transferConfig->sysLangFields;
        $modelConfig->sysDataFields  = isset($modelConfig->sysDataFields)  ? $modelConfig->sysDataFields  : $transferConfig->sysDataFields;
        $modelConfig->listFields     = isset($modelConfig->listFields)     ? $modelConfig->listFields     : $transferConfig->listFields;
    }

    /**
     * Read excel and format data.
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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','100');

        /* Formatting excel data. */
        $formatDatas  = $this->format($model, $filter);

        /* Get page by datas. */
        $datas        = $this->getPageDatas($formatDatas, $pagerID);

        $suhosinInfo  = $this->checkSuhosinInfo($datas->datas);

        $importFields = !empty($_SESSION[$model . 'TemplateFields']) ? $_SESSION[$model . 'TemplateFields'] : $this->config->$model->templateFields;

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

    /**
     * Build NextList.
     *
     * @param  array  $list
     * @param  int    $lastID
     * @param  string $fields
     * @param  int    $pagerID
     * @param  string $model
     * @access public
     * @return void
     */
    public function buildNextList($list = array(), $lastID = 0, $fields = '', $pagerID = 1, $model = '')
    {
        $html  = '';
        $key   = key($list);
        $addID = 1;
        if($model == 'task') $members = $this->loadModel('user')->getTeamMemberPairs($this->session->taskTransferParams['executionID'], 'execution');

        $showImportCount = $this->config->transfer->lazyLoading ? $this->config->transfer->showImportCount : $this->maxImport;
        $lastRow         = $lastID + $key + $showImportCount;

        foreach($list as $row => $object)
        {
            if($row <= $lastID) continue;
            if($row > $lastRow) break;

            $tmpList[$row] = $object;
            $trClass = '';
            if($row == $lastRow) $trClass = 'showmore';
            $html .= "<tr class='text-top $trClass' data-id=$row>";
            $html .= '<td>';

            if(!empty($object->id))
            {
                $html .= $object->id . html::hidden("id[$row]", $object->id);
            }
            else
            {
                $sub = " <sub style='vertical-align:sub;color:gray'>{$this->lang->transfer->new}</sub>";
                if($model == 'task') $sub = (strpos($object->name, '>') === 0) ? " <sub style='vertical-align:sub;color:red'>{$this->lang->task->children}</sub>" : $sub;
                $addID ++;
                $html .= $addID . $sub;
            }
            $html .= "</td>";

            foreach($fields as $field => $value)
            {
                $control  = $value['control'];
                $values   = $value['values'];
                $name     = "{$field}[$row]";
                $selected = isset($object->$field) ? $object->$field : '';
                if($model and $control == 'hidden' and isset($this->session->{$model.'TransferParams'}[$field. 'ID'])) $selected = $this->session->{$model . 'TransferParams'}[$field. 'ID'];

                $options = array();
                if($control == 'select')
                {
                    if(!empty($values[$selected])) $options = array($selected => $values[$selected]);
                    if(empty($options) and is_array($values)) $options = array_slice($values, 0, 1, true);
                    if(!isset($options['']) and !in_array($field, $this->config->transfer->requiredFields)) $options[''] = '';
                }

                if($control == 'select')       $html .= '<td>' . html::select("$name", $options, $selected, "class='form-control picker-select nopicker' data-field='{$field}' data-index='{$row}'") . '</td>';

                elseif($control == 'multiple') $html .= '<td>' . html::select($name . "[]", $values, $selected, "multiple class='form-control picker-select nopicker' data-field='{$field}' data-index='{$row}'") . '</td>';

                elseif($control == 'date')     $html .= '<td>' . html::input("$name", $selected, "class='form-control form-date' autocomplete='off'") . '</td>';

                elseif($control == 'datetime') $html .= '<td>' . html::input("$name", $selected, "class='form-control form-datetime' autocomplete='off'") . '</td>';

                elseif($control == 'hidden')   $html .= html::hidden("$name", $selected);

                elseif($control == 'textarea')
                {
                    if($model == 'bug' and $field == 'steps') $selected = str_replace("\n\n\n\n\n\n", '', $selected);
                    $html .= '<td>' . html::textarea("$name", $selected, "class='form-control' cols='50' rows='1'") . '</td>';
                }
                elseif($field == 'stepDesc' or $field == 'stepExpect' or $field == 'precondition')
                {
                    $stepDesc = $this->process4Testcase($field, $tmpList, $row);
                    if($stepDesc) $html .= '<td>' . $stepDesc . '</td>';
                }
                elseif($field == 'estimate')
                {
                    $html .= '<td>';
                    if(!empty($object->estimate) and is_array($object->estimate))
                    {
                        $html .= "<table class='table-borderless'>";
                        foreach($object->estimate as $account => $estimate)
                        {
                            $html .= '<tr>';
                            $html .= '<td class="c-team">' . html::select("team[$row][]", $members, $account, "class='form-control chosen'") . '</td>';
                            $html .= '<td class="c-estimate-1">' . html::input("estimate[$row][]", $estimate, "class='form-control' autocomplete='off'")  . '</td>';
                            $html .= '</tr>';
                        }
                        $html .= "</table>";
                    }
                    else
                    {
                        $html .= html::input("estimate[$row]", !empty($object->estimate) ? $object->estimate : '', "class='form-control'");
                    }
                    $html .= '</td>';
                }
                elseif(strpos($this->transferConfig->textareaFields, $field) !== false) $html .= '<td>' . html::textarea("$name", $selected, "class='form-control' style='overflow:hidden;'") . '</td>';

                else $html .= '<td>' . html::input("$name", $selected, "class='form-control autocomplete='off'") . '</td>';
            }

            if(in_array($model, $this->config->transfer->actionModule)) $html .= '<td><a onclick="delItem(this)"><i class="icon-close"></i></a></td>';
            $html .= '</tr>' . "\n";
        }

        return $html;
    }

    /**
     * Process stepExpect、stepDesc and precondition for testcase.
     *
     * @param  int    $field
     * @param  int    $datas
     * @param  int    $key
     * @access public
     * @return void
     */
    public function process4Testcase($field, $datas, $key = 0)
    {
        $stepData = $this->testcase->processDatas($datas);

        $html = '';
        if($field == 'precondition' and isset($datas[$key])) return html::textarea("precondition[$key]", htmlSpecialString($datas[$key]->precondition), "class='form-control' style='overflow:hidden'");
        if($field == 'stepExpect') return $html;
        if(isset($stepData[$key]['desc']))
        {
            $html = "<table class='w-p100 bd-0'>";
            $hasStep = false;
            foreach($stepData[$key]['desc'] as $id => $desc)
            {
                if(empty($desc['content'])) continue;
                $hasStep = true;

                $html .= "<tr class='step'>";
                $html .= '<td>' . $id . html::hidden("stepType[$key][$id]", $desc['type']) . '</td>';
                $html .= '<td>' . html::textarea("desc[$key][$id]", htmlSpecialString($desc['content']), "class='form-control'") . '</td>';
                if($desc['type'] != 'group') $html .= '<td>' . html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]['content']) ? htmlSpecialString($stepData[$key]['expect'][$id]['content']) : '', "class='form-control'") . '</td>';
                $html .= "</tr>";
            }

            if(!$hasStep)
            {
                $html .= "<tr class='step'>";
                $html .= "<td>1" . html::hidden("stepType[$key][1]", 'step') . " </td>";
                $html .= "<td>" . html::textarea("desc[$key][1]", '', "class='form-control'") . "</td>";
                $html .= "<td>" . html::textarea("expect[$key][1]", '', "class='form-control'") . "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }
        else
        {
            $html  = "<table class='w-p100 bd-0'>";
            $html .= "<tr class='step'>";
            $html .= "<td>1" . html::hidden("stepType[$key][1]", 'step') . " </td>";
            $html .= "<td>" . html::textarea("desc[$key][1]", '', "class='form-control'") . "</td>";
            $html .= "<td>" . html::textarea("expect[$key][1]", '', "class='form-control'") . "</td>";
            $html .= "</tr>";
            $html .= "</table>";
        }

        return $html;
    }
}
