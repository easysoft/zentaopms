<?php
declare(strict_types=1);
namespace zin;

class mainMenu extends wg
{
    protected static array $defineProps = array(
        'statuses?:array',
        'btnGroup?:array',
        'others?:array'
    );

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

    protected function build(): wg
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
            setID('mainMenu'),
            setClass('flex justify-between'),
            set($this->getRestProps()),
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
                setID('featureBarBtns'),
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
