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
        'rawContent?: bool'
    );

    protected static array $defaultProps = array
    (
        'zui'     => false,
        'display' => true,
        'metas'   => array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')
    );

    protected static array $defineBlocks = array('head' => array());

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
        global $lang, $config, $app;

        $setXuanClass = str_contains($_SERVER['HTTP_USER_AGENT'], 'xuanxuan')
            ? setClass('xxc-embed')
            : null;

        $zui  = $this->prop('zui');
        $head = $this->buildHead();
        $body = $this->buildBody();

        $context     = context();
        $jsConfig    = \js::getJSConfigVars();
        $bodyProps   = $this->prop('bodyProps');
        $bodyClass   = $this->prop('bodyClass');
        $metas       = $this->prop('metas');
        $rawContent  = $this->prop('rawContent', !$context->rawContentCalled);
        $hookContent = $this->prop('hookContent', !$context->hookContentCalled);
        $title       = $this->props->get('title', data('title')) . " - $lang->zentaoPMS";
        $attrs       = $this->getRestProps();
        $css         = array(data('pageCSS'), '/*{{ZIN_PAGE_CSS}}*/');
        $js          = array('/*{{ZIN_PAGE_JS}}*/', data('pageJS'));
        $imports     = $context->getImports();
        $webRoot     = $app->getWebRoot();
        $themeName   = $app->cookie->theme;
        $zuiPath     = $config->zin->zuiPath;
        $pageID      = $jsConfig->currentModule . '-' . $jsConfig->currentMethod;

        $zinMode       = isset($config->zin->mode) ? $config->zin->mode : '';
        $jsConfig->zin = !empty($zinMode) ? $zinMode : true;

        $headImports = array();
        $headImports[] = h::favicon($webRoot . 'favicon.ico');
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

        if($config->debug)
        {
            $zinDebugData = array('config' => jsRaw('window.config'));
            if($config->debug > 4)
            {
                $zinDebugData['zinTool'] = isset($config->zinTool) ? $config->zinTool : false;
                if($config->debug > 5)
                {
                    $zinDebugData['page']         = $this->toJSON();
                    $zinDebugData['definedProps'] = wg::$definedPropsMap;
                    $zinDebugData['wgBlockMap']   = wg::$blockMap;
                }
            }
            $headImports[] = h::js('window.zin = ' . js::value($zinDebugData) . ';');
        }
        else
        {
            $headImports[] = h::js('window.zin = {};');
        }

        if($setXuanClass) $headImports[] = h::importCss($config->webRoot . 'zentaoclient.css', setID('zentaoclient'));
        if($zui)
        {
            $extraJS = isset($config->zin->extraJS) ? $config->zin->extraJS : 'zin.js';
            if(!empty($extraJS)) $headImports[] = h::importJs($webRoot . 'js/zui3/' . $extraJS);
            array_unshift($js, 'zui.defineFn();');
            array_unshift($headImports, h::js("window.EDITOR_IMAGE_PROXY_ENDPOINT='{$webRoot}imgproxy.php';"));
        }

        $currentLang = $this->props->get('lang');
        if(empty($currentLang)) $currentLang = $app->getClientLang();

        return h::html
        (
            before(html('<!DOCTYPE html>')),
            set($attrs),
            set::className("theme-$themeName", $this->prop('class')),
            set::lang($currentLang),
            $setXuanClass,
            h::head
            (
                html($metas),
                h::title(html(html_entity_decode($title))),
                $this->block('headBefore'),
                $headImports,
                $head
            ),
            h::body
            (
                set($bodyProps),
                set::className($bodyClass),
                $setXuanClass,
                empty($imports) ? null : h::import($imports),
                h::css($css, setClass('zin-page-css'), setData('id', $pageID)),
                $body,
                $rawContent ? rawContent() : null,
                $hookContent ? hookContent() : null,
                h::js($js, setClass('zin-page-js'), setData('id', $pageID))
            )
        );
    }
}
