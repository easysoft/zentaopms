<?php
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class btn extends wg
{
    static $tag = 'button';

    static $defaultProps = array('type' => 'button', 'class' => 'btn');
}
