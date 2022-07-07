<?php
class port extends control
{
    /**
     * Export datas.
     *
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
        $this->session->set(($model.'ExportParams'), $params);
        if($_POST)
        {
            $this->config->port->sysDataList = $this->port->initSysDataFields();

            $fields = $this->post->exportFields;

            /* Init config fieldList */
            $fieldList = $this->port->initFieldList($model, $fields);

            $rows = $this->getRows($model, $fieldList);

            $list = $this->setListValue($model, $fieldList);
            if($list) foreach($list as $listName => $listValue) $this->post->set($listName, $listValue);

            /* Get export rows and fields datas */
            $exportDatas = $this->getExportDatas($fieldList, $rows);

            $this->post->set('rows',   $exportDatas['rows']);
            $this->post->set('fields', $exportDatas['fields']);
            $this->post->set('kind',   $model);
            $this->fetch('file', 'export2' . $_POST['fileType'], $_POST);
        }
    }

    /**
     * Get ExportDatas.
     *
     * @param  int    $fieldList
     * @param  int    $modelDatas
     * @access public
     * @return void
     */
    public function getExportDatas($fieldList, $modelDatas)
    {
        $exportDatas = array();
        $foreignKeyList = array();
        foreach ($fieldList as $key => $field)
        {
            $exportDatas['fields'][$key] = $field['title'];
            if($field['foreignKey'])
            {
                $exportDatas[$key]   = $field['values'];
                $foreignKeyList[] = $key;
            }
        }

        $exportDatas['user'] = $this->loadModel('user')->getPairs('devfirst|noclosed|nodeleted');

        foreach ($modelDatas as $id => $values)
        {
            foreach($values as $field => $value)
            {
                if(in_array($field, $foreignKeyList))
                {
                    $modelDatas[$id]->$field = zget($exportDatas[$field], $value);
                }
                elseif(strpos($this->config->port->userFields, $field) !== false)
                {
                    $modelDatas[$id]->$field = zget($exportDatas['user'], $value);
                }
            }
        }

        $exportDatas['rows'] = array_values($modelDatas);
        return $exportDatas;
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
    public function getRows($model, $fieldList)
    {
        $queryCondition = $this->session->{$model . 'QueryCondition'};
        $onlyCondition  = $this->session->{$model . 'OnlyCondition'};

        $modelDatas = array();
        if($onlyCondition and $queryCondition)
        {
            $table = zget($this->config->objectTables, $model);;
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
            $modelDatas = $this->port->getFiles($model, $modelDatas);
        }
        return $modelDatas;
    }

    /**
     * Export Template.
     *
     * @access public
     * @return void
     */
    public function exportTemplate()
    {

    }

    /**
     * Import.
     *
     * @access public
     * @return void
     */
    public function import()
    {

    }

    /**
     * Show Import datas .
     *
     * @access public
     * @return void
     */
    public function showImport()
    {

    }
}
