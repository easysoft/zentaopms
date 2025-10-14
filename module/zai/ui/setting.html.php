<?php
/**
 * The zai setting view file of zai module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun<sunhao@chandao.com>
 * @package     zai
 * @link        https://www.zentao.net
 */
namespace zin;

$readonly = $mode != 'edit';

$fields = array();
$fields[] = field('host')->label($lang->zai->host)->readonly($readonly);
$fields[] = field('appID')->label($lang->zai->appID)->readonly($readonly);

if(!$readonly || !empty($setting->port))
{
    $fields[] = field('port')->label($lang->zai->port)->control('number')->readonly($readonly);
}

$fields[] = field('token')->label($lang->zai->token)->control($readonly ? 'password' : 'text')->readonly($readonly);
$fields[] = field('adminToken')->label($lang->zai->adminToken)->control($readonly ? 'password' : 'text')->readonly($readonly);

$actions = array();
if($readonly)
{
    if(empty($setting->host))
    {
        $actions[] = setting()->text($lang->zai->addSetting)->type('primary')->url('zai', 'setting', 'mode=edit')->toArray();
        $fields = array();
    }
    else
    {
        $actions[] = setting()->text($lang->edit)->type('primary')->url('zai', 'setting', 'mode=edit')->toArray();
    }
}
else
{
    $actions[] = 'submit';
    $actions[] = setting()->text($lang->cancel)->url('zai', 'setting')->toArray();
}


include './sidebar.html.php';

formPanel
(
    setCssVar('--zt-panel-form-max-width', '1400px'),
    set::formClass('max-w-lg', $readonly ? 'is-readonly' : ''),
    to::heading
    (
        div
        (
            setClass('panel-title text-lg'),
            span($lang->zai->setting),
            icon(setClass('text-warning'), 'help'),
            span
            (
                setStyle(array('font-weight' => 400)),
                setClass('text-gray-600 text-base'),
                html(sprintf($lang->zai->settingTips, $config->zai->installUrl))
            )
        )
    ),
    set::data($setting),
    set::fields($fields),
    set::actions($actions)
);
