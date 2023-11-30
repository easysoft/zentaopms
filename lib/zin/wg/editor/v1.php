<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';

class editor extends wg
{
    protected static array $defineProps = array(
        'createInput?: bool=true',        // 是否创建一个隐藏的 input 存储编辑器内容
        'uploadUrl?: string',             // 图片上传链接
        'placeholder?: string=""',        // 占位文本
        'fullscreenable?: bool=true',     // 是否可全屏
        'resizable?: bool=true',          // 是否可自适应
        'exposeEditor?: bool=true',       // 是否将编辑器实例挂载到 window
        'size?: string="sm"',             // 尺寸
        'hideMenubar?: bool=false',       // 是否隐藏 menubar
        'bubbleMenu?: bool=false',        // 是否启用菜单冒泡
        'menubarMode?: string="compact"', // 菜单栏模式
        'value?: string',                 // 内容
        'uid?: string'                    // 图片上传uid
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        // global $app;
        // $jsFile = $app->getWebRoot() . 'js/zeneditor/tiptap-component.esm.js';
        $jsFile = 'https://zui-dist.oop.cc/zeneditor/tiptap-component.esm.js';
        return '$.getLib("' . $jsFile . '", {type: "module", root: false}, () => {document.body.dataset.loadedEditor = true;});';
    }

    protected function created()
    {
        if(empty($this->prop('uid'))) $this->setProp('uid', $this->gid);
        $this->setDefaultProps(array('uploadUrl' => helper::createLink('file', 'ajaxUpload', 'uid=' . $this->prop('uid'))));
    }

    protected function build(): wg
    {
        $editor = new h
        (
            setTag('tiptap-editor'),
            setClass('form-control', 'p-0'),
            $this->prop('size') === 'full' ? setStyle('height', '100%') : setClass('h-auto')
        );

        $props = $this->props->pick(array('createInput', 'uploadUrl', 'placeholder', 'fullscreenable', 'resizable', 'exposeEditor', 'size', 'hideMenubar', 'bubbleMenu', 'menubarMode', 'collaborative', 'hocuspocus', 'docName', 'username', 'userColor'));
        foreach($props as $key => $value)
        {
            if($value === true || (is_string($value) && !empty($value))) $editor->add(set(uncamelize($key), $value));
        }

        $customProps = $this->getRestProps();
        if(!isset($customProps['id']))    $customProps['id'] = $customProps['name'];
        if(!isset($customProps['class'])) $customProps['class'] = 'w-full';

        $editor->add(set($customProps));
        $editor->add($this->prop('value'));
        $editor->add($this->children());

        return div
        (
            setClass('editor-container p-px mt-px rounded'),
            $props['size'] === 'full' ? setStyle('height', '100%') : setClass('h-auto'),
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
