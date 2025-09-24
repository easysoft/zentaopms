<?php
require_once __DIR__ . '/h2d_htmlconverter.php';
require_once __DIR__ . '/simple_html_dom.php';
require_once __DIR__ . '/styles.php';
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpword extends baseDelegate
{
    protected static $className = 'PhpOffice\PhpWord\PhpWord';

    public function __construct()
    {
        $this->instance = new self::$className();
    }
}
