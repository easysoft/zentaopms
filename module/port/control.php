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
        $params['executionID'] = 1;
        $params['orderBy'] = 'id_desc';

        extract($params);

        /* save params to session. */
        $this->session->set(($model.'ExportParams'), $params);

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
        $exportDatas['kind'] = $model;

        $exportFields= isset($this->config->$model->exportFields) ? $this->config->$model->exportFields : '';
        $exportFields = explode(',', $exportFields);
        $exportDatas['exportFields'] = array_keys($exportDatas['fields']);
        $exportDatas['fileType']     = 'xlsx';
        $exportDatas['fileName']     = '企业网站第一期-所有任务';

        $exportDatas['typeList'] = join(',', $exportDatas['typeList']);
        $exportDatas['priList']  = join(',', $exportDatas['priList']);
        $_POST = $exportDatas;
        //a($exportDatas);die;
        $this->fetch('file', 'export2' . $_POST['fileType'], $POST);
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
                $exportDatas['listStyle'][] = $key;
                $exportDatas[$key.'List']   = $field['values'];
                $foreignKeyList[] = $key;
            }
        }

        foreach ($modelDatas as $id => $values)
        {
            foreach($values as $field => $value)
            {
                if(in_array($field, $foreignKeyList))
                {
                    $modelDatas[$id]->$field = zget($exportDatas[$field.'List'], $value);
                }
            }
        }
        $exportDatas['rows'] = array_values($modelDatas);
        return $exportDatas;
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
