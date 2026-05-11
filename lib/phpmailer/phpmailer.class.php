<?php
/**
 * ZenTaoPMS - Open-source project management system.
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *
 * A third-party license is embedded for some of the code in this file:
 * The use of the source code of this file is also subject to the terms
 * and consitions of the license of "PHPMailer" (LGPL, see
 * </lib/vendor/phpmailer/phpmailer/LICENSE>).
 */

require_once dirname(__FILE__, 2) . '/base/delegate/delegate.class.php';

class phpmailer extends baseDelegate
{
    protected static $className = 'PHPMailer\PHPMailer\PHPMailer';

    public function __construct($exceptions = null)
    {
        $this->instance = new static::$className($exceptions);

        // 修复 phpmailer 6.x SSL 验证失败的问题。详见https://www.php.net/manual/zh/context.ssl.php。Fix the SSL verification failure of phpmailer 6.x. See https://www.php.net/manual/en/context.ssl.php.
        $this->instance->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];

    }
}
