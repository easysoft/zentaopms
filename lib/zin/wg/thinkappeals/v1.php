<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引师$APPEALS模型部件类。
 * thinmory $APPEALS model widget class.
 */
class thinkAppeals extends thinkModel
{
    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function getIndicator(): array
    {
        global $app;

        $blocks    = $this->prop('blocks');
        $indicator = array();

        foreach($blocks['steps'] as $key => $step)
        {
            $indicator[] = array('name' => $step->title, 'color'=> '#64758B', 'axisLabel' => $key == 0 ? array('show' => true) : null);
        }
        return $indicator;
    }
}
