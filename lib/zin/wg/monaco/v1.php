<?php
namespace zin;

class monaco extends wg
{
    protected static $defineProps = array(
        'id:string',
        'action?:string',
        'options?:array',
        'diffContent?:array',
        'onMouseDown?:string',
    );

    protected static $defaultProps = array(
        'action'  => 'create',
        'options' => array(),
        'diffContent' => array(),
        'onMouseDown' => '',
    );
    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $app;
        $vsPath      = $app->getWebRoot() . 'js/monaco-editor/min/vs';
        $clientLang  = $app->clientLang;
        $id          = $this->prop('id');
        $action      = $this->prop('action');
        $options     = $this->prop('options');
        $diffContent = $this->prop('diffContent');
        $onMouseDown = $this->prop('onMouseDown');

        if(!$options) $options = new stdclass();
        return div
        (
            jsVar('id', $id),
            jsVar('action', $action),
            jsVar('options', $options),
            jsVar('diffContent', $diffContent),
            jsVar('onMouseDown', $onMouseDown),
            jsVar('vsPath', $vsPath),
            jsVar('clientLang', $clientLang),
            h::import($app->getWebRoot() . 'js/monaco-editor/min/vs/loader.js'),
            setId($id),
        );
    }
}
