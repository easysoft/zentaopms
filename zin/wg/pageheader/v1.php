<?php
namespace zin\wg;

use \zin\core\h5;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class pageheader extends \zin\core\wg
{
    static $tag = 'header';

    static $defaultProps = array('id' => 'header');

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
    protected function acceptChild($child, $strAsHtml = false)
    {
        if(class_exists('\zin\wg\pageheading') and $child instanceof \zin\wg\pageheading)
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
        $child = parent::acceptChild($child, $strAsHtml);

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
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $container = h5::div()->addClass('container');

        /* Heading. */
        $container->append($this->heading);

        /* Navigation Bar. */
        $container->append($this->navbar);

        /* Tool Bar. */
        $container->append($this->toolbar);

        $builder->append($container);

        return $builder;
    }
}
