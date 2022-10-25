<?php
class actionbar extends wg
{
    private $children = array();

    public function __construct($text = '')
    {
        parent::__construct();
    }

    public function append($item)
    {
        $this->children[] = $item;
    }

    public function toString()
    {
        $html = '<div class="pull-right">';
        foreach($this->children as $child)
        {
            if(is_string($child))
            {
                $html .= $child;
                continue;
            }
            $html .= $child->toString();
        }
        return $html . '</div>';
    }
}
