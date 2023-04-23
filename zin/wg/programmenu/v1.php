<?php
namespace zin;

class programMenu extends wg
{
    private $programs = array();

    protected static $defineProps = array
    (
        'programs?:array',
        'activeClass?:string',
        'activeIcon?:string',
        'activeKey?:string',
        'closeLink?:string'
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenuTree($parent, $parentID)
    {
        $children = $this->getChildProgram($parentID);
        if(count($children) === 0) return array();

        foreach($children as $child)
        {
            $item = array('key' => $child->id, 'text' => $child->name, 'items' => array());
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            else                    unset($item['items']);
            $parent[] = $item;
        }
        return $parent;
    }

    private function getTitle($activeKey)
    {
        global $lang;

        if(empty($activeKey)) return $lang->program->all;

        foreach($this->programs as $program)
        {
            if($program->id == $activeKey) return $program->name;
        }
        return $lang->program->all;
    }

    private function getChildProgram($id)
    {
        return array_filter($this->programs, function($program) use($id) {return $program->parent == $id;});
    }

    private function setMenuTreeProps()
    {
        if(!empty($this->prop('programs'))) $this->programs = $this->prop('programs');
        $this->setProp('programs', null);
        $items = $this->buildMenuTree(array(), 0);
        array_unshift($items, array('type' => 'heading', 'text' => '筛选项目集'));
        $this->setProp('items', $items);
        $this->setProp('commonItemProps', array('item' => array('className' => 'not-hide-menu')));
        $this->setProp('isDropdownMenu', true);
        $this->setProp('_to', "[data-zin-id='$this->gid']");
        $this->setDefaultProps(array('activeClass' => 'active', 'activeIcon' => 'check'));
    }

    protected function build()
    {
        $this->setMenuTreeProps();

        $activeKey = $this->prop('activeKey');
        $title     = $this->getTitle($activeKey);
        $closeBtn  = null;

        if(!empty($activeKey))
        {
            $closeBtn = a
            (
                set('href', $this->prop('closeLink')),
                h::i
                (
                    setClass('icon icon-close'),
                    setStyle('color', '#313C52'),
                )
            );
        }

        return div
        (
            setClass('program-menu'),
            set('data-zin-id', $this->gid),
            h::header
            (
                set('data-toggle', 'dropdown'),
                div
                (
                    setClass('title-container'),
                    div
                    (
                        setClass('icon-container down'),
                        h::i(setClass('gg-chevron-down')),
                    ),
                    div
                    (
                        setClass('icon-container up'),
                        h::i(setClass('gg-chevron-up')),
                    ),
                    span($title)
                ),
                $closeBtn
            ),
            zui::menutree(inherit($this))
        );
    }
}
