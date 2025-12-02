<?php
/**
 * ZenTaoPMS - Open-source project management system.
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *
 * A third-party license is embedded for some of the code in this file:
 * The use of the source code of this file is also subject to the terms
 * and consitions of the license of "PHPWord" (LGPL, see
 * </lib/vendor/phpoffice/phpword/COPYING>).
 */

require_once __DIR__ . '/h2d_htmlconverter.php';
require_once __DIR__ . '/simple_html_dom.php';
require_once __DIR__ . '/styles.php';
require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpword extends baseDelegate
{
    protected static $className = 'PhpOffice\PhpWord\PhpWord';

    public function __construct()
    {
        $this->instance = new static::$className();
    }
}
