<?php
declare(strict_types=1);
namespace zin;

class aiTeammateMenu extends wg
{
    protected static array $defineProps = array(
        'items?:array',
        'btnName?:string',
        'btnClass?:string',
        'placement?:string="bottom-end"',
        'module?:string',
        'method?:string',
        'objectID?:int',
        'objectVarName?:string',
        'isFormPage?:bool',
    );

    protected function build()
    {
        $items = $this->prop('items');
        if(empty($items)) return null;

        list($btnName, $btnClass, $module, $method, $objectVarName, $isFormPage) =
            $this->prop(array('btnName', 'btnClass', 'module', 'method', 'objectVarName', 'isFormPage'));

        if(empty($btnName))
        {
            global $app, $lang;
            $app->loadLang('aiteammate');
            $btnName = sprintf($lang->ai->promptMenu->assignedTo, $lang->aiteammate->common);
        }

        return dropdown
        (
            btn
            (
                setClass('btn ai-styled size-sm font-medium', $btnClass),
                set::icon('hand-right'),
                set::caret(true),
                $btnName,
            ),
            set::placement($this->prop('placement')),
            set::items
            (
                array_map(function($teammate) use ($module, $method, $objectVarName, $isFormPage)
                {
                    $avatar = html::avatar(array(
                        'avatar'  => $teammate->avatar,
                        'account' => $teammate->name,
                    ), '20', 'rounded-full');
                    $name = sprintf($GLOBALS['lang']->ai->promptMenu->assignedTo, $teammate->name);

                    return array(
                        'text'    => $avatar . $name,
                        'class'   => 'btn ghost size-sm font-medium text-left',
                        'data'    => array(
                            'teammate-id' => $teammate->id,
                            'module'      => $module,
                            'method'      => $method,
                        ),
                        'click'   => $isFormPage
                            ? 'executeWithFormContextForTeammate(this)'
                            : null,
                        'url'     => !$isFormPage
                            ? helper::createLink('aiteammate', 'assignagent',
                                "teammateID={$teammate->id}&objectType={$objectVarName}&objectID={$this->prop('objectID')}&pageInfo={$module},{$method}")
                            : null,
                    );
                }, $items)
            ),
        );
    }
}
