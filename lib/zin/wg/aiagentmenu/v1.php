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

        if(empty($btnName))
        {
            global $lang;
            $btnName = $lang->ai->prompts->common;
        }

        $isFormPage = $this->prop('isFormPage');
        $objectID   = $this->prop('objectID');

        $app->control->loadModel('ai');

        $formModule = $this->prop('module');
        $formMethod = $this->prop('method');
        if($isFormPage && $formModule && $formMethod) $app->loadConfig('ai');

        $menuItems = array_map(function($prompt) use ($isFormPage, $objectID, $app, $formModule, $formMethod)
        {
            $itemProps = ['text' => $prompt->name];

            if($isFormPage)
            {
                $promptFields  = $app->control->ai->getPromptFields((int)$prompt->id);
                $fieldsData    = $promptFields ? helper::jsonEncode(array_values($promptFields)) : '[]';
                $allowedFields = $app->control->ai->getFormAllowedFields($formModule, $formMethod);
                $fieldLabels   = $app->control->ai->getFormFieldLabels($formModule);
                $agentRole     = helper::jsonEncode(($prompt->role ?? '') . (!empty($prompt->characterization) ? "\n{$prompt->characterization}" : ''));
                $agentPurpose  = helper::jsonEncode($prompt->purpose ?? '');
                $agentSkills   = ($app->config->edition != 'open' && method_exists($app->control->ai, 'getPromptSkillIDs'))
                    ? $app->control->ai->getPromptSkillIDs($prompt)
                    : [];

                $itemProps['data-on'] = 'click';
                $itemProps['data-call'] = "executeWithFormContext({$prompt->id})";
                $itemProps['data-prompt-fields'] = $fieldsData;
                $itemProps['data-allowed-fields'] = helper::jsonEncode($allowedFields);
                $itemProps['data-field-labels'] = helper::jsonEncode($fieldLabels);
                $itemProps['data-agent-role'] = $agentRole;
                $itemProps['data-agent-purpose'] = $agentPurpose;
                $itemProps['data-agent-skills'] = helper::jsonEncode($agentSkills);
            }
            else
            {
                $itemProps['data-on'] = 'click';
                $itemProps['data-call'] = "callZentaoAgent({$prompt->id}, {$objectID})";
            }

            return $itemProps;
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
            set::items($menuItems)
        );
    }
}
