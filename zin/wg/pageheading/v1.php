<?php
namespace zin;

class pageHeading extends wg
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

        return $child;
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

        /* Generate button with url. */
        if(!empty($url)) $child = btn($text, set('url', $url), setClass('primary'));
        else $child = h::span($text, setClass('text'));

        return h::div
        (
            setId('heading'),
            setClass('primary'),
            icon(set('name', $icon)),
            $child
        );
    }
}
