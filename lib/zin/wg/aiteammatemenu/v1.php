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

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        $items = $this->prop('items');
        if(empty($items)) return null;

        list($btnName, $btnClass, $placement, $module, $method, $objectVarName, $isFormPage) =
            $this->prop(array('btnName', 'btnClass', 'placement', 'module', 'method', 'objectVarName', 'isFormPage'));

        if(empty($btnName))
        {
            global $app, $lang;
            $app->loadLang('aiteammate');
            $btnName = sprintf($lang->ai->promptMenu->assignedTo, $lang->aiteammate->common);
        }

        $objectID = $this->prop('objectID');

        $menuItems = array_map(function($teammate) use ($module, $method, $objectVarName, $isFormPage, $objectID)
        {
            $avatar = html::avatar(array(
                'avatar'  => $teammate->avatar,
                'account' => $teammate->name,
            ), '20', 'rounded-full', '', 'span');
            $name = sprintf($GLOBALS['lang']->ai->promptMenu->assignedTo, $teammate->name);

            $itemProps = array
            (
                'text'             => array('html' => $avatar . $name),
                'data-teammate-id' => $teammate->id,
                'data-module'      => $module,
                'data-method'      => $method,
            );

            if($isFormPage)
            {
                $itemProps['data-on'] = 'click';
                $itemProps['data-call'] = 'executeWithFormContextForTeammate(event)';
            }
            else
            {
                $itemProps['url'] = helper::createLink('aiteammate', 'assignagent',
                    "teammateID={$teammate->id}&objectType={$objectVarName}&objectID={$objectID}&pageInfo={$module},{$method}");
                $itemProps['data-toggle'] = 'modal';
                $itemProps['data-size'] = 'sm';
            }

            return $itemProps;
        }, array_values($items));

        return dropdown
        (
            btn
            (
                setClass('btn ai-styled size-sm font-medium', $btnClass),
                set::icon('hand-right'),
                set::caret(true),
                $btnName,
            ),
            set::placement($placement),
            set::items($menuItems),
            set::menuClass('ai-teammate-menu')
        );
    }
}
