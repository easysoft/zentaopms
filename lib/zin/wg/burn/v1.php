<?php
declare(strict_types=1);
/**
 * The burn widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 仪表盘（burn）部件类。
 * The burn widget class.
 *
 * @author Hao Sun
 */
class burn extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'data?: string|array',       // 数据源
        'referenceLine?: bool=false' // 参考线
    );

    /**
     * Build widget.
     */
    protected function build(): zui
    {
        return zui::burn(inherit($this));
    }
}
