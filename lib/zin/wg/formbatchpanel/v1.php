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
        'items?: array[]',      // 使用一个列定义对象数组来定义批量表单项。
        'minRows?: int',        // 最小显示的行数目。
        'maxRows?: int',        // 最多显示的行数目。
        'data?: array[]',       // 初始化行数据。
        'mode?: string',        // 批量操作模式，可以为 `'add'`（批量添加） 或 `'edit'`（批量编辑）。
        'actionsText?: string', // 操作列头部文本，如果不指定则使用 `$lang->actions` 的值。
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
