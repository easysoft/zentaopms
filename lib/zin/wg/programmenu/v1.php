<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'dropmenu' . DS . 'v1.php';

class programMenu extends wg
{
    private $programs = array();

    protected static array $defineProps = array(
        'programs?: array',
        'activeClass?: string="active"',
        'activeIcon?: string="check"',
        'activeKey?: string',
        'link?: string',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenuTree($parent, $parentID)
    {
        $children = $this->getChildProgram($parentID);
        if(count($children) === 0) return array();

        foreach($children as $child)
        {
            $item = array('id' => $child->id, 'icon' => 'icon-cards-view', 'text' => $child->name, 'items' => array());
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

    protected function build(): zui
    {
        $this->programs = (array)$this->prop('programs');
        $activeKey      = $this->prop('activeKey');
        $link           = $this->prop('link');
        $closeLink      = str_replace('{id}', '0', $link);
        return zui::dropmenu
        (
            set('_id', 'programMenu'),
            set::_className('program-menu btn'),
            set::defaultValue($activeKey),
            set::text($this->getTitle($activeKey)),
            set::caret(true),
            set::popWidth(200),
            set::popClass('popup text-md'),
            set::onClick(jsRaw("(event) => {if(!event.target.closest('.is-caret')) return; openUrl('$closeLink'); return false}")),
            set::data(array('search' => false, 'checkIcon' => false, 'title' => data('lang.product.selectProgram'), 'link' => $link, 'data' => $this->buildMenuTree(array(), 0))),
        );
    }
}
