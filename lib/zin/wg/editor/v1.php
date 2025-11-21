<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';

class editor extends wg
{
    protected static $version = '0.13.3'; // Keep in sync with ZenEditor release.

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

    protected static string $css = <<<CSS
        .editor {border: unset; border-radius: unset; color: var(--color-fore)}
        .editor.size-auto {min-height: 0;}
        zen-editor-menu-item {display: inline-flex; align-items: center;}
        zen-editor-menu-item > .menu-item {color: #64758B!important; display: inline-flex; align-items: center;}
        zen-editor-menu-item > .menu-item:hover {color: var(--color-primary-400)!important; background-color: var(--color-gray-100)!important;}
        zen-editor-menu-item > .menu-item.is-active {color: var(--color-primary-400)!important; background-color: transparent!important; box-shadow: inset 0 0 0 1px var(--color-primary-300);}
        zen-editor-menu-item > .menu-item.is-active:hover {background-color: var(--color-gray-200)!important;}
        zen-editor-menu-item > .menu-item.is-active:not(:hover) {background-color: transparent!important;}
        zen-editor-menu-item > .menu-item:has(.color):hover, zen-editor-menu-item > .menu-item:has(.color).is-active {background-color: ransparent!important; box-shadow: inset 0 0 0 1px var(--color-primary-300)!important;}
        .menubar {border-bottom: 1px solid #d8dbde!important; padding: 0.125rem;}
        .tippy-content > div {border: 1px solid #d8dbde!important;}
        .tippy-content zen-editor-menu-item {line-height: normal;}
        .tippy-content zen-editor-menu-item .label {all: unset;}
        .tiptap.ProseMirror {padding-top: 0.5rem;}
        .tiptap.ProseMirror p {margin: 0;}
    CSS;

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        $version = self::$version;
        $content = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
        $content .= "$.getLib('zen-editor/zen-editor.esm.js?v=$version', {type: 'module'}, () => {document.body.dataset.loadedEditor = true;});";
        return $content;
    }

    protected function created()
    {
        if(empty($this->prop('uid'))) $this->setProp('uid', uniqid());
        $this->setDefaultProps(array('uploadUrl' => createLink('file', 'ajaxUpload', 'uid=' . $this->prop('uid'))));
        if(helper::getBrowser()['name'] == 'safari') $this->setProp('neglectDefaultTextStyle', true);
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
            isInModal() ? null : btn
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
        global $lang, $app;

        $editor = new h
        (
            setTag('zen-editor'),
            setClass('p-0', $this->prop('readonly') ? '' : 'form-control'),
            $this->prop('size') === 'full' ? setStyle('height', '100%') : setClass('h-auto')
        );

        $props = $this->props->pick(array('uploadUrl', 'placeholder', 'fullscreenable', 'resizable', 'exposeEditor', 'size', 'hideMenubar', 'hideUI', 'readonly', 'bubbleMenu', 'slashMenu', 'menubarMode', 'locale', 'markdown', 'neglectDefaultTextStyle', 'preferHardBreak'));
        foreach($props as $key => $value)
        {
            if($key == 'placeholder' && empty($value)) $value = $lang->noticePasteImg;
            if($value === true || (is_string($value) && !empty($value))) $editor->add(set(uncamelize($key), $value));
        }

        $customProps = $this->getRestProps();
        if(!isset($customProps['class'])) $customProps['class'] = 'w-full';

        $editor->setProp($customProps);
        $editor->setProp('css', self::$css); // Inject CSS into editor.
        $editor->setProp('css-src', $app->getWebRoot() . 'js/zui3/zen-editor/zui-inject-style.css'); // Inject CSS on page, for tippy menus.

        /* Set initial content. */
        $initialContent = $this->prop('value');
        $children       = $this->children();
        if(empty($children)) $editor->setProp('initial-content', $initialContent);
        else                 $editor->add(h('article', set('slot', 'content'), $initialContent? html($initialContent) : null, $children));

        $templateType = $this->prop('templateType');

        return div
        (
            setClass('editor-container p-px mt-px rounded relative', $this->prop('readonly') ? 'is-readonly' : ''),
            $props['size'] === 'full' ? setStyle('height', '100%') : setClass('h-auto'),
            h::css(self::$css . $addCss), // Inject CSS on page, for tippy menus.
            $templateType ? $this->buildTemplate($this->prop('name'), $templateType) : null,
            $editor,
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
