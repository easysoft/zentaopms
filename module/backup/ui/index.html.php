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

jsvar('backup', $lang->backup->common);
jsvar('rmPHPHeader', $lang->backup->rmPHPHeader);
jsvar('confirmRestore', $lang->backup->confirmRestore);
jsvar('restore', $lang->backup->restore);
jsvar('backupTimeout', $lang->backup->error->timeout);

featureBar
(
    li
    (
        set::class('nav-item'),
        a
        (
            set('data-app', $app->tab),
            $lang->backup->history
        )
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
                'class'       => 'btn primary',
                'url'         => $settingLink,
                'data-toggle' => 'modal',
                'data-size' => 'sm'
            ])),
            item(set
            ([
                'text'      => $lang->backup->backup,
                'icon'      => 'copy',
                'class'     => 'btn primary backup',
                'data-link' => $backupLink,
                'onClick'   => 'backup(this);'
            ])),
        );
}

/* DataTable. */
$cols = $this->loadModel('datatable')->getSetting('backup');
$data = initTableData($backups, $cols, $this->backup);

foreach($data as $key => $row)
{
    $data[$key]->time     = date(DT_DATETIME1, filemtime(array_keys($row->files)[0]));
    $data[$key]->file     = dirname(array_keys($row->files)[0]) . DS . '{' . implode(',', array_map('basename', array_keys($row->files))) . '}';
    $data[$key]->count    = count($row->files);
    $data[$key]->allCount = array_sum(array_column($row->files, 'allCount'));
    $data[$key]->size     = helper::formatKB(array_sum(array_column($row->files, 'size')));

    $isOk = $data[$key]->isOK = true;
    $data[$key]->isPHP = false;
    foreach($row->files as $name => $file)
    {
        if(str_ends_with($name, '.php')) $data[$key]->isPHP  = true;
        if($file['allCount'] != $file['count'])
        {
            $isOk = $data[$key]->isOK = false;
            break;
        }
    }
    $data[$key]->status = $isOk ? $lang->backup->statusList['success'] : $lang->backup->statusList['fail'];
    if(!$data[$key]->isPHP) $data[$key]->actions[0]['disabled'] = true;
    if(!$data[$key]->isOK)  $data[$key]->actions[1]['disabled'] = true;
}

dtable
(
    set::customCols(false),
    set::cols($cols),
    set::data($data),
);

modalTrigger
(
    set(array('data-size' => 'sm', 'backdrop' => false)),
    modal
    (
        setID('waitting'),
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
