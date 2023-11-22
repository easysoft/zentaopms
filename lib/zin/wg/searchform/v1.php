<?php
declare(strict_types=1);
/**
 * The searchForm widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 搜索面板（searchForm）部件类
 * The searchForm widget class
 */
class searchForm extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'id?: string',                  // ID，可以与 searchToggle 配合使用。
        'module: string',               // 模块。
        'container?: string',           // 容器元素选择器，当被打开时在容器元素上显示类 show-search-form。
        'show?: boolean',               // 是否默认展示。
        'simple?: boolean',             // 是否为简单模式，不包含保存搜索条件和已保存的查询条件侧边栏。
        'setting?: array',              // 默认配置。
        'url?: string',                 // 配置加载地址，默认为 search-buildForm-module。
        'searchUrl?: string',           // 搜索时提交表单的 URL。
        'searchLoader?: string|array'   // 搜索时 loadPage 参数。
    );

    /**
     * Build widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        global $config;
        if(isset($config->zin->mode) && $config->zin->mode == 'compatible')
        {
            if(!$this->hasProp('url'))        $this->setProp('url', createLink('search', 'buildZinForm', 'module=' . $this->prop('module')));
            if(!$this->hasProp('searchUrl'))  $this->setProp('searchUrl', createLink('search', 'buildZinQuery'));
        }
        return zui::searchForm(inherit($this));
    }
}
