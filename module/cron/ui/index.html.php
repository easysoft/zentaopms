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

foreach($crons as $cron)
{
    $actionsHtml = '';
    if(common::hasPriv('cron', 'toggle') and !empty($cron->command))
    {
        $toggleLink   = inlink('toggle', "id={$cron->id}&status=" . ($cron->status == 'stop' ? 'normal' :  'stop'));
        $toggleName   = $cron->status == 'stop' ? $lang->cron->toggleList['start'] : $lang->cron->toggleList['stop'];
        $actionsHtml .= html::a($toggleLink, $toggleName, '', "class='primary-500 ajax-submit'");
    }
    if(!empty($cron->command) and common::hasPriv('cron', 'edit')) $actionsHtml .= html::a(inlink('edit', "id={$cron->id}"), $lang->edit, '', "class='primary-500' data-toggle='modal'");
    if($cron->buildin == 0 and common::hasPriv('cron', 'delete'))  $actionsHtml .= html::a(inlink('delete', "id={$cron->id}"), $lang->delete, '', "class='primary-500 ajax-submit' data-confirm={$lang->cron->confirmDelete}");

    $cron->actions = $actionsHtml;
    if($cron->command == '') $cron->lastTime = '';
    if($cron->lastTime) $cron->lastTime = substr($cron->lastTime, 2, 17);
}

panel
(
    !empty($config->global->cron) ? set::title($lang->cron->list) : null,
    !empty($config->global->cron) ? null : setClass('leading-loose m-auto mt-0 mb-0 w-2/4'),
    !empty($config->global->cron) ? set::bodyClass('p-0') : null,
    !empty($config->global->cron) ? set::headingActions(array
    (
        array('class' => 'mr-3 ajax-submit', 'url' => inlink('ajaxExec', 'restart=1'), 'text' => $lang->cron->openProcess),
        array('class' => 'mr-3 ajaxTurnon ajax-submit', 'url' => inlink('turnon'), 'text' => $lang->cron->turnonList[0], 'data-confirm' => $this->lang->cron->confirmTurnon),
        array('class' => 'mr-3 primary', 'data-toggle' => 'modal', 'url' => inlink('create'), 'text' => $lang->cron->create)
    )) : null,
    !empty($config->global->cron) ? div
    (
        dtable
        (
            set::bordered(true),
            set::cols($config->cron->dtable->fieldList),
            set::data(array_values($crons)),
            set::footer(array('html' => $lang->cron->notice->help, 'className' => 'text-secondary'))
        )
    ) : div
    (
        html($lang->cron->introduction),
        !common::hasPriv('cron', 'turnon') ? null : html(sprintf($lang->cron->confirmOpen, inlink('turnon')))
    )
);

render();
