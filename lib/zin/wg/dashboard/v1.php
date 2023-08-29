<?php
declare(strict_types=1);
/**
 * The dashboard widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 仪表盘（dashboard）部件类。
 * The dashboard widget class.
 *
 * @author Hao Sun
 */
class dashboard extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'id?: string',                        // ID。
        'cache?: bool|string',                // 是否启用缓存。
        'responsive?: bool',                  // 是否启用响应式。
        'blocks: array',                      // 区块列表。
        'grid?: int',                         // 栅格数。
        'gap?: int',                          // 间距。
        'leftStop?: int',                     // 区块水平停靠间隔。
        'cellHeight?: int',                   // 网格高度。
        'blockFetch?: string|function|array', // 区块数据获取 url 或选项。
        'blockDefaultSize?: array',           // 区块默认大小。
        'blockSizeMap?: array',                // 区块大小映射。
        'blockMenu?: array',                  // 区块菜单。
        'onLayoutChange?: function',          // 布局变更事件。
        'onClickMenu?: function'              // 布局变更事件。
    );

    static $dashboardID = 0;

    protected function created()
    {
        $this->setDefaultProps(array('id' => static::$dashboardID ? static::$dashboardID : 'dashboard', 'cache' => data('app.user.account')));
        static::$dashboardID++;
    }

    /**
     * Build widget.
     */
    protected function build(): wg
    {
        return zui::dashboard
        (
            set($this->props->skip(array('id'))),
            set('_id', $this->prop('id')),
            set('_props', $this->getRestProps())
        );
    }
}
