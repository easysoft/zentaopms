<?php 
declare(strict_types=1);
class searchTao extends searchModel
{
    protected function processBuildinFields(string $module, array $searchConfig): array
    {
        $flowModule = $module;
        if($module == 'projectStory' || $module == 'executionStory') $flowModule = 'story';
        if($module == 'projectBuild' || $module == 'executionBuild') $flowModule = 'build';
        if($module == 'projectBug') $flowModule = 'bug';

        $buildin = false;
        $this->app->loadLang('workflow');
        $this->app->loadConfig('workflow');
        if(!empty($this->config->workflow->buildin))
        {
            foreach($this->config->workflow->buildin->modules as $appModules)
            {
                if(isset($appModules->$flowModule))
                {
                    $buildin = true;
                    break;
                }
            }
        }

        if(!$buildin) return $searchConfig;

        $fields   = $this->loadModel('workflowfield')->getList($flowModule, 'searchOrder, `order`, id');
        $maxCount = $this->config->maxCount;
        $this->config->maxCount = 0;

        $fieldValues = array();
        $formName    = $module . 'Form';
        if($this->session->$formName)
        {
            foreach($this->session->$formName as $formField)
            {
                $field = zget($formField, 'field', '');
                $value = zget($formField, 'value', '');

                if(empty($field)) continue;
                if($value) $fieldValues[$formField->field][$value] = $value;
            }
        }

        foreach($fields as $field)
        {
            if($field->canSearch == 0 || $field->buildin) continue;

            if(in_array($field->control, $this->config->workflowfield->optionControls))
            {
                $field->options = $this->workflowfield->getFieldOptions($field, true, zget($fieldValues, $field->field, ''), '', $this->config->flowLimit);
            }

            $searchConfig['fields'][$field->field] = $field->name;
            $searchConfig['params'][$field->field] = $this->loadModel('flow', 'sys')->processSearchParams($field->control, $field->options);
        }
        $this->config->maxCount = $maxCount;

        return $searchConfig;
    }
}

