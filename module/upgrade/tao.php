<?php
declare(strict_types=1);
class upgradeTao extends upgradeModel
{
    /**
     * 获取中间表分组ID。
     * Get dataview group ID.
     *
     * @param  string $name
     * @access protected
     * @return int
     */
    protected function getDataviewGroupID(string $name): int
    {
        $groupID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('dataview')->andWhere('name')->eq($name)->fetch('id');
        if(empty($groupID))
        {
            $group = new stdclass();
            $group->root   = 0;
            $group->name   = $name;
            $group->parent = 0;
            $group->grade  = 1;
            $group->order  = 10;
            $group->type   = 'dataview';

            $this->dao->insert(TABLE_MODULE)->data($group)->exec();
            $groupID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_MODULE)->set("`path` = CONCAT(',', `id`, ',')")->where('id')->eq($groupID)->exec();
        }
        return $groupID;
    }

    /**
     * 将内置透视表转化为中间表。
     * Convert built-in dataset to dataview.
     *
     * @access protected
     * @return void
     */
    protected function ConvertBuiltInDataSet(): void
    {
        $this->loadModel('dataset');
        $this->loadModel('dataview');

        $dataview = new stdclass();
        $dataview->group       = $this->getDataviewGroupID($this->lang->dataview->builtIn);
        $dataview->createdBy   = 'system';
        $dataview->createdDate = helper::now();

        foreach($this->lang->dataset->tables as $code => $dataset)
        {
            $sameCodeList = $this->dao->select('*')->from(TABLE_DATAVIEW)->where('code')->eq($code)->fetchAll();
            if(!empty($sameCodeList)) continue;

            $dataview->name = $dataset['name'];
            $dataview->code = $code;
            $dataview->view = 'ztv_' . $code;

            $table = $this->dataset->getTableInfo($code);
            $dataview->sql = $this->dataset->getTableData($table->schema, 'id_desc', 100, true);

            $fields = array();
            foreach($table->schema->fields as $key => $field)
            {
                $relatedField = '';
                if($field['type'] == 'object' and isset($field['show']))
                {
                    $key = str_replace('.', '_', $field['show']);
                    $relatedField  = substr($field['show'], strpos($field['show'], '.') + 1);
                    $relatedObject = isset($field['object']) ? $field['object'] : '';
                    if(!empty($relatedObject) and isset($table->schema->objects[$relatedObject]))
                    {
                        foreach($table->schema->objects[$relatedObject] as $fieldID => $fieldName)
                        {
                            if($fieldID == $relatedField) continue;

                            $addField = "{$relatedObject}_{$fieldID}";
                            $fields[$addField] = array();
                            $fields[$addField]['name']   = isset($fieldName['name']) ? $fieldName['name'] : $addField;
                            $fields[$addField]['field']  = $fieldID;
                            $fields[$addField]['object'] = $relatedObject;
                            $fields[$addField]['type']   = 'object';
                        }
                    }
                }

                if(!isset($fields[$key])) $fields[$key] = array();
                $fields[$key]['name']   = $field['name'];
                $fields[$key]['field']  = empty($relatedField) ? $key : $relatedField;
                $fields[$key]['object'] = isset($field['object']) ? $field['object'] : $code;
                $fields[$key]['type']   = $field['type'];
            }
            $dataview->fields = json_encode($fields);

            $this->dao->insert(TABLE_DATAVIEW)->data($dataview)->exec();
            $dataviewID = $this->dao->lastInsertID();
            if(!empty($dataview->view) and !empty($dataview->sql)) $this->dataview->createViewInDB($dataviewID, $dataview->view, $dataview->sql);
        }
    }

    /**
     * 将自定义透视表转化为中间表。
     * Convert custom dataset to dataview.
     *
     * @param  array $customDataset
     * @access protected
     * @return void
     */
    protected function ConvertCustomDataSet(array $customDataset): void
    {
        $this->loadModel('dataview');

        $dataview = new stdclass();
        $dataview->group = $this->getDataviewGroupID($this->lang->dataview->default);

        foreach($customDataset as $datasetID => $dataset)
        {
            $dataview->name        = $dataset->name;
            $dataview->code        = 'custom_' . $datasetID;
            $dataview->view        = 'ztv_custom_' . $datasetID;
            $dataview->sql         = $dataset->sql;
            $dataview->fields      = $dataset->fields;
            $dataview->createdBy   = $dataset->createdBy;
            $dataview->createdDate = $dataset->createdDate;
            $dataview->deleted     = $dataset->deleted;

            $this->dao->insert(TABLE_DATAVIEW)->data($dataview)->exec();
            $dataviewID = $this->dao->lastInsertID();
            if(!empty($dataview->view) and !empty($dataview->sql)) $this->dataview->createViewInDB($dataviewID, $dataview->view, $dataview->sql);
        }
    }
}
