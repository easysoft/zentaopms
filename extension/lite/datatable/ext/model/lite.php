<?php
/**
 * Get field list.
 *
 * @param  string $module
 * @access public
 * @return array
 */
public function getFieldList($module)
{
    if(!isset($this->config->$module)) $this->loadModel($module);
    if($this->session->currentProductType === 'normal') unset($this->config->$module->datatable->fieldList['branch']);
    foreach($this->config->$module->datatable->fieldList as $field => $items)
    {
        if(zget($items, 'display', true) === false)
        {
            unset($this->config->$module->datatable->fieldList[$field]);
            continue;
        }

        if($field === 'branch')
        {
            if($this->session->currentProductType === 'branch')   $this->config->$module->datatable->fieldList[$field]['title'] = $this->lang->datatable->branch;
            if($this->session->currentProductType === 'platform') $this->config->$module->datatable->fieldList[$field]['title'] = $this->lang->datatable->platform;
            continue;
        }
        $title = zget($this->lang->$module, $items['title'], zget($this->lang, $items['title'], $items['title']));
        $this->config->$module->datatable->fieldList[$field]['title'] = $title;
    }

    if($this->config->edition != 'open' and $module != 'story')
    {
        $fields = $this->loadModel('workflowfield')->getList($module);
        foreach($fields as $field)
        {
            if($field->buildin) continue;
            $this->config->$module->datatable->fieldList[$field->field]['title']    = $field->name;
            $this->config->$module->datatable->fieldList[$field->field]['width']    = '120';
            $this->config->$module->datatable->fieldList[$field->field]['fixed']    = 'no';
            $this->config->$module->datatable->fieldList[$field->field]['required'] = 'no';
        }
    }
    return $this->config->$module->datatable->fieldList;
}
