<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class icon extends \zin\core\wg
{
    static $tag = 'i';

    static $defaultProps = array('class' => 'icon');

    static $customProps = 'name';

    protected function acceptChild($child, $strAsHtml = false)
    {
        $child = parent::acceptChild($child, $strAsHtml);

        if(!$strAsHtml && is_string($child) && !$this->props->has('name'))
        {
            $this->prop('name', $child);
            return NULL;
        }
        return $child;
    }

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        if($this->props->has('name'))
        {
            $builder->props->set('class', 'icon-' . $this->props->get('name'));
        }

        return $builder;
    }
}
