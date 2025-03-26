<?php
declare(strict_types=1);
namespace zin;

class monaco extends wg
{
    protected static array $defineProps = array(
        'id: string',
        'action?: string',
        'options?: array',
        'diffContent?: array',
        'selectedLines?: string', // startLine,endLine 或者 startLine,endLine,startCol,endCol
        'selectedClass?: string', // yellow-decoration: 黄色背景，wave-decoration: 红色波浪线
        'lineMap?: array',        // 用于设置行号的显示规则，如 array('编辑器行号' => '实际行号')
        'onMouseDown?: string',
        'onMouseMove?: string'
    );

    protected static array $defaultProps = array(
        'action'      => 'create',
        'options'     => array(),
        'diffContent' => array(),
        'onMouseDown' => '',
        'onMouseMove' => ''
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        global $app;
        $vsPath        = $app->getWebRoot() . 'js/monaco-editor/min/vs';
        $clientLang    = $app->clientLang;
        $id            = $this->prop('id');
        $action        = $this->prop('action');
        $options       = $this->prop('options');
        $diffContent   = $this->prop('diffContent');
        $selectedLines = $this->prop('selectedLines');
        $selectedClass = $this->prop('selectedClass');
        $lineMap       = $this->prop('lineMap');
        $onMouseDown   = $this->prop('onMouseDown');
        $onMouseMove   = $this->prop('onMouseMove');

        if(!$options) $options = new stdclass();
        return div
        (
            jsVar('id', $id),
            jsVar('action', $action),
            jsVar('options', $options),
            jsVar('diffContent', !$diffContent ? new stdclass() : $diffContent),
            jsVar('onMouseDown', $onMouseDown),
            jsVar('onMouseMove', $onMouseMove),
            jsVar('vsPath', $vsPath),
            jsVar('clientLang', $clientLang),
            jsVar('selectedLines', $selectedLines),
            jsVar('selectedClass', $selectedClass),
            jsVar('+lineMap', empty($lineMap) ? null : $lineMap),
            h::import($app->getWebRoot() . 'js/monaco-editor/min/vs/loader.js'),
            setID($id)
        );
    }
}
