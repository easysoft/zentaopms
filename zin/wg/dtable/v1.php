<?php
class column
{
    public function __construct($name, $title)
    {
        $this->name  = $name;
        $this->title = $title;
    }

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

    public function flex($width)
    {
        $this->flex = $width;
        return $this;
    }

    public function fixed($position)
    {
        $this->fixed = $position;
        return $this;
    }

}

class dtable
{
    public $cols = array();

    public $search = '';

    public $config;

    public function __construct($text = '')
    {
        global $config;

        $this->config = $config;
        $this->text   = $text;
    }

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

    public function data($data)
    {
        $this->data = $data;
    }

    public function search($status, $module)
    {
        $this->search = '<div class="cell' .  ($status == 'bySearch' ? " show" : "")  . '" id="queryBox" data-module="' . $module . '"></div>';
        return $this;
    }

    public function toString()
    {
        $html = '';
        if(!empty($this->search)) $html .= $this->search;
        $html .= '<div class="dtable"></div>';
        $html .= '<script>new zui.DTable(".dtable", {plugins: ["nested", "rich"], cols: ' . json_encode($this->cols) . ', data: ' . json_encode($this->data) . '})</script>';

        return $html;
    }
}
