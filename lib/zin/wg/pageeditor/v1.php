<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';

class pageEditor extends wg
{
    protected static array $defineProps = array(
        'uploadUrl?: string',                   // 图片上传链接
        'placeholder?: string=""',              // 占位文本
        'fullscreenable?: bool=true',           // 是否可全屏
        'size?: string="sm"',                   // 尺寸，可选值 'sm', 'lg', 'full', 'auto'
        'readonly?: bool=false',                // 是否只读
        'locale?: string',                      // 语言，可选值 'zh', 'en'，默认跟随浏览器，也可以是自定义的语言项 JSON，详见 ZenEditor 文档
        'value?: string',                       // 初始内容
        'uid?: string'                          // 图片上传 uid
    );

    protected function created()
    {
        if(empty($this->prop('uid'))) $this->setProp('uid', uniqid());
        $this->setDefaultProps(array('uploadUrl' => createLink('file', 'ajaxUpload', 'uid=' . $this->prop('uid'))));
    }

    protected function buildTemplate(string $editor, string $type): node
    {
        global $app, $lang;
        $app->loadLang('user');

        jsVar('templateEmpty', $lang->user->tplContentNotEmpty);
        jsVar('confirmDeleteTemplate', $lang->user->confirmDeleteTemplate);

        return btnGroup
        (
            setClass('absolute right-0'),
            btn
            (
                setClass('ghost border-l border-r border-light'),
                on::click("window.showSaveModal('$editor', '$type')"),
                $lang->user->saveTemplate
            ),
            dropdown
            (
                btn($lang->user->applyTemplate, setClass('ghost')),
                set::items(array('url' => createLink('user', 'ajaxGetTemplates', "editor=$editor&type=$type")))
            )
        );
    }

    protected function build()
    {
        global $config;

        $value    = $this->prop('value');
        $readonly = $this->prop('readonly');
        $name     = $this->prop('name');
        $size     = $this->prop('size');

        $zuiPath = isset($config->zuiEditorPath) ? $config->zuiEditorPath : null;
        $zuiPathSetting = null;
        if($zuiPath)
        {
            $zuiPathSetting = js
            (
                <<<JS
                $.registerLib('blocksuite', {
                    src: [
                        '$zuiPath/editor.umd.cjs',
                        '$zuiPath/editor.css',
                    ],
                    root: false,
                    check: 'BlockSuite',
                });
                JS
            );
        }

        return div
        (
            setClass('editor-container p-px mt-px rounded relative w-full no-morph', $readonly ? 'is-readonly' : ''),
            $size === 'full' ? setStyle('height', '100%') : setClass('h-auto'),
            setCssVar('--affine-editor-side-padding', '0'),
            zui::pageEditor
            (
                set::_class('w-full h-full'),
                set::name($name),
                set::content($value),
                set::readonly($readonly),
                set($this->getRestProps())
            ),
            input
            (
                set::name('uid'),
                set::value($this->prop('uid')),
                setClass('hidden')
            ),
            $zuiPathSetting
        );
    }
}
