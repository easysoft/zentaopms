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
class column extends wg
{
    /**
     * Construct function, init table data.
     *
     * @param  string $name
     * @param  string $title
     * @access public
     * @return void
     */
    public function __construct($name, $title)
    {
        parent::__construct();
        $this->name  = $name;
        $this->title = $title;
    }

    /**
     * Set column width.
     *
     * @param  int    $width
     * @access public
     * @return object
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Set the column output type.
     *
     * @param  string $type link|avatar|circleProgress|html
     * @access public
     * @return object
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set flex attribute of column.
     *
     * @param  int   $growValue
     * @access public
     * @return object
     */
    public function flex($growValue)
    {
        $this->flex = (int)$growValue;
        return $this;
    }

    /**
     * Set fixed attribute of column.
     *
     * @param  string $position left|right
     * @access public
     * @return object
     */
    public function fixed($position)
    {
        $this->fixed = $position;
        return $this;
    }

    /**
     * Set sortType.
     *
     * @param  string|bool $type up|down|true|false
     * @access public
     * @return object
     */
    public function sortType($type)
    {
        $this->sortType = $type;
        return $this;
    }

    /**
     * Set checkbox.
     *
     * @param  bool   $value
     * @access public
     * @return object
     */
    public function checkbox($value)
    {
        $this->checkbox = $value;
        return $this;
    }

    /**
     * Set nested Toggle.
     *
     * @param  bool   $value
     * @access public
     * @return object
     */
    public function nestedToggle($value)
    {
        $this->nestedToggle = $value;
        return $this;
    }

    /**
     * Set column icon.
     *
     * @param  string $icon
     * @access public
     * @return object
     */
    public function iconRender($icon)
    {
        $this->iconRender = $icon;
        return $this;
    }

    /**
     * Set status list.
     *
     * @param  array $statusList
     * @access public
     * @return object
     */
    public function statusMap($statusList)
    {
        $this->statusMap = $statusList;
        return $this;
    }
}

class dtable
{
    /**
     * Columns.
     *
     * @var    array
     * @access public
     */
    public $cols = array();

    /**
     * Search.
     *
     * @var    string
     * @access public
     */
    public $search = '';

    /**
     * Footer.
     *
     * @var    string
     * @access public
     */
    public $footer = '';

    /**
     * Form.
     *
     * @var string
     * @access public
     */
    public $form = '';

    /**
     * System config.
     *
     * @var    object
     * @access public
     */
    public $config;

    /**
     * Construct function, init dtable data.
     *
     * @param  string $text
     * @access public
     * @return void
     */
    public function __construct($text = '')
    {
        global $config;

        $this->config = $config;
        $this->text   = $text;
    }

    /**
     * Get column object.
     *
     * @param  string $name
     * @param  string $title
     * @access public
     * @return object
     */
    public function col($name, $title)
    {
        $col = new column($name, $title);
        $this->cols[] = $col;
        return $col;
    }

    /**
     * Build columns of table.
     *
     * @param  array  $fieldList
     * @access public
     * @return void
     */
    public function buildCols($fieldList)
    {
        foreach($fieldList as $field)
        {
            $col = $this->col($field['name'], $field['title']);
            foreach($field as $attr => $value)
            {
                if(in_array($attr, $this->config->dtable->colVars)) $col->$attr($value);
            }
        }
    }

    /**
     * Set table data.
     *
     * @param  array  $data
     * @access public
     * @return void
     */
    public function data($data)
    {
        $this->data = $data;
    }

    /**
     * Set search html.
     *
     * @param  string $status
     * @param  string $module
     * @access public
     * @return object
     */
    public function search($status, $module)
    {
        $this->search = '<div class="cell' .  ($status == 'bySearch' ? " show" : "")  . '" id="queryBox" data-module="' . $module . '"></div>';
        return $this;
    }

    /**
     * Set the table footer.
     *
     * @param  object $pager
     * @param  string $class
     * @param  string $summary
     * @param  string $summaryID
     * @access public
     * @return void
     */
    public function footer($pager = null, $class = '', $summary = '', $summaryID = '')
    {
        $footerHtml  = "<div class='table-footer $class'>";
        if($summary) $footerHtml .= "<div id='$summaryID' class='table-statistic'>$summary</div>";
        if($pager)
        {
            ob_start();
            $pager->show('right', 'pagerjs');
            $footerHtml .= ob_get_clean();
        }
        $footerHtml .= '</div>';

        $this->footer = $footerHtml;
    }

    /**
     * Set form html.
     *
     * @param  string $formID
     * @param  string $class
     * @param  string $attr
     * @access public
     * @return void
     */
    public function form($formID = '', $class = '', $attr = '')
    {
        $this->form = "<form class='$class' id='$formID' method='post' $attr>";
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
        if(!empty($this->search)) $html .= $this->search;

        $html .= '<div class="dtable"></div>';
        $html .= '<script>dtableWithZentao = new zui.DTable(".dtable", {cols: ' . json_encode($this->cols) . ', data: ' . json_encode($this->data) . ', nested: true, checkable: true})</script>';

        return $html;
    }
}
