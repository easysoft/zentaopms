<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'dropmenu' . DS . 'v1.php';

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
        return zui::dropmenu
        (
            set('_id', 'programMenu'),
            set::className('program-menu btn'),
            set::defaultValue($activeKey),
            set::text($this->getTitle($activeKey)),
            set::caret(true),
            set::popClass('popup text-md'),
            set::data(array('search' => false, 'checkIcon' => true, 'title' => data('lang.product.selectProgram'), 'data' => $this->buildMenuTree(array(), 0), 'link' => '#')),
        );
    }
}
