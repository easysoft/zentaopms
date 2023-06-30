<?php
declare(strict_types=1);
/**
 * The dropmenu widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 1.5 级导航（dropmenu）部件类。
 * The dropmenu widget class.
 *
 * @author Hao Sun
 */
class dropmenu extends wg
{
    /**
     * Define the properties.
     *
     * @var array
     * @access protected
     */
    protected static $defineProps = array
    (
        'url?:      string',        // 异步获取下拉菜单选项数据的 URL。
        'text?:     string',        // 选择按钮上显示的文本。
        'objectID?: string',        // 当前选中项的 ID。
        'cache?:    bool|int=true', // 是否启用缓存。
        'data?:     array',         // 手动指定数据。
    );

    /**
     * Override the build method.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        list($url, $text, $objectID, $cache) = $this->prop(array('url', 'text', 'objectID', 'cache'));
        return zui::dropmenu
        (
            set(array('fetcher' => $url, 'text' => $text, 'value' => $objectID, 'cache' => $cache)),
            set($this->getRestProps())
        );
    }
}
