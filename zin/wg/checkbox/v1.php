<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

use \zin\core\h5;

use function zin\set;

class checkbox extends \zin\core\wg
{
    static $tag = 'label';

    static $defaultProps = array('class' => 'checkbox');

    static $customProps = 'text,checked';

    static function create($props)
    {
        $checkbox = new checkbox();
        foreach($props as $key => $value) $checkbox->prop($key, $value);
        return $checkbox;
    }

    protected function build($isPrint = false, $parent = null)
    {
        $builder = parent::build($isPrint, $parent);

        $input = h5::input(set('type', 'checkbox'));
        if ($this->prop('checked')) $input->append(set('checked', 'true'));

        $builder->append($input);
        $builder->append($this->prop('text'));

        return $builder;
    }
}
