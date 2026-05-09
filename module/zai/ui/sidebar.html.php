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
    set::width(240),
    set::preserve('zaiSetting'),
    div
    (
        setClass('cell p-2.5 canvas'),
        menu
        (
            set::items($menuItems)
        )
    ),
    a
    (
        setClass('block cell pb-3 px-2.5 bg-canvas mt-4 rounded-md shadow border relative'),
        style::backgroundImage('linear-gradient(to bottom right, transparent 0%, #D9EAFF 100%), repeating-radial-gradient(circle at 0 0, transparent 0, #fff 27px), repeating-linear-gradient(#D9EAFF55, #D9EAFF)'),
        set::href(createLink('zai', 'promoteZentaoSkill')),
        toggle::modal(),
        div
        (
            style::paddingTop('148px'),
            style::background('no-repeat url(' . $config->webRoot . 'static/images/zentao-cli-skill.png) 6px 20px'),
            style::backgroundSize('203px'),
            html($lang->zai->zentaoSkillPromotion)
        )
    )
);
