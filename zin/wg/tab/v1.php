<?php
class tab extends wg
{
    private $text;

    private $isActive;

    public function __construct($text)
    {
        parent::__construct();
        $this->text = $text;
    }

    public function active($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    public function toString()
    {
        $active = $this->isActive ? 'btn-active-text' : '';
        $label  = "<span class='text'>{$this->text}</span>";
        return html::a($this->link, $label, '', "class='btn btn-link $active'");
    }
}
