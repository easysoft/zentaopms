<?php
/**
 * ZenTaoPMS - Open-source project management system.
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *
 * A third-party license is embedded for some of the code in this file:
 * The use of the source code of this file is also subject to the terms
 * and consitions of the license of "spout" (Apache, see
 * </lib/vendor/box/spout/LICENSE>).
 */

require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class spout extends baseDelegate
{
    protected static $className = 'Box\Spout\Reader\Common\Creator\ReaderFactory';

    public function __construct($readerType = 'xlsx')
    {
        $this->instance = static::$className::createFromType($readerType);
    }
}
