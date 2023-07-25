<?php
declare(strict_types=1);
namespace zin;

class checkboxGroup extends wg
{
    protected static array $defineProps = array(
        'title: array',
        'items: array'
    );

    private static array $checkboxProps = array(
        'checked' => false,
        'disabled' => false,
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildTitle(): wg
    {
        $title = array_merge(self::$checkboxProps, $this->prop('title'));
        return checkbox(set($title), setClass('checkbox-title'));
    }

    private function buildCheckboxList(): wg
    {
        $items = $this->prop('items');
        $title = array_merge(self::$checkboxProps, $this->prop('title'));
        $list = ul(setClass('flex', 'flex-wrap', 'ml-1.5', 'checkbox-list', 'pl-3'));
        foreach($items as $item)
        {
            $item = array_merge(self::$checkboxProps, $item);
            if($title['checked'] === true) $item['checked'] = true;
            if($title['disabled'] === true) $item['disabled'] = true;
            $list->add
            (
                li
                (
                    setClass('basis-1/2'),
                    checkbox(set($item), setClass('checkbox-child'))
                )
            );
        }
        return $list;
    }

    public function build(): wg
    {
        return div
        (
            set('data-on', 'click'),
            set('data-call', 'window.handleCheckboxGroupClick'),
            set('data-params', 'event'),
            setClass('checkbox-group'),
            $this->buildTitle(),
            $this->buildCheckboxList(),
        );
    }
}
