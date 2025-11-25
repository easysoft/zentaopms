<?php
/**
 * ZenTaoPMS - Open-source project management system.
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *
 * A third-party license is embedded for some of the code in this file:
 * The use of the source code of this file is also subject to the terms
 * and consitions of the license of "Captcha" (MIT, see
 * </lib/vendor/gregwar/captcha/LICENSE>).
 */

require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class captcha extends baseDelegate
{
    protected static $className = 'Gregwar\Captcha\CaptchaBuilder';

    public function __construct($phrase = null, ?PhraseBuilderInterface $builder = null)
    {
        $this->instance = new static::$className($phrase, $builder);
    }
}
