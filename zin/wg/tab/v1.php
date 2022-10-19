<?php
class tab
{
    private $text;

    private $isActive;

    public function __construct($text)
    {
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
        return html::a($this->link, $this->text, '', "class='btn btn-link $active'");
    }
}
