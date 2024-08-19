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
        'resizable?: bool=true',                // 是否可拖拽调整大小
        'exposeEditor?: bool=true',             // 是否将编辑器实例挂载到 `window.$zenEditors`
        'size?: string="sm"',                   // 尺寸，可选值 'sm', 'lg', 'full', 'auto'
        'hideMenubar?: bool=false',             // 是否隐藏菜单栏
        'hideUI?: bool=false',                  // 是否隐藏整个编辑器 UI
        'readonly?: bool=false',                // 是否只读
        'bubbleMenu?: bool=false',              // 是否启用浮动菜单
        'slashMenu?: bool=false',               // 是否启用 `/` 菜单
        'menubarMode?: string="compact"',       // 菜单栏模式，可选值 'basic', 'compact', 'full'
        'locale?: string',                      // 语言，可选值 'zh', 'en'，默认跟随浏览器，也可以是自定义的语言项 JSON，详见 ZenEditor 文档
        'markdown?: bool=false',                // 是否启用 Markdown 模式，若启用，为了兼容性将会隐藏一些功能
        'neglectDefaultTextStyle?: bool=false', // 是否不赋予默认的文本样式
        'preferHardBreak?: bool=false',         // 是否优先使用硬回车而不是新段落
        'value?: string',                       // 初始内容
        'templateType?: string',                // 模板类型
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
        $props = $this->props->pick(array('uploadUrl', 'placeholder', 'fullscreenable', 'resizable', 'exposeEditor', 'size', 'hideMenubar', 'hideUI', 'readonly', 'bubbleMenu', 'slashMenu', 'menubarMode', 'locale', 'markdown', 'neglectDefaultTextStyle', 'preferHardBreak'));

        $customProps = $this->getRestProps();
        if(!isset($customProps['class'])) $customProps['class'] = 'w-full';

        return div
        (
            setClass('editor-container p-px mt-px rounded relative w-full', $this->prop('readonly') ? 'is-readonly' : ''),
            $props['size'] === 'full' ? setStyle('height', '100%') : setClass('h-auto'),
            zui::pageEditor
            (
                set::_class('w-full h-full')
            ),
            textarea
            (
                $this->prop('value'),
                set::rows(1),
                set::size($props['size'])
            ),
            input
            (
                set::name('uid'),
                set::value($this->prop('uid')),
                setClass('hidden')
            )
        );
    }
}
