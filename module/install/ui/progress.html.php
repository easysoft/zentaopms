<?php
declare(strict_types=1);
/**
 * The progress view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

if(!isInModal())
{
    set::zui(true);

    h::importCss($app->getWebRoot() . 'js/xterm/xterm.css');
    h::importJs($app->getWebRoot() . 'js/xterm/xterm.js');
}

$appList = array();
$components = json_decode($solution->components);
$order = 0;
foreach($components as $category => $cloudApp)
{
    $active    = (isset($cloudApp->status) && $cloudApp->status !='waiting') ? 'active' : '';
    if($order > 0) $appList[] = span(setClass("progress-arrow app-{$cloudApp->id} {$active}"), '→');
    $order ++;

    $appList[] = div
    (
        setClass("step app-{$cloudApp->id} $active"),
        div(setClass("step-no {$active}"), $order),
        div
        (
            setClass('step-title'),
            span(setID("{$cloudApp->alias}-status")),
            $cloudApp->alias
        )
    );
}

jsVar('shownLogs', array());
jsVar('startInstall', $install);
jsVar('solutionID ', $solution->id);
jsVar('notices', $lang->solution->notices);
jsVar('installLabel', $lang->solution->install);
jsVar('configLabel', $lang->solution->config);
jsVar('skipLang', $lang->install->solution->skip);
jsVar('backgroundLang', $lang->solution->background);
jsVar('isModal', helper::isAjaxRequest('modal'));

$actions = array();
if(!helper::isAjaxRequest('modal'))
{
    $actions[] = array(
        'text'  => $lang->solution->background,
        'href'  => createLink('install', 'step6'),
        'id'    => 'skipInstallBtn',
        'class' => 'btn primary hidden',
    );
}

$actions[] = array(
    'text'    => $lang->solution->retryInstall,
    'id'      => 'retryInstallBtn',
    'class'   => 'btn primary hidden',
    'onclick' => 'retryInstall(this)'
);

if(!helper::isAjaxRequest('modal'))
{
    $actions[] = array(
        'text'    => $lang->solution->cancelInstall,
        'id'      => 'cancelInstallBtn',
        'class'   => 'btn hidden',
        'onclick' => 'cancelInstall(this)'
    );
}

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        set('data-loading', $lang->solution->notices->uninstallingSolution),
        formPanel
        (
            setClass('bg-canvas m-auto mw-auto'),
            helper::isAjaxRequest('modal') ? null : set::title($lang->install->solution->progress),
            div
            (
                setID('terminal'),
                div(setClass('text-md font-bold pb-2'), $lang->install->solution->log)
            ),
            div
            (
                setClass('text-center app-list flex justify-center items-center'),
                $appList
            ),
            div(setClass('error-message text-warning text-center')),
            set::actions($actions)
        )
    )
);

if(!helper::isAjaxRequest('modal')) render('pagebase');
