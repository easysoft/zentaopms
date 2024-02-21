<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';

class editor extends wg
{
    protected static array $defineProps = array(
        'uploadUrl?: string',             // 图片上传链接
        'placeholder?: string=""',        // 占位文本
        'fullscreenable?: bool=true',     // 是否可全屏
        'resizable?: bool=true',          // 是否可拖拽调整大小
        'exposeEditor?: bool=true',       // 是否将编辑器实例挂载到 `window.$zenEditors`
        'size?: string="sm"',             // 尺寸，可选值 'sm', 'lg', 'full'
        'hideMenubar?: bool=false',       // 是否隐藏菜单栏
        'bubbleMenu?: bool=false',        // 是否启用浮动菜单
        'menubarMode?: string="compact"', // 菜单栏模式，可选值 'compact', 'full'
        'locale?: string',                // 语言，可选值 'zh', 'en'，默认跟随浏览器，也可以是自定义的语言项 JSON，详见 ZenEditor 文档
        'value?: string',                 // 初始内容
        'templateType?: string',          // 模板类型
        'uid?: string'                    // 图片上传 uid
    );

    protected static string $css = <<<CSS
        .editor {border: unset; border-radius: unset;}
        zen-editor-menu-item > .menu-item {color: #9ea3b0!important;}
        zen-editor-menu-item > .menu-item:hover, zen-editor-menu-item > .menu-item.is-active {color: #fff!important; background-color: var(--color-primary-400)!important;}
        zen-editor-menu-item > .menu-item:has(.color):hover, zen-editor-menu-item > .menu-item:has(.color).is-active {background-color: transparent!important; box-shadow: inset 0 0 0 1px #9ea3b0!important;}
        .menubar {border-bottom: 1px solid #d8dbde!important; padding: 0.25rem;}
        .tippy-content > div {border: 1px solid #d8dbde!important;}
        .tippy-content zen-editor-menu-item {line-height: normal;}
        .tippy-content zen-editor-menu-item .label {all: unset;}
    CSS;

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        $content  = file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
        $content .= '$.getLib(\'zen-editor/zen-editor.esm.js\', {type: "module"}, () => {document.body.dataset.loadedEditor = true;});';
        return $content;
    }

    protected function created()
    {
        if(empty($this->prop('uid'))) $this->setProp('uid', uniqid());
        $this->setDefaultProps(array('uploadUrl' => createLink('file', 'ajaxUpload', 'uid=' . $this->prop('uid'))));
    }

    protected function buildTemplate(string $editor, string $type): wg
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
                on::click("showSaveModal('$editor', '$type')"),
                $lang->user->saveTemplate
            ),
            dropdown
            (
                btn($lang->user->applyTemplate),
                set::items(array('url' => createLink('user', 'ajaxGetTemplates', "editor=$editor&type=$type")))
            )
        );
    }

    protected function build()
    {
        global $lang;

        $editor = new h
        (
            setTag('zen-editor'),
            setClass('form-control', 'p-0'),
            $this->prop('size') === 'full' ? setStyle('height', '100%') : setClass('h-auto')
        );

        $props = $this->props->pick(array('uploadUrl', 'placeholder', 'fullscreenable', 'resizable', 'exposeEditor', 'size', 'hideMenubar', 'bubbleMenu', 'menubarMode', 'collaborative', 'hocuspocus', 'docName', 'username', 'userColor'));
        foreach($props as $key => $value)
        {
            if($key == 'placeholder' && empty($value)) $value = $lang->noticePasteImg;
            if($value === true || (is_string($value) && !empty($value))) $editor->add(set(uncamelize($key), $value));
        }

        $customProps = $this->getRestProps();
        if(!isset($customProps['class'])) $customProps['class'] = 'w-full';

        $editor->add(set($customProps));
        $editor->add(set('css', self::$css)); // Inject CSS into editor.
        $editor->add(h('article', set('slot', 'content'), html($this->prop('value')), $this->children())); // Set initial content.

        $templateType = $this->prop('templateType');

        return div
        (
            setClass('editor-container p-px mt-px rounded relative'),
            $props['size'] === 'full' ? setStyle('height', '100%') : setClass('h-auto'),
            h::css(self::$css), // Inject CSS on page, for tippy menus.
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
