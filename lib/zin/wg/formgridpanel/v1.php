<?php
declare(strict_types=1);
/**
 * The formPanel widget class file of zin module of ZenTaoPMS.
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
 * 网格表单面板（formGrid）部件类。
 * The form grid panel widget class.
 *
 * @author Hao Sun
 */
class formGridPanel extends formPanel
{
    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defaultProps = array
    (
        'size'       => '',
        'layout'     => 'grid',
        'container'  => true,
        'class'   => 'panel-form page-form'
    );
}
