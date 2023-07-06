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
        'id?:       string="dropmenu"', // ID，当页面有多个 dropmenu 时确保有唯一的 ID。
        'tab?:      string,',           // 应用名。
        'module?:   string,',           // 模块名。
        'method?:   string,',           // 方法名。
        'objectID?: string,',           // 对象 ID。
        'extra?:    string,',           // 额外参数。
        'url?:      string',            // 异步获取下拉菜单选项数据的 URL，如果已经指定 module， method，objectID，extra 等参数则可以忽略。
        'text?:     string',            // 选择按钮上显示的文本。
        'cache?:    bool|int=true',     // 是否启用缓存。
        'data?:     array',             // 手动指定数据。
    );

    /**
     * Override the build method.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        list($url, $text, $objectID, $cache, $tab, $module, $method, $objectID, $extra, $id, $data) = $this->prop(array('url', 'text', 'objectID', 'cache', 'tab', 'module', 'method', 'objectID', 'extra', 'id', 'data'));

        $app = data('app');

        if(empty($tab))      $tab    = $app->tab;
        if(empty($module))   $module = $app->moduleName;
        if(empty($method))   $method = $app->methodName;
        if(empty($extra))    $extra  = '';
        if(empty($objectID)) $objectID = data($module . 'ID');

        if(empty($url))
        {
            $url = createLink($tab, 'ajaxGetDropMenu', "objectID=$objectID&module=$module&method=$method&extra=$extra");
        }

        if(empty($text) && !empty($module))
        {
            $object = $app->control->loadModel($module)->getByID((int)$objectID);
            $text   = $object->name;
        }

        return zui::dropmenu
        (
            set('_id', $id),
            set('_id', $id ? $id : 'dropmenu'),
            set('data', $data),
            set('data-fetcher', $url),
            set(array('fetcher' => $url, 'text' => $text, 'defaultValue' => $objectID, 'cache' => $cache)),
            set($this->getRestProps())
        );
    }
}
