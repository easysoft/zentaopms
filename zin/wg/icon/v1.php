<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'directive.func.php';

use zin\core\h;
use function zin\core\setClass;
use function zin\core\set;

class icon extends \zin\core\wg
{
    protected static $defineProps = 'name:string';

    protected function build($isPrint = false)
    {
        $iconName = $this->props->get('name', '');
        return h::i
        (
            setClass("icon icon-$iconName"),
            set($this->props->skip('name')),
            parent::build()
        );
    }

    public function addChild($child)
    {
        if(is_string($child) && !$this->props->has('name')) $this->props->set('name', $child);
        else parent::addChild($child);
    }
}
