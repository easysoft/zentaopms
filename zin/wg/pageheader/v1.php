<?php
namespace zin;

class pageheader extends wg
{
    /**
     * Add child.
     *
     * @param  object|string $child
     * @access protected
     * @return object|string
     */
    protected function onAddChild($child)
    {
        if(class_exists('zin\pageheading') and $child instanceof pageheading)
        {
            $this->add($child, 'heading');
            return false;
        }

        if(class_exists('\zin\pagenavbar') and $child instanceof \zin\pagenavbar)
        {
            $this->add($child, 'navbar');
            return false;
        }

        if(class_exists('\zin\pagetoolbar') and $child instanceof \zin\pagetoolbar)
        {
            $this->add($child, 'toolbar');
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
        return h::header
        (
            setId('header'),
            h::div
            (
                setClass('container main-header'),
                $this->block('heading'),
                $this->block('navbar'),
                $this->block('toolbar')
            )
        );
    }
}
