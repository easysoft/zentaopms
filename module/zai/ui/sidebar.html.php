<?php
/**
 * The zai setting sidebar view file of zai module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun<sunhao@chandao.com>
 * @package     zai
 * @link        https://www.zentao.net
 */
namespace zin;

$methodName = $app->methodName;
$menuItems  = array();
$menuItems[] = setting()->text($lang->zai->setting)->icon('cog-outline')->url('zai', 'setting', $methodName == 'setting' ? "mode=$mode" : '')->selected($methodName == 'setting')->toArray();

if(hasPriv('zai', 'vectorized'))
{
    $menuItems[] = setting()->text($lang->zai->vectorized)->icon('db')->url('zai', 'vectorized')->selected($methodName == 'vectorized')->toArray();
}

sidebar
(
    set::toggleBtn(false),
    set::preserve('zaiSetting'),
    div
    (
        setClass('cell p-2.5 bg-white'),
        menu
        (
            set::items($menuItems)
        )
    )
);
