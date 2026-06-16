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

        list($btnName, $btnClass) = $this->prop(array('btnName', 'btnClass'));

        if(empty($btnName))
        {
            global $lang;
            $btnName = $lang->ai->prompts->common;
        }

        return dropdown
        (
            btn
            (
                setClass('btn ai-styled size-sm font-medium', $btnClass),
                set::icon('lightning'),
                set::caret(true),
                $btnName,
            ),
            set::placement($this->prop('placement')),
            set::items
            (
                array_map(function($prompt)
                {
                    return array(
                        'text'  => $prompt->name,
                        'click' => $this->prop('isFormPage')
                            ? "executeWithFormContext({$prompt->id})"
                            : null,
                        'url'   => !$this->prop('isFormPage')
                            ? helper::createLink('ai', 'promptExecute',
                                "promptId={$prompt->id}&objectId={$this->prop('objectID')}&auto=0")
                            : null,
                        'class' => 'btn ghost size-sm font-medium text-left',
                    );
                }, $items)
            ),
        );
    }
}
