<?php
declare(strict_types=1);
namespace zin;

class btn extends wg
{
    protected static array $defineProps = array(
        'text?:string',              // 按钮的文本。
        'icon?:string',              // 图标名称。
        'iconClass?:string',         // 图标的样式类。
        'square?:bool',              // 是否为方形按钮，通常用于只显示一个图标的按钮。
        'disabled?:bool',            // 是否禁用按钮。
        'active?:bool',              // 是否为激活状态。
        'url?:string',               // 按钮的链接地址。
        'target?:string',            // 按钮的链接目标。
        'size?:string|int',          // 按钮的尺寸，可选值为 `'xl'`、`'lg'`、`'md'`、`'sm'` 或者通过数字设置宽高，如 `20`。
        'trailingIcon?:string',      // 按钮尾部图标的名称。
        'trailingIconClass?:string', // 按钮尾部图标的样式类。
        'caret?:string|bool',        // 按钮的下拉箭头，可选值为 `'top'`（向上）、`'bottom'`（向下） 或者 `true`（自动）。
        'hint?:string',              // 按钮的提示文本（鼠标悬停时显示）。
        'type?:string',              // 按钮的类型，可选值为 `'default'`、`'primary'`、`'success'`、`'info'`、`'warning'`、`'danger'`、`'link'`。
        'btnType?:string="button"'   // 按钮的类型，可选值为 `'button'`、`'submit'`、`'reset'`。
    );

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    protected function getProps()
    {
        $url    = $this->prop('disabled') ? null : $this->prop('url');
        $target = $this->prop('target');
        $props  = array_merge($this->getRestProps(), array('title' => $this->prop('hint')));

        if(empty($url))
        {
            $props['type'] = $this->prop('btnType');
            if(!isset($props['data-target'])) $props['data-target'] = $target;
            return $props;
        }

        $props['tagName'] = 'a';
        if(!isset($props['href']))   $props['href'] = $url;
        if(!isset($props['target'])) $props['target'] = $target;
        return $props;
    }

    private function getChildren()
    {
        list($caret, $text, $icon, $iconClass, $trailingIcon, $trailingIconClass) = $this->prop(array('caret', 'text', 'icon', 'iconClass', 'trailingIcon', 'trailingIconClass'));

        $children = array();
        if(!empty($icon)) $children[] = icon($icon, setClass($iconClass));
        if(!empty($text)) $children[] = h::span($text, setClass('text'));
        $children[] = parent::build();
        if(!empty($trailingIcon)) $children[] = icon($trailingIcon, setClass($trailingIconClass));
        if(!empty($caret))        $children[] = h::span(setClass(is_string($caret) ? "caret-$caret" : 'caret'));

        return $children;
    }

    protected function getClassList()
    {
        list($url, $type, $caret, $text, $icon, $trailingIcon) = $this->prop(array('url', 'type', 'caret', 'text', 'icon', 'trailingIcon'));
        $onlyCaret = empty($text) && !empty($caret) && empty($icon) && empty($trailingIcon);
        $classList = array(
            'btn'       => true,
            'disabled'  => $this->prop('disabled'),
            'active'    => $this->prop('active'),
            'btn-caret' => $onlyCaret,
            'square'    => $this->prop('square')
        );

        if(empty($type) && !empty($url)) $type = 'btn-default';
        else if($type === 'link')        $type = 'btn-link';
        else if($type === 'default')     $type = 'btn-default';
        if(!empty($type))                $classList[$type] = true;

        if(empty($text) && !empty($icon) && !isset($classList['square'])) $classList['square'] = true;

        $size = $this->prop('size');
        if(!empty($size)) $classList["size-$size"] = true;

        return $classList;
    }

    protected function build(): wg
    {
        $props     = $this->getProps();
        $children  = $this->getChildren();
        $classList = $this->getClassList();

        return button
        (
            set($props),
            setClass($classList),
            $children
        );
    }
}
