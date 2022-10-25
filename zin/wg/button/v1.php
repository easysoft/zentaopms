<?php
class button extends wg
{
    public $link = '';
    public $target = '';
    public $misc = '';
    public $newline = true;

    public function __construct($text)
    {
        parent::__construct();
        $this->text = $text;
    }

    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    public function target($target = '')
    {
        $this->target = $target;
        return $this;
    }

    public function misc($misc)
    {
        $this->misc = $misc;
        return $this;
    }

    public function newline($newline = true)
    {
        $this->newline = $newline;
        return $this;
    }

    public function toString()
    {
        return html::a($this->link, $this->text, $this->target, $this->misc . $this->toHx(), $this->newline);
    }
}
