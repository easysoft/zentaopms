<?php
declare(strict_types=1);
namespace zin;

class gantt extends wg
{
    public static function getPageCSS(): string
    {
        return file_get_contents(dirname(__DIR__, 4) . '/www/js/dhtmlxgantt/min.css');
    }

    protected function build(): wg
    {
        global $app;
        $jsFile = $app->getWebRoot() . 'js/dhtmlxgantt/min.js';

        return zui::gantt(inherit($this), set::_call("~((name,selector,options) => $.getLib('$jsFile', {root: false}, () => {gantt.plugins({marker: true, critical_path: true, fullscreen: true, tooltip: true, click_drag: true});zui.create(name,selector,options);}))"));

    }
}
