<?php
declare(strict_types=1);
namespace zin;

class content extends wg
{
    protected static array $defineProps = array
    (
        'control?: string',          // 内容类型，值可以为：html, text 以及其他部件的类型。
        'render?: callable|Closure'  // 自定义构建函数。
    );

    protected function buildText(): node
    {
        return div
        (
            setClass('text'),
            set($this->getRestProps()),
            $this->prop('text'),
            $this->prop('content')
        );
    }

    protected function buildLink(): node
    {
        return a
        (
            set::href($this->prop('url')),
            set($this->getRestProps()),
            $this->prop('text')
        );
    }

    protected function buildHtml(): node
    {
        return div
        (
            setClass('article'),
            set($this->getRestProps()),
            html($this->prop('content'))
        );
    }

    protected function buildList(): node
    {
        return new simpleList(set($this->props->skip('control')), $this->children());
    }

    protected function buildDivider(): node
    {
        return hr(setClass('divider'));
    }

    protected function build()
    {
        $render = $this->prop('render');
        if($render instanceof \Closure) return $render($this->props->skip('render'), $this->children());
        elseif(is_callable($render)) return call_user_func($render, $this->props->skip('render'), $this->children());

        $control = $this->prop('control');
        if($control)
        {
            $methodName = "build{$control}";
            if(method_exists($this, $methodName)) return $this->$methodName();

            $wgName = "\\zin\\$control";
            if(class_exists($wgName)) return new $wgName(set($this->props->skip('control')), $this->children());

            return createWg($control, set($this->props->skip('control')), 'div');
        }

        if($this->hasProp('children')) $this->prop('children');

        return parent::build();
    }
}
