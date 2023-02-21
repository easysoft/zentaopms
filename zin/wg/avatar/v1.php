<?php
namespace zin;

class avatar extends wg
{
    protected static $defineProps = array
    (
        'class?:string="userMenu dropdown-toggle"',
        'data-arrow?:string="true"',
        'data-toggle?:string="dropdown"',
        'data-trigger?:string="hover"',
        'data-placement?:string="bottom"',
        'href?:string="#userMenu"',
        'theme?:string="success"',
        'radius?:string="circle"',
        'size?:string',
        'outline?:string',
        'trigger?:string',
        'showName' => array
        (
            'type' => 'bool',
            'default' => false
        )
    );

    private $skipProps        = array('theme', 'radius', 'showName', 'outline', 'size', 'avatar', 'name', 'trigger');
    private $skipTriggerProps = array('data-arrow', 'data-toggle', 'data-placement', 'data-trigger', 'href');

    protected function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('name'))
        {
            $this->setProp('name', $child);
            return false;
        }

        return $child;
    }

    protected function build()
    {
        $name   = $this->prop('name');
        $avatar = $this->prop('avatar');

        /* Without name and avatar url. */
        if(empty($name) and empty($avatar)) return null;

        $radius   = $this->prop('radius');
        $theme    = $this->prop('theme');
        $outline  = $this->prop('outline');
        $size     = $this->prop('size');
        $role     = $this->prop('role');
        $showName = $this->prop('showName');
        $trigger  = $this->prop('trigger');

        $skipProps = !empty($trigger) ? $this->skipProps : array_merge($this->skipProps, $this->skipTriggerProps);
        if(!empty($trigger)) $this->setProp('href', $trigger);

        return h::div
        (
            set($this->props->skip($skipProps)),
            h::div
            (
                setClass('avatar'),
                empty($radius)  ? null : setClass($radius),
                empty($theme)   ? null : setClass($theme),
                empty($outline) ? null : setClass($outline . '-outline'),
                empty($size)    ? null : setClass($size),
                !empty($avatar) ? h::img(set('src', $avatar)) : strtoupper(mb_substr($name, 0, 1))
            ),
            empty($showName) ? null : h::span($name),
            empty($showName) or empty($role) ? null : h::span($role)
        );
    }
}

