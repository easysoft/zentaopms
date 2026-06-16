<?php
declare(strict_types=1);
namespace zin;

class aiAgentMenu extends wg
{
    protected static array $defineProps = array(
        'items?:array',
        'btnName?:string',
        'btnClass?:string',
        'placement?:string="bottom-end"',
        'objectID?:int',
        'isFormPage?:bool',
    );

    protected function build()
    {
        $items = $this->prop('items');
        if(empty($items)) return null;

        list($btnName, $btnClass, $placement) = $this->prop(array('btnName', 'btnClass', 'placement'));
        $itemClass = 'btn ghost size-sm font-medium text-left';

        if(empty($btnName))
        {
            global $lang;
            $btnName = $lang->ai->prompts->common;
        }

        $isFormPage = $this->prop('isFormPage');
        $objectID   = $this->prop('objectID');

        $menuItems = array_map(function($prompt) use ($isFormPage, $objectID, $itemClass)
        {
            $itemProps = array
            (
                set::text($prompt->name),
                set('class', $itemClass),
            );

            if($isFormPage)
            {
                $itemProps[] = set('data-on', 'click');
                $itemProps[] = set('data-call', "executeWithFormContext({$prompt->id})");
            }
            else
            {
                $itemProps[] = set('url', helper::createLink('ai', 'promptExecute',
                    "promptId={$prompt->id}&objectId={$objectID}&auto=0"));
            }

            return item(...$itemProps);
        }, array_values($items));

        return dropdown
        (
            btn
            (
                setClass('btn ai-styled size-sm font-medium', $btnClass),
                set::icon('lightning'),
                set::caret(true),
                $btnName,
            ),
            set::placement($placement),
            to::menu
            (
                menu
                (
                    setClass('dropdown-menu'),
                    ...$menuItems
                )
            )
        );
    }
}
