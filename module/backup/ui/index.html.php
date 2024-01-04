<?php
/**
 * The index view file of backup module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     backup
 * @link        https://www.zentao.net
 */
declare(strict_types=1);
namespace zin;

jsvar('backupLang', $lang->backup->common);
jsvar('rmPHPHeaderLang', $lang->backup->rmPHPHeader);
jsvar('restoreLang', $lang->backup->restore);
jsvar('alertTips', $lang->backup->insufficientDisk);
jsvar('backupError', empty($backupError) ? '' : $backupError);
jsvar('startBackup', $lang->backup->backup);
jsvar('getSpaceLoading', $lang->backup->getSpaceLoading);
jsvar('confirmDeleteLang', $lang->backup->confirmDelete);
jsvar('confirmRestoreLang', $lang->backup->confirmRestore);

featureBar
(
    li
    (
        set::className('nav-item'),
        a(set('data-app', $app->tab), $lang->backup->history)
    ),
    set::current('all')
);

/* Toolbar. */
$settingLink = $this->createLink('backup', 'setting');
$backupLink  = $this->createLink('backup', 'backup', "reload=yes");

if(common::hasPriv('backup', 'setting') and common::hasPriv('backup', 'backup'))
{
    toolbar
        (
            item(set
            ([
                'text'        => $lang->backup->setting,
                'icon'        => 'cog',
                'class'       => 'btn primary settings',
                'url'         => $settingLink,
                'data-toggle' => 'modal',
                'data-size' => 'sm'
            ])),
            item(set
            ([
                'text'      => $lang->backup->backup,
                'icon'      => 'copy',
                'class'     => 'btn primary backup load-indicator',
                'data-link' => $backupLink,
                'onClick'   => 'backup(this);'
            ]))
        );
}

/* DataTable. */
$cols = $this->loadModel('datatable')->getSetting('backup');
$data = initTableData($backups, $cols, $this->backup);

$rows = array();
foreach($data as $key => $row)
{
    $isOK  = true;
    $isPHP = false;
    foreach($row->files as $file => $attr)
    {
        if(str_ends_with($file, '.php'))    $isPHP = true;
        if($attr['allCount'] != $attr['count']) $isOk  = false;
    }

    $first = true;
    foreach($row->files as $file => $attr)
    {
        $fileName = basename($file);
        $backup   = new stdclass();
        $backup->file     = $file;
        $backup->allCount = $attr['allCount'];
        $backup->count    = $attr['count'];
        $backup->size     = helper::formatKB($attr['size']);
        $backup->status   = $attr['allCount'] == $attr['count'] ? $lang->backup->statusList['success'] : $lang->backup->statusList['fail'];
        $backup->name     = substr($fileName, 0, strpos($fileName, '.'));

        if($first)
        {
            $backup->time    = date(DT_DATETIME1, filemtime($file));
            $backup->rowspan = count($row->files);
            $backup->actions = $row->actions;
            if(!$isPHP) $backup->actions[0]['disabled'] = true;
            if(!$isOK)  $backup->actions[1]['disabled'] = true;
        }

        $rows[] = $backup;
        $first  = false;
    }
}

dtable
(
    set::customCols(false),
    set::cols($cols),
    set::data($rows),
    set::plugins(array('cellspan')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::footer(array('html' => $lang->backup->restoreTip . sprintf($lang->backup->holdDays, $config->backup->holdDays), 'className' => 'text-important'))
);

modalTrigger
(
    set(array('data-size' => 'sm', 'backdrop' => false)),
    modal
    (
        setID('waiting'),
        html($lang->backup->waitting),
        set::closeBtn(false),
        div
        (
            setID('message'),
            html(sprintf($lang->backup->progressSQL, 0))
        )
    )
);

render();
