<?php
declare(strict_types=1);
namespace zin;

class sqlBuilder extends wg
{
    protected static array $defineProps = array(
        'class?: string',
        'steps?: array',
        'requiredSteps?: array=["table"]',
        'currStep?: string'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        $content = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
        return $content;
    }

    protected function setSteps()
    {
        global $lang;
        list($steps, $currStep) = $this->prop(array('steps', 'currStep'));

        if(empty($steps))
        {
            $stepList = $lang->bi->builderStepList;
            if(empty($steps)) $this->setProp('steps', array_keys($stepList));
        }

        list($steps, $currStep) = $this->prop(array('steps', 'currStep'));
        if(empty($currStep)) $this->setProp('currStep', reset($steps));
    }

    protected function buildStepBar()
    {
        global $lang;
        list($steps, $selected, $requires) = $this->prop(array('steps', 'currStep', 'requiredSteps'));

        $stepList = $lang->bi->builderStepList;
        $lastStep = end($steps);
        $items    = array();

        foreach($steps as $step)
        {
            if(!isset($stepList[$step])) continue;

            $key  = $step;
            $text = $stepList[$key];

            $isSelected = $selected == $key;
            $required   = $key == 'table';
        }

        $selectedClass = 'text-primary ring-secondary font-bold selected';
        $defaultClass  = 'text-gray-900 ring-opacity-0 font-medium';

        foreach($stepList as $key => $text)
        {
            $isSelected = $selected == $key;
            $required   = in_array($key, $requires);

            $classList = array();
            $classList[$selectedClass] = $isSelected;
            $classList[$defaultClass]  = !$isSelected;
            $classList['required'] = $required;

            $items[] = btn
            (
                setClass('builder-step-btn relative text-md mx-2 bg-inherit ring', $classList),
                set('data-step', $key),
                set::type('default'),
                $text,
                on::click()->do("switchStep(event, '$selectedClass', '$defaultClass')")
            );

            if($key != $lastStep) $items[] = icon
            (
                setClass('self-center text-gray-500 text-lg leading-3'),
                'angle-down'
            );
        }

        return div
        (
            setClass('builder-step-bar flex col justify-evenly basis-40 gap-1 bg-primary-50 h-full'),
            $items
        );
    }

    protected function buildTableStep()
    {
        return $this->buildStepContent('table');
    }

    protected function buildFieldStep()
    {
        return $this->buildStepContent('field');
    }

    protected function buildFuncStep()
    {
        return $this->buildStepContent('func');
    }

    protected function buildWhereStep()
    {
        return $this->buildStepContent('where');
    }

    protected function buildQueryStep()
    {
        return $this->buildStepContent('query');
    }

    protected function buildGroupStep()
    {
        return $this->buildStepContent('group');
    }

    protected function buildStepContent($step, $contents = array(), $extraHeading = null)
    {
        global $lang;
        list($currStep) = $this->prop(array('currStep'));

        $ucStep = ucfirst($step);
        $contentTitle    = $lang->bi->{"step{$ucStep}Title"};
        $contentTitleTip = $lang->bi->{"step{$ucStep}Tip"};
        return panel
        (
            setID("builder$step"),
            setClass('w-full builder-content', array('hidden' => $currStep !== $step)),
            set::title($contentTitle),
            set::headingClass('justify-start gap-0'),
            set::bodyClass('h-86 overflow-auto'),
            to::heading
            (
                sqlBuilderHelpIcon
                (
                    set::text($contentTitleTip)
                ),
                $extraHeading
            ),
            $contents
        );
    }

    protected function build()
    {
        global $lang;
        $this->setSteps();
        list($step, $steps) = $this->prop(array('currStep', 'steps'));

        $ucStep = ucfirst($step);
        $contentTitle    = $lang->bi->{"step{$ucStep}Title"};
        $contentTitleTip = $lang->bi->{"step{$ucStep}Tip"};

        return panel
        (
            setID('builderPanel'),
            setClass('h-96'),
            set::bodyClass('p-0 flex h-96'),
            $this->buildStepBar(),
            $this->buildTableStep(),
            $this->buildFieldStep(),
            $this->buildFuncStep(),
            $this->buildWhereStep(),
            $this->buildQueryStep(),
            $this->buildGroupStep(),
        );
    }
}
