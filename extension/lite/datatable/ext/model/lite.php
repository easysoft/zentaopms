<?php
/**
 * Get field list.
 *
 * @param  string $module
 * @param  string $method
 * @access public
 * @return array
 */
public function getFieldList(string $module, string $method = ''): array
{
    /* Load corresponding module. */
    if(!isset($this->config->$module)) $this->loadModel($module);

    $config = $this->config->$module;
    if(!empty($method) && isset($config->$method) && isset($config->$method->dtable)) $config = $config->$method;

    $fieldList = $config->dtable->fieldList;

    /* If doesn't need product, remove 'product' field. */
    if($this->session->hasProduct == 0 && (strpos($this->config->datatable->noProductModule, ",$module,") !== false))
    {
        $productIndex = array_search('product', $config->dtable->defaultField);
        if($productIndex) unset($config->dtable->defaultField[$productIndex]);
        if(isset($fieldList['product'])) unset($fieldList['product']);
    }

    /* Nomal product without 'branch' field. */
    if($this->session->currentProductType === 'normal') unset($config->fieldList['branch']);

    foreach($fieldList as $fieldName => $items)
    {
        /* Translate field title. */
        if(!isset($items['title'])) $items['title'] = $fieldName;
        $title = zget($this->lang->$module, $items['title'], zget($this->lang, $items['title'], $items['title']));
        $fieldList[$fieldName]['title'] = $title;

        /* Set col config default value. */
        if(!empty($items['type']) && isset($this->config->datatable->defaultColConfig[$items['type']]))
        {
            $fieldList[$fieldName] = array_merge($this->config->datatable->defaultColConfig[$items['type']], $fieldList[$fieldName]);
        }
    }

    /* Logic except open source version .*/
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
