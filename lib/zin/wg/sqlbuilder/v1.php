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
}
