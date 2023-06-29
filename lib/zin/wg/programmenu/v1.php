<?php
namespace zin;

class programMenu extends wg
{
    private $programs = array();

    protected static $defineProps = array(
        'programs?: array',
        'activeClass?: string="active"',
        'activeIcon?: string="check"',
        'activeKey?: string',
        'closeLink?: string',
        'onClickItem?: string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildMenuTree($parent, $parentID)
    {
        $children = $this->getChildProgram($parentID);
        if(count($children) === 0) return array();

        foreach($children as $child)
        {
            $item = array('key' => $child->id, 'text' => $child->name, 'items' => array());
            if(isset($child->icon)) $item['icon'] = $child->icon;

            $items = $this->buildMenuTree($item['items'], $child->id);

            if(count($items) !== 0)
            {
                $item['items'] = $items;
            }
            else
            {
                unset($item['items']);
            }

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

    private function getTreeProps()
    {
        $items = $this->buildMenuTree(array(), 0);
        array_unshift($items, array('type' => 'heading', 'text' => '筛选项目集'));
        $props = array('items' => $items);

        /* Attach click event function. */
        $onClickItem = $this->prop('onClickItem');
        if($onClickItem) $props['onClickItem'] = $onClickItem;

        return $props;
    }

    private function closeBtn()
    {
        $activeKey = $this->prop('activeKey');
        if(empty($activeKey)) return null;

        return a
        (
            set('href', $this->prop('closeLink')),
            h::i
            (
                setClass('icon icon-close p-3 cursor-pointer'),
                setStyle('color', '#313C52'),
            )
        );
    }

    protected function build()
    {
        $this->programs = (array)$this->prop('programs');
        $activeKey      = $this->prop('activeKey');

        return div
        (
            setClass('program-menu col shrink-0'),
            set('data-show', '0'),
            popovers
            (
                set::placement('bottom-start'),
                to::trigger
                (
                    button
                    (
                        setClass('h-10 border border-primary flex justify-between items-center cursor-pointer pl-3 rounded'),
                        on::click('toggleIcon'),
                        div
                        (
                            setClass('flex gap-x-2'),
                            div
                            (
                                setClass('icon-container down'),
                                icon('angle-down'),
                            ),
                            div
                            (
                                setClass('icon-container up'),
                                icon('angle-top', setClass('text-white')),
                            ),
                            span
                            (
                                setClass('font-bold leading-5'),
                                $this->getTitle($activeKey),
                            )
                        ),
                        $this->closeBtn(),
                    )
                ),
                to::target(zui::tree(set($this->getTreeProps()))),
            )
        );
    }
}
