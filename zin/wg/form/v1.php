<?php
/**
 * The v1 file of dtable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Jinyong Zhu <zhujinyong@easycorp.ltd>
 * @package     dtable
 * @version     $Id
 * @link        https://www.zentao.net
 */
class form 
{
    /**
     * Rows.
     *
     * @var    array
     * @access public
     */
    public $rows = array();

    /**
     * Form action btn.
     *
     * @var string
     * @access public
     */
    public $formActions = '';

    /**
     * Construct function, init dtable data.
     *
     * @param  string $text
     * @access public
     * @return void
     */
    public function __construct($text = '')
    {
        global $config, $lang, $app;

        $this->config  = $config;
        $this->lang    = $lang;
        $this->text    = $text;
        $this->control = $app->control;
    }

    /**
     * Append dom to rows.
     *
     * @param  array $field
     * @access public
     * @return object
     */
    public function append($field = array(), $element = '')
    {
        if($field)
        {
            $row  = '<div class="form-group">';
            $row .= '<label class="form-label">' . $field['title'] . '</label>';

            if($field['control'] == 'input')
            {
                $row .= html::input($field['name'], isset($field['default']) ? $field['default'] : '', "class='form-control'");
            }
            elseif($field['control'] == 'select')
            {
                $row .= html::select($field['name'], $field['options'], isset($field['default']) ? $field['default'] : '', "class='form-control'");
            }
            elseif($field['control'] == 'textarea')
            {
                $row .= html::textarea($field['name'], isset($field['default']) ? $field['default'] : '', "class='form-control'");
            }
            elseif($field['control'] == 'radio')
            {
                $row .= html::radio($field['name'], $field['options'], isset($field['default']) ? $field['default'] : '');
            }
            elseif($field['control'] == 'multi-select')
            {
                //$row .= html::select($field['name'], $field['options'], isset($field['default']) ? $field['default'] : '', "class='form-control chosen' multiple");
            }

            $row .= '</div>';
        }
        else
        {
            $row = $element;
        }

        $this->rows[] = $row;
    }

    /**
     * Build rows of table.
     *
     * @param  array  $fieldList
     * @access public
     * @return void
     */
    public function buildForm($fieldList)
    {
        foreach($fieldList as $field) $this->append($field);
    }

    /**
     * Build form actions.
     *
     * @param  string $actions
     * @access public
     * @return void
     */
    public function buildFormAction($actions = '')
    {
        if(!$actions)
        {
            $actions .= html::submitButton(); 
            $actions .= html::backButton(); 
        }

        $this->append('', $actions);
    }

    /**
     * Form to string.
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        $html  = '';
        $html .= "<form class='form'>";

        foreach($this->rows as $row) $html .= $row;
        //$html .= $this->control->printExtendFields('', 'table');
        $html .= '</form>';

        return $html;
    }
}
