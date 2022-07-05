<?php

class portModel extends model
{

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
        $this->app->loadLang($model);

        if(array_key_exists($field, $this->lang->$model))
        {
            $title = $this->lang->$model->$field;
        }
        elseif(array_key_exists($field . 'AB', $this->lang->$model))
        {
            $title = $this->lang->$model->$field . 'AB';
        }
        elseif(array_key_exists($field, $this->lang->port->reservedWord))
        {
            $title = $this->lang->port->reservedWord[$field];
        }

        return $title;
    }

    public function initWidth($model, $field)
    {
        return '120px';

    }

    public function initControl($model, $field)
    {
        return 'select';
    }

    public function initValues($model, $field)
    {
        return 'value';
    }

    public function initSort($model, $field)
    {
        return 'no';
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
        if(empty($this->config->$model->create->requiredFields)) return 'no';

        $requiredFields = "," . $this->config->$model->create->requiredFields . ",";

        if(strpos($requiredFields, $field) !== false) return 'yes';
        return 'no';
    }

    public function initFixed($model, $field)
    {
        return 'left';
    }

    public function initClass($model, $field)
    {
        return '';
    }
}

