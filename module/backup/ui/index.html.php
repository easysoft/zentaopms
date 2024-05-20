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
        set::className('nav-item text-lg'),
        $lang->backup->common
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

div
(
    setClass('panel-title'),
    $lang->system->backup->systemInfo
);

h::table
(
    set::className('table bordered'),
    h::thead
    (
        h::th($lang->system->backup->name),
        h::th($lang->system->backup->status),
        h::th($lang->system->backup->currentVersion),
        h::th($lang->system->backup->latestVersion),
        h::th($lang->actions),
    ),
    h::tbody
    (
        setClass('text-center'),
        h::td($systemInfo->name),
        h::td($systemInfo->status),
        h::td($systemInfo->currentVersion),
        h::td
        (
            $systemInfo->latestVersion,
            $systemInfo->upgradeHint ? a
            (
                set::href($systemInfo->latestURL),
                set::target('_blank'),
                icon('info pl-2 pt-2', set::title($systemInfo->upgradeHint))
            ): null
        ),
        h::td
        (
            set::className('actions-list center'),
            btnGroup
            (
                set::items
                (
                    array
                    (
                        array('class' => 'btn text-primary ghost', 'icon' => 'refresh', 'text' => $lang->backup->common , 'onclick' => 'backup(this)'),
                        array('class' => 'btn text-primary ghost', 'icon' => 'arrow-up', 'text' => $lang->system->backup->upgrade, 'onclick' => 'upgrade(this)', 'disabled' => $systemInfo->upgradeable ? true : true),
                    )
                )
            )
        ),
    )
);

div
(
    setClass('panel-title mt-6'),
    $lang->system->backup->history
);

if($this->config->inQuickon)
{
    $this->loadModel('system');

    foreach($backups as $backup)
    {
        $backup->backupPerson = isset($backup->sqlSummary['account']) ? $backup->sqlSummary['account'] : '';
        if(empty($backup->sqlSummary['backupType'])) $backup->sqlSummary['backupType'] = '';
        $backup->type = $backup->sqlSummary['backupType'];
    }

    $data = initTableData($backups, $config->system->dtable->backup->fieldList, $this->system);

    dtable
    (
        set::customCols(false),
        set::cols($config->system->dtable->backup->fieldList),
        set::data($data),
        set::plugins(array('cellspan')),
        set::getCellSpan(jsRaw('window.getCellSpan')),
        set::footer(array('html' => sprintf($lang->backup->holdDays, $config->backup->holdDays), 'className' => 'text-important'))
    );
}
else
{
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
            if(!isset($attr['allCount']) || !isset($attr['count']))
            {
                unset($row->files[$file]);
                continue;
            }

            if(str_ends_with($file, '.php'))        $isPHP = true;
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
}

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
);

render();
