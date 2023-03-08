<?php
namespace zin;

class pagebase extends wg
{
    static $tag = 'html';

    static $defineProps = 'metas?: string|array, title?: string, bodyProps?: array, zui?: bool, display?: bool';

    static $defaultProps = array('zui' => false, 'display' => true, 'metas' => array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">'));

    static $defineBlocks = array('head' => array());

    protected function created()
    {
        if($this->prop('display')) $this->display();
    }

    protected function buildHead()
    {
        return $this->block('head');
    }

    protected function buildBody()
    {
        return $this->children();
    }

    protected function build()
    {
        global $lang, $config;

        $zui = $this->prop('zui');
        return h::html
        (
            before(html('<!DOCTYPE html>')),
            h::head
            (
                html($this->prop('metas')),
                h::title($this->props->get('title', '') . " - $lang->zentaoPMS"),
                $zui ? h::import(array($config->zin->zuiPath . 'zui.zentao.umd.cjs', $config->zin->zuiPath . 'zui.zentao.css')) : null,
                h::js('window.domReady = function(fn){if (document.readyState !== \'loading\') {fn();} else {document.addEventListener(\'DOMContentLoaded\', fn);}};'),
                $zui ? h::js
                (
                    'zui.create = function(name, element, options){',
                        'if(!zui.componentsMap) zui.componentsMap = Object.keys(zui).reduce(function(map, n){',
                            'if(n[0] !== n[0].toUpperCase()) return map;',
                            'map[n.toLowerCase()] = zui[n];',
                            'return map;',
                        '}, {});',
                        'const Component = zui.componentsMap[name.toLowerCase()];',
                        'return Component ? new Component(element, options) : null;',
                    '};'
                ) : null,
                $this->buildHead()
            ),
            h::body
            (
                set($this->prop('bodyProps')),
                $this->buildBody(),
                $config->debug ? h::js('window.zin = ' . json_encode(array('page' => $this->toJsonData(), 'definedProps' => wg::$definedPropsMap, 'wgBlockMap' => wg::$wgToBlockMap)) . ';console.log("zin", window.zin)') : null
            )
        );
    }
}
