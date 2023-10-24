<?php
/**
 * Get field list.
 *
 * @param  string $module
 * @param  string $method
 * @access public
 * @return array
 */
public function getFieldList($module, $method = '')
{
    if(!isset($this->config->$module)) $this->loadModel($module);

    $config = $this->config->$module;
    if(!empty($method) && isset($config->$method) && isset($config->$method->dtable)) $config = $config->$method;

    $fieldList = $config->dtable->fieldList;
    if($this->session->currentProductType === 'normal') unset($fieldList['branch']);
    foreach($fieldList as $field => $items)
    {
        if($field === 'branch')
        {
            if($this->session->currentProductType === 'branch')   $fieldList[$field]['title'] = $this->lang->dtable->branch;
            if($this->session->currentProductType === 'platform') $fieldList[$field]['title'] = $this->lang->dtable->platform;
            continue;
        }
        $title = zget($this->lang->$module, $items['title'], zget($this->lang, $items['title'], $items['title']));
        $fieldList[$field]['title'] = $title;
    }

    if($this->config->edition != 'open' and $module != 'story')
    {
        $fields = $this->loadModel('workflowfield')->getList($module);
        foreach($fields as $field)
        {
            if($field->buildin) continue;
            $fieldList[$field->field]['title']    = $field->name;
            $fieldList[$field->field]['width']    = '120';
            $fieldList[$field->field]['fixed']    = 'no';
            $fieldList[$field->field]['required'] = 'no';
        }
    }
    return $fieldList;
}
