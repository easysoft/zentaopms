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
            /* Init config fieldList */
            $fieldList = $this->port->initFieldList($model);

            $queryCondition = $this->session->{$model . 'QueryCondition'};
            $onlyCondition  = $this->session->{$model . 'OnlyCondition'};

            $modelDatas = array();
            if($onlyCondition and $queryCondition)
            {
                $table = zget($this->config->objectTables, $model);;
                if(isset($this->config->$model->port->table)) $table = $this->config->$model->port->table;
                $modelDatas = $this->dao->select('*')->from($table)->where($queryCondition)->fetchAll('id');
            }
            elseif($queryCondition)
            {
                $stmt = $this->dbh->query($queryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
                while($row = $stmt->fetch()) $modelDatas[$row->id] = $row;
            }

            $exportDatas = $this->getExportDatas($fieldList, $modelDatas);

            $this->post->set('rows', $exportDatas['rows']);
            $this->post->set('fields', $exportDatas['fields']);
            $this->post->set('kind', $model);
            $this->fetch('file', 'export2' . $_POST['fileType'], $POST);
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
                elseif(strpos($this->config->port->userField, $field) !== false)
                {
                    $modelDatas[$id]->$field = zget($exportDatas['user'], $value);
                }
            }
        }

        $exportDatas['rows'] = array_values($modelDatas);
        return $exportDatas;
    }

    public function setListValue($model)
    {
        $this->post->set('listStyle', $this->config->$model->export->listFields);
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
