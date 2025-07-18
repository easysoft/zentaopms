<?php
declare(strict_types=1);
namespace zin;

class content extends wg
{
    protected static array $defineProps = array
    (
        'control?: string|array',    // 内容类型，值可以为：html, text 以及其他部件的类型，也可以指定为包含 `control` 键值的控件属性数组。
        'content?: string|array',    // 内容数据。
        'render?: callable|Closure'  // 自定义构建函数。
    );

    protected static array $controlMap = array
    (
        'list'     => 'simpleList',
        'status'   => 'statusLabel',
        'pri'      => 'priLabel',
        'severity' => 'severityLabel',
    );

    protected function buildText(): node
    {
        return div
        (
            setClass('text'),
            set($this->getRestProps()),
            $this->prop('text'),
            $this->prop('content'),
            $this->children()
        );
    }

    protected function buildLink(): node
    {
        return a
        (
            set::href($this->prop('url')),
            set($this->getRestProps()),
            $this->prop('text'),
            $this->children()
        );
    }

    protected function buildHtml(): node
    {
        return div
        (
            setClass('article'),
            set($this->getRestProps()),
            html($this->prop('content')),
            $this->children()
        );
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
            $controlProps = array();
            $controlName  = '';
            if(is_string($control))
            {
                $controlName = $control;
            }
            elseif(is_array($control))
            {
                $controlName = $control['control'];
                unset($control['control']);
                $controlProps = $control;
            }

            $methodName = "build{$controlName}";
            if(method_exists($this, $methodName)) return $this->$methodName();

            if(isset(static::$controlMap[$controlName])) $controlName = static::$controlMap[$controlName];

            $wgName = "\\zin\\$controlName";
            if(class_exists($wgName)) return new $wgName(set($this->props->skip('control')), $controlProps ? set($controlProps) : null, $this->prop('children'), $this->children());

            return createWg($controlName, array(set($this->props->skip('control')), $controlProps ? set($controlProps) : null, $this->prop('children'), $this->children()), 'div');
        }

        if($this->hasProp('children')) return $this->prop('children');

        return parent::build();
    }
}
