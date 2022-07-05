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
        $fieldList = $this->initFieldList($model);
    }

    /**
     * Init FieldList .
     *
     * @param  int    $model
     * @access public
     * @return void
     */
    public function initFieldList($model)
    {
        $portFieldList = $this->config->port->fieldList;

        $this->loadModel($model);
        $fields = isset($this->config->$model->exportFields) ? $this->config->$model->exportFields : '';

        if(empty($fields)) return false;

        $fields = explode(',', $fields);

        /* build module fieldList. */
        foreach ($fields as $field)
        {
            $field = trim($field);

            $modelFieldList = isset($this->config->$model->fieldList[$field]) ? $this->config->$model->fieldList[$field] : array();

            foreach ($portFieldList as $portField => $value)
            {
                $funcName = 'init' . ucfirst($portField);
                if(!array_key_exists($portField, $modelFieldList)) $modelFieldList[$portField] = $this->port->$funcName($model, $field);
            }

            $this->config->$model->fieldList[$field] = $modelFieldList;
        }

        a($this->config->$model->fieldList);
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
