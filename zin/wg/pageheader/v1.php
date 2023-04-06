<?php
namespace zin;

class pageHeader extends wg
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
        if(class_exists('zin\pageHeading') and $child instanceof pageHeading)
        {
            $this->add($child, 'heading');
            return false;
        }

        if(class_exists('\zin\pageNavbar') and $child instanceof \zin\pageNavbar)
        {
            $this->add($child, 'navbar');
            return false;
        }

        if(class_exists('\zin\pageToolbar') and $child instanceof \zin\pageToolbar)
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
