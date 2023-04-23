<?php

namespace zin;

class mainMenu extends wg
{
    static $defineProps = 'statuses?:array,btnGroup?:array,others?:array';

    protected function buildOther($item)
    {
        if ($item['type'] === 'checkbox')
        {
            unset($item['type']);
            return checkbox(inherit(item(set($item))));
        }

        if ($item['type'] === 'button')
        {
            unset($item['type']);
            return btn(inherit(item(set($item))));
        }

        return null;
    }

    protected function build()
    {
        $others = $this->prop('others');

        if(empty($others))
        {
            $otherElms = null;
        }
        else
        {
            $otherElms = array();
            foreach($others as $item) $otherElms[] = $this->buildOther($item);
        }


        return div
        (
            setId('mainMenu'),
            setClass('flex justify-between'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            div
            (
                setClass('flex'),
                div
                (
                    toolbar(set(array('items' => $this->prop('statuses'))))
                ),
                $otherElms
            ),
            div
            (
                setId('featureBarBtns'),
                toolbar
                (
                    setClass('toolbar-btn-group'),
                    setStyle('gap', '0.625rem'),
                    set(array('items' => $this->prop('btnGroup')))
                )
            )
        );
    }
}
