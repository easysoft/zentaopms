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
     * Form.
     *
     * @var string
     * @access public
     */
    public $form = '';

    /**
     * Construct function, init dtable data.
     *
     * @param  string $text
     * @access public
     * @return void
     */
    public function __construct($text = '')
    {
        global $config, $lang;

        $this->config = $config;
        $this->lang   = $lang;
        $this->text   = $text;
    }

    /**
     * Get row object.
     *
     * @param  object $field
     * @access public
     * @return object
     */
    public function row($field)
    {
        $row  = '<tr>';
        $row .= '<th>' . $field['title'] . '</th>';
        $row .= '<td>';
        if($field['control'] == 'input')
        {
            $row .= html::input($field['name'], '', "class='form-control'");
        }
        elseif($field['control'] == 'select')
        {
            $row .= html::select($field['name'], $field['values'], '', "class='form-control'");
        }
        elseif($field['control'] == 'textarea')
        {
            $row .= html::textarea($field['name'], '', "class='form-control'");
        }
        elseif($field['control'] == 'radio')
        {
            $row .= html::radio($field['name'], $field['values']);
        }
        elseif($field['control'] == 'multi-select')
        {
            //$row .= html::select($field['name'], $field['values'], '', "class='form-control chosen' multiple");
        }
        $row .= '</td>';
        $row .= '</tr>';
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
        foreach($fieldList as $field) $this->row($field);
    }

    /**
     * Get datatable.
     *
     * @access public
     * @return string
     */
    public function toString()
    {
        $html = '';

        $html .= '<form class="form-ajax main-form">';
        $html .= '<table class="table table-form">';
        foreach($this->rows as $row)
        {
            $html .= $row;
        }
        $html .= '</table>';
        $html .= '</form>';

        return $html;
    }
}
