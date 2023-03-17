<?php
namespace zin;

class programmenu extends wg
{
    private $programs = array();

    protected static $defineProps = array('js-render?:bool=true');

    private function buildMenuTree($parent, $parentID)
    {
        $children = $this->getChildProgram($parentID);
        if(count($children) === 0) return;

        foreach($children as $child) {
            $item = array('key' => $child->id, 'text' => $child->name);
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            $parent[] = $item;
        }
        return $parent;
    }

    private function getChildProgram($id)
    {
        return array_filter($this->programs, function($program) use($id) {return $program->parent == $id;});
    }

    private function getContainer()
    {
        return div
        (
            setClass('program-menu'),
            h::header
            (
                div
                (
                    setClass('title-container'),
                    div
                    (
                        setClass('icon-container down'),
                        h::i(setClass('gg-chevron-down'))
                    ),
                    span($this->prop('title'))
                )
            )
        );
    }

    protected function build()
    {
        $this->programs = $this->prop('programs');
        $this->setProp('programs', null);
        $programItems = $this->buildMenuTree(array(), 0);
        $this->setProp('items', $programItems);
        $this->setProp('activeClass', 'active');
        $this->setProp('activeIcon', 'check');
        return zui::programmenu(inherit($this));
    }
}
