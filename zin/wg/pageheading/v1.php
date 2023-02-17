<?php
namespace zin;

class pageheading extends wg
{
    protected static $defineProps = 'text?:string, icon?:string, url?:string';

    /**
     * On add child.
     *
     * @param  object|string $child
     * @access protected
     * @return object|string
     */
    protected function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    /**
     * Build.
     *
     * @access protected
     * @return object
     */
    protected function build()
    {
        $icon = $this->prop('icon');
        $text = $this->prop('text');
        $url  = $this->prop('url');

        $container = h::div
        (
            setId('heading'),
            setClass('primary'),
            icon(set('name', $icon))
        );

        /* Generate button with url. */
        if(!empty($url)) $container->add(btn($text, set('url', $url), setClass('primary')));
        else $container->add(h::span($text, setClass('text')));

        $this->add($container);

        return $this->children();
    }
}
