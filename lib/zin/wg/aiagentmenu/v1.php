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
        'module?:string',
        'method?:string',
    );

    protected function build()
    {
        global $app;

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

        $app->control->loadModel('ai');

        global $config;
        $formModule = $this->prop('module');
        $formMethod = $this->prop('method');
        if($isFormPage && $formModule && $formMethod) $app->loadConfig('ai');

        $menuItems = array_map(function($prompt) use ($isFormPage, $objectID, $itemClass, $app, $config, $formModule, $formMethod)
        {
            $itemProps = array
            (
                set::text($prompt->name),
                set('class', $itemClass),
            );

            if($isFormPage)
            {
                $promptFields  = $app->control->ai->getPromptFields((int)$prompt->id);
                $fieldsData    = $promptFields ? helper::jsonEncode(array_values($promptFields)) : '[]';
                $allowedFields = $app->control->ai->getFormAllowedFields($formModule, $formMethod);
                $agentRole     = helper::jsonEncode(($prompt->role ?? '') . (!empty($prompt->characterization) ? "\n{$prompt->characterization}" : ''));
                $agentPurpose  = helper::jsonEncode($prompt->purpose ?? '');

                $itemProps[] = set('data-on', 'click');
                $itemProps[] = set('data-call', "executeWithFormContext({$prompt->id})");
                $itemProps[] = set('data-prompt-fields', $fieldsData);
                $itemProps[] = set('data-allowed-fields', helper::jsonEncode($allowedFields));
                $itemProps[] = set('data-agent-role', $agentRole);
                $itemProps[] = set('data-agent-purpose', $agentPurpose);
            }
            else
            {
                $itemProps[] = set('data-on', 'click');
                $itemProps[] = set('data-call', "callZentaoAgent({$prompt->id}, {$objectID})");
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
