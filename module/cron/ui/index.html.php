<?php
declare(strict_types=1);
/**
 * The index view file of cron module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     cron
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmTurnonMessage', $this->lang->cron->confirmTurnon);

foreach($crons as $cron)
{
    $actionsHtml = '';
    if(common::hasPriv('cron', 'toggle') and !empty($cron->command)) $actionsHtml .= html::a(inlink('toggle', "id=$cron->id&status=" . ($cron->status == 'stop' ? 'normal' :  'stop')), $cron->status == 'stop' ? $lang->cron->toggleList['start'] : $lang->cron->toggleList['stop'], 'hiddenwin');
    if(!empty($cron->command) and common::hasPriv('cron', 'edit'))   $actionsHtml .= html::a(inlink('edit', "id=$cron->id"), $lang->edit);
    if($cron->buildin == 0 and common::hasPriv('cron', 'delete'))    $actionsHtml .= html::a(inlink('delete', "id=$cron->id"), $lang->delete, 'hiddenwin');

    $cron->actions  = $actionsHtml;
    $cron->lastTime = substr($cron->lastTime, 2, 17);
}

panel
(
    set::title($lang->cron->list),
    set::headingActions(array
    (
        array('class' => 'mr-3 ajaxRefresh', 'url' => inlink('openProcess'), 'text' => $lang->cron->openProcess),
        array('class' => 'mr-3 ajaxTurnon',  'url' => '###', 'text' => $lang->cron->turnonList[0], 'onclick' => 'window.confirmTurnon()'),
        array('class' => 'mr-3 primary',     'url' => inlink('create'), 'text' => $lang->cron->create),
    )),
    !empty($config->global->cron) ? div
    (
        dtable
        (
            set::bordered(true),
            set::cols($config->cron->dtable->fieldList),
            set::data(array_values($crons)),
        ),
        div(setClass('alert secondary mt-5'), $lang->cron->notice->help)
    ) : div
    (
        html($lang->cron->introduction),
        !common::hasPriv('cron', 'turnon') ? null : html(sprintf($lang->cron->confirmOpen, inlink('turnon')))
    )
);

render();

