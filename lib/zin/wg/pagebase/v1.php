<?php
declare(strict_types=1);
namespace zin;

class pageBase extends wg
{
    static $tag = 'html';

    protected static array $defineProps = array
    (
        'metas?: string|array',
        'title?: string',
        'bodyProps?: array',
        'bodyClass?: array|string',
        'zui?: bool',
        'lang?: string',
        'display?: bool',
        'rawContent?: bool'
    );

    protected static array $defaultProps = array
    (
        'zui' => false,
        'display' => true,
        'metas' => array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
    );

    protected static array $defineBlocks = array('head' => array());

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

    protected function build(): wg
    {
        global $lang, $config, $app;

        $zui  = $this->prop('zui');
        $head = $this->buildHead();
        $body = $this->buildBody();

        $jsConfig   = \js::getJSConfigVars();
        $bodyProps  = $this->prop('bodyProps');
        $bodyClass  = $this->prop('bodyClass');
        $metas      = $this->prop('metas');
        $rawContent = $this->prop('rawContent', !zin::$rawContentCalled);
        $title      = $this->props->get('title', data('title')) . " - $lang->zentaoPMS";
        $attrs      = $this->getRestProps();
        $css        = array(data('pageCSS'), '/*{{ZIN_PAGE_CSS}}*/');
        $js         = array('/*{{ZIN_PAGE_JS}}*/', data('pageJS'));
        $imports    = context::current()->getImportList();
        $webRoot    = $app->getWebRoot();
        $themeName  = $app->cookie->theme;
        $zuiPath    = $config->zin->zuiPath;

        $zinMode       = isset($config->zin->mode) ? $config->zin->mode : '';
        $jsConfig->zin = !empty($zinMode) ? $zinMode : true;

        $headImports = array();
        if($zui)
        {
            $headImports[] = h::importCss($zuiPath . 'zui.zentao.css', setID('zuiCSS'));
            $headImports[] = h::importCss($zuiPath . 'themes/' . $themeName . '.css', setID('zuiTheme'));
            $headImports[] = h::importJs($zuiPath . 'zui.zentao.js', setID('zuiJS'));
            $headImports[] = h::jsCall('$.setLibRoot', $zuiPath);

            $extraCSS = isset($config->zin->extraCSS) ? $config->zin->extraCSS : '';
            if(!empty($extraCSS)) $headImports[] = h::importCss($webRoot . 'js/zui3/' . $extraCSS);
        }
        $headImports[] = h::jsVar('window.config', $jsConfig, setID('configJS'));
        if($zui)
        {
            $extraJS = isset($config->zin->extraJS) ? $config->zin->extraJS : 'zin.js';
            if(!empty($extraJS)) $headImports[] = h::importJs($webRoot . 'js/zui3/' . $extraJS);
        }

        if($config->debug)
        {
            $js[] = h::createJsVarCode('window.zin', array('page' => $this->toJSON(), 'definedProps' => wg::$definedPropsMap, 'wgBlockMap' => wg::$wgToBlockMap, 'config' => jsRaw('window.config')));
            $js[] = 'console.log("[ZIN] ", window.zin);';
        }
        else
        {
            $js[] = h::createJsVarCode('window.zin', array());
        }
        if($zui) array_unshift($js, 'zui.defineFn();');

        $currentLang = $this->props->get('lang');
        if(empty($currentLang)) $currentLang = $app->getClientLang();

        return h::html
        (
            before(html('<!DOCTYPE html>')),
            set($attrs),
            set::className("theme-$themeName", $this->prop('class')),
            set::lang($currentLang),
            h::head
            (
                html($metas),
                h::title($title),
                $this->block('headBefore'),
                $headImports,
                $head
            ),
            h::body
            (
                set($bodyProps),
                set::className($bodyClass),
                empty($imports) ? null : h::import($imports),
                h::css($css, setClass('zin-page-css')),
                $body,
                $rawContent ? rawContent() : null,
                h::js($js, setClass('zin-page-js'))
            )
        );
    }
}
