<?php
namespace zin;

class programmenu extends wg
{
    private $programs = array();

    protected static $defineProps = array('js-render?:bool=true, title?:string, subTitle?:string, programs?:array, activeClass?:string, activeIcon?:string, activeKey?:string');

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

    private function setMenuTreeProps()
    {
        $this->programs = $this->prop('programs');
        $this->setProp('programs', null);
        $this->setProp('items', $this->buildMenuTree(array(), 0));
        $this->setDefaultProps(array('activeClass' => 'active', 'activeIcon' => 'check'));
    }

    protected function build()
    {
        $this->setMenuTreeProps();
        $activeKey = $this->prop('activeKey');
        $closeBtn = null;
        if(!empty($activeKey))
        {
            $closeBtn = a
            (
                set('href', $this->prop('closeLink')),
                h::i
                (
                    setClass('icon icon-close')
                )
            );
        }
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
                ),
                $closeBtn
            ),
            h::main
            (
                zui::menutree(inherit($this))
            )
        );
    }
}
