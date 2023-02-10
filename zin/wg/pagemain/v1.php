<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

use \zin\core\h5;

class pagemain extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'main');

    protected function acceptChild($child, $strAsHtml = false)
    {
        $child = parent::acceptChild($child, $strAsHtml);
        $this->appendTo('inner', $child);
    }

    protected function build($isPrint = false, $parent = null)
    {
        $builder = parent::build($isPrint, $parent);
        $builder->append(h5::div($this->blocks['inner'])->addClass('container'));
        return $builder;
    }
}
