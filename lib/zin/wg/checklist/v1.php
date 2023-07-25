<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class checkList extends wg
{
    protected static array $defineProps = array(
        'primary: bool=true',
        'type: string="checkbox"',
        'name?: string',
        'value?: string|array',
        'items?: array',
        'inline?: bool'
    );

    public function getValueList()
    {
        $value = $this->prop('value');
        if(is_null($value)) return array();

        if($this->prop('type') === 'checkbox') return is_array($value) ? $value : explode(',', $value);
        return [$value];
    }

    public function onBuildItem($item): checkbox
    {
        if($item instanceof item) $item = $item->props->toJsonData();

        if(!isset($item['checked']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['checked'] = in_array($value, $valueList);
        }

        $props = $this->props->pick(['primary', 'type', 'name']);
        return new checkbox(set($props), set($item));
    }

    protected function build(): wg
    {
        list($items, $inline) = $this->prop(['items', 'inline']);

        if(!empty($items))
        {
            $valueList = $this->getValueList();
            foreach($items as $key => $item)
            {
                if(!is_array($item))         $item = array('text' => $item, 'value' => $key);
                if(!isset($item['checked'])) $item['checked'] = in_array($item['value'], $valueList);
                $items[$key] = $this->onBuildItem($item);
            }
        }

        return div
        (
            setClass($inline ? 'check-list-inline' : 'check-list'),
            set($this->getRestProps()),
            $items,
            $this->children()
        );
    }
}
