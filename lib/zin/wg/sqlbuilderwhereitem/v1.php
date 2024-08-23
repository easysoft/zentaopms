<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';

class sqlBuilderWhereItem extends wg
{
    protected static array $defineProps = array(
        'index?: int'
    );

    protected function build()
    {
        list($index) = $this->prop(array('index'));
        return null;
    }
}
