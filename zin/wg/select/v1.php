<?php
namespace zin;

class select extends wg
{
    static $defineProps =
    [
        'name: string',
        'id?: string',
        'class?: string="form-control"',
        'value?: string',
        'required?: bool',
        'disabled?: bool',
        'multiple?: bool',
        'items?: array',
        'options?: array',
        'size?: number',
    ];

    public function onBuildItem($item)
    {
        if($item instanceof item) $item = $item->props->toJsonData();

        $text  = isset($item['text']) ? $item['text'] : '';
        unset($item['text']);

        if(!isset($item['selected']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['selected'] = in_array($value, $valueList);
        }

        return h::option(set($item), $text);
    }

    public function isMultiple()
    {
        $multiple = $this->prop('multiple');
        if($multiple === NULL)
        {
            $name = $this->prop('name');
            $multiple = str_contains($name, '[');
        }
        return $multiple;
    }

    public function getValueList()
    {
        $value = $this->prop('value');
        if($this->isMultiple()) return is_array($value) ? $value : explode(',', $value);
        return [$value];
    }

    protected function build()
    {
        list($items, $options) = $this->prop(['items', 'options']);
        if(is_array($options)) $items = $options;

        if(!empty($items))
        {
            $valueList = $this->getValueList();
            foreach($items as $key => $item)
            {
                if(!is_array($item)) $item = ['text' => $item, 'value' => $key];
                if(!isset($item['selected'])) $item['selected'] = in_array($item['value'], $valueList);
                $items[$key] = $this->onBuildItem($item);
            }
        }

        return h::select
        (
            setClass('form-control'),
            set::multiple($this->isMultiple()),
            set($this->props->skip(['items', 'options', 'value', 'multiple'])),
            $items,
            $this->children()
        );
    }
}
