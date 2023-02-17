<?php
namespace zin;

class pageheader extends wg
{
    static $customprops = 'heading,navbar,toolbar';

    public $heading;

    public $navbar;

    public $toolbar;

    /**
     * Accept child node.
     *
     * @param  object|string $child
     * @param  bool          $strAsHtml
     * @access protected
     * @return object|string
     */
    protected function onAddChild($child, $strAsHtml = false)
    {
        if(class_exists('\zin\pageheading') and $child instanceof \zin\pageheading)
        {
            $this->heading = $child;
            return NULL;
        }

        if(class_exists('\zin\wg\pagenavbar') and $child instanceof \zin\wg\pagenavbar)
        {
            $this->navbar = $child;
            return NULL;
        }

        if(class_exists('\zin\wg\pagetoolbar') and $child instanceof \zin\wg\pagetoolbar)
        {
            $this->toolbar = $child;
            return NULL;
        }

        /* Invoke parent method; */
        $child = parent::onAddChild($child, $strAsHtml);

        if(!$strAsHtml && is_string($child) && !$this->props->has('name'))
        {
            $this->prop('name', $child);
            return NULL;
        }

        return $child;
    }

    /**
     * Build builder.
     *
     * @param  bool      $isPrint
     * @param  object    $parent
     * @access protected
     * @return object
     */
    protected function build()
    {
        $container = h::div
        (
            setClass('container main-header')
        );

        /* Heading. */
        $container->add($this->heading);

        /* Navigation Bar. */
        $container->add($this->navbar);

        /* Tool Bar. */
        $container->add($this->toolbar);

        return h::create
        (
            'header',
            setId('header'),
            $container
        );
    }
}
