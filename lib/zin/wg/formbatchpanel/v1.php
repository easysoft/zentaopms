<?php
declare(strict_types=1);
/**
 * The formBatchPanel widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'formpanel' . DS . 'v1.php';

/**
 * 批量编辑表单面板（formBatchPanel）部件类。
 * The batch operate form panel widget class.
 *
 * @author Hao Sun
 */
class formBatchPanel extends formPanel
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static $defineProps = array
    (
        /* Min rows count */
        'minRows?: int',

        /* Max rows count */
        'maxRows?: int',

        /* Default rows data */
        'data?: array',

        /* Batch operation mode */
        'mode?: string'
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static $defaultProps = array
    (
        'batch' => true
    );
}
