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

}

class dtable
{
    public $cols = array();

    public function __construct($text = '')
    {
        $this->text = $text;
    }

    public function col($name, $title)
    {
        $col = new column($name, $title);
        $this->cols[] = $col;
        return $col;
    }

    public function data($data)
    {
        $this->data = $data;
    }

    public function toString()
    {
        $html  = '<div class="dtable"></div>';
        $html .= '<script>new zui.DTable(".dtable", {plugins: ["nested", "rich"], cols: ' . json_encode($this->cols) . ', data: ' . json_encode($this->data) . '})</script>';

        return $html;
    }
}
