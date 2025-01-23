<?php
declare(strict_types=1);

/**
 * The appview view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('copied',           $lang->instance->copied);
jsVar('instanceID',       $instance->id);
jsVar('instanceStatus',   $instance->status);
jsVar('instanceType',     $type);
jsVar('inQuickon',        $config->inQuickon);
jsVar('confirmBackupTip', $lang->instance->backup->confirmTip);

if(empty($instance->externalID)) $instance->externalID = 0;
$instance->appName = strtolower($instance->appName);
$cpuInfo    = $this->instance->printCpuUsage($instance, (object)$instanceMetric->cpu);
$memoryInfo = $this->instance->printStorageUsage($instance, (object)$instanceMetric->memory);
$volumeInfo = $this->instance->printStorageUsage($instance, (object)$instanceMetric->disk);
$actions    = $this->loadModel('common')->buildOperateMenu($instance);

if($type !== 'store')
{
    $defaultAccount = new stdclass();
    $defaultAccount->username = $instance->account;
    $defaultAccount->password = $instance->password;
    $defaultAccount->token    = $instance->token;

    $lang->instance->defaultAccount  = $lang->instance->account;
    $lang->instance->defaultPassword = $lang->instance->password;
}

if(!empty($actions['suffixActions']))
{
    foreach($actions['suffixActions'] as $suffix => &$action)
    {
        if($type == 'store' && $action['icon'] == 'trash' && !empty($action['data-confirm']['message'])) $action['data-confirm']['message'] = $lang->instance->notices['confirmUninstallStoreApp'];
    }
}
$dbListWg = array();
foreach($dbList as $db)
{
    $disabledClass = $db->ready && commonModel::hasPriv('instance', 'manage') ? '' : 'disabled';
    $dbListWg[] = h::tr
    (
        h::td($db->db_name),
        h::td($db->db_type),
        h::td
        (
            setID('dbStatusTD'),
            $db->ready ? $lang->instance->dbReady : $lang->instance->dbWaiting,
            setClass('text-' . ($db->ready ? 'success' : 'danger'))
        ),

        h::td
        (
            btn
            (
                $lang->instance->management,
                setClass('btn text-primary ghost db-management ' .  $disabledClass),
                setData('dbname', $db->name),
                setData('dbtype', $db->db_type),
                setData('id',   $instance->id),
                on::click()->call('loadDBAuthUrl', jsRaw('this'))
            )
        )
    );
}

$hideOperate = !in_array($instance->appName, array('gitlab', 'sonarqube'));
if($instance->appName == 'gitlab' && !$app->user->admin)
{
    $openID = $this->loadModel('pipeline')->getOpenIdByAccount($instance->externalID, 'gitlab', $app->user->account);
    if(!$openID) $hideOperate = true;
}

detailHeader(
    to::prefix(''),
    to::title(''),
);
div
(
    setClass('flex flex-normal gap-x-5 justify-center'),
    on::click('.copy-btn')->call('copyText', jsRaw('this')),
    div
    (
        setClass('flex-none w-2/3'),
        setID('instanceInfoContainer'),
        detailBody
        (
            sectionList
            (
                /* 应用名称信息图标区块 */
                section
                (
                    set::title(''),
                    div
                    (
                        setClass('flex justify-between'),
                        div
                        (
                            setClass('flex basis-full'),
                            $type === 'store' && !empty($instance->logo) ? img(set::src($instance->logo), setStyle(array('width' => '50px', 'height' => '50px'))) : null,
                            div
                            (
                                setClass(($type === 'store' ? 'ml-3' : '') . ' flex col gap-y-1 basis-full'),
                                div
                                (
                                    $instance->name, setClass('text-xl'),
                                    $type === 'store' ? span(setID("appVersion"), $instance->appVersion, setClass('ml-3 label gray-pale rounded-full')) : null
                                ),
                                $type === 'store' ? div
                                (
                                    setID('systemLoad'),
                                    setClass('flex progress-container'),
                                    icon('cpu text-' . $cpuInfo['color']),
                                    $lang->instance->cpuUsage,
                                    div
                                    (
                                        setClass('progress rounded-lg'),
                                        set::title($cpuInfo['tip']),
                                        setStyle('background', "var(--color-{$cpuInfo['color']}-50)"),
                                        div
                                        (
                                            setClass('progress-bar ' . $cpuInfo['color']),
                                            set::role('progressbar'),
                                            setStyle('width', $cpuInfo['rate'])
                                        )
                                    ),
                                    icon('memory text-' . $memoryInfo['color']),
                                    $lang->instance->memUsage,
                                    span
                                    (
                                        setClass('text-gray'),
                                        sprintf($lang->instance->memTotal, helper::formatKB($instanceMetric->memory->limit))
                                    ),
                                    div
                                    (
                                        setClass('progress rounded-lg'),
                                        set::title($memoryInfo['tip']),
                                        setStyle('background', "var(--color-{$memoryInfo['color']}-50)"),
                                        div
                                        (
                                            setID('memoryRate'),
                                            set::role('progressbar'),
                                            setData('load', $instance->status == 'running' && $memoryInfo['rate'] == '0%'),
                                            setClass('progress-bar ' . $memoryInfo['color']),
                                            setStyle('width', $memoryInfo['rate'])
                                        )
                                    ),
                                    !empty($diskSettings->resizable) ? icon('db text-' . $volumeInfo['color']) : null,
                                    !empty($diskSettings->resizable) ? $lang->instance->volUsage: null,
                                    !empty($diskSettings->resizable) ? span
                                    (
                                        setClass('text-gray'),
                                        sprintf($lang->instance->volTotal, helper::formatKB($instanceMetric->disk->limit))
                                    ) : null,
                                    !empty($diskSettings->resizable) ? div
                                    (
                                        setClass('progress rounded-lg'),
                                        set::title($volumeInfo['tip']),
                                        setStyle('background', "var(--color-{$volumeInfo['color']}-50)"),
                                        div
                                        (
                                            setID('volumeRate'),
                                            set::role('progressbar'),
                                            setData('load', $instance->status == 'running' && $volumeInfo['rate'] == '0%'),
                                            setClass('progress-bar ' . $volumeInfo['color']),
                                            setStyle('width', $volumeInfo['rate'])
                                        )
                                    ) : null
                                ) : null
                            )
                        ),
                        $type !== 'store' ? null : btn
                        (
                            $lang->instance->setting,
                            setClass('btn ghost'),
                            set::disabled(in_array($instance->status, array('installing', 'initializing', 'uninstalling', 'destroying'))),
                            set::id('setting'),
                            set::icon('backend'),
                            setData('toggle', 'modal'),
                            setData('size', 'sm'),
                            set::url(createLink('instance', 'setting', "id={$instance->id}")))
                    )
                ),
                /* 基本信息区块 */
                section
                (
                    set::title($lang->instance->baseInfo),
                    h::table
                    (
                        setStyle('min-width', '700px'),
                        setClass('table w-full max-w-full bordered mt-4 text-center store-info'),
                        h::tr
                        (
                            $type !== 'store' ? null : h::th($lang->instance->status),
                            h::th($lang->instance->source),
                            // h::th($lang->instance->appTemplate),
                            h::th($lang->instance->installBy),
                            h::th($lang->instance->installAt),
                            $type !== 'store' ? null : h::th($lang->instance->runDuration),
                            !empty($defaultAccount->username) ? h::th($lang->instance->defaultAccount) : null,
                            !empty($defaultAccount->password) ? h::th($lang->instance->defaultPassword) : null,
                            !empty($defaultAccount->token)    ? h::th($lang->instance->token) : null,
                            $hideOperate ? null : h::th($lang->instance->browseProject)
                        ),
                        h::tr
                        (
                            $type !== 'store' ? null : h::td
                            (
                                setID('statusTD'),
                                setData('status', $instance->status),
                                setData('reload', in_array($instance->status, array('creating', 'initializing', 'pulling', 'startup', 'starting', 'suspending', 'installing', 'uninstalling', 'stopping', 'destroying', 'upgrading'))),
                                span
                                (
                                    setClass('label label-dot mx-1 ' . zget($this->lang->instance->htmlStatusesClass, $instance->status, ''))
                                ),
                                zget($this->lang->instance->statusList, $instance->status, ''), setClass('text-' . zget($this->lang->instance->htmlStatusesClass, $instance->status, ''))
                            ),
                            h::td(zget($lang->instance->sourceList, $instance->source, '')),
                            // h::td(a(set::href($this->createLink('store', 'appView', "id=$instance->appID")), $instance->appName)),
                            h::td(zget($users, $instance->createdBy, '')),
                            h::td(substr($instance->createdAt, 0, 16)),
                            $type !== 'store' ? null : h::td(common::printDuration($instance->runDuration)),
                            !empty($defaultAccount->username) ? h::td($defaultAccount->username) : null,
                            !empty($defaultAccount->password) ? h::td
                            (
                                input(set::type('text'), set::value($defaultAccount->password), set::name('password'), setStyle('display', 'none')),
                                btn(set::className('copy-btn ghost'),set::icon('copy'))
                            ): null,
                            !empty($defaultAccount->token)    ? h::td
                            (
                                input(set::type('text'), set::value($defaultAccount->token), set::name('token'), setStyle('display', 'none')),
                                btn(set::className('copy-btn ghost'),set::icon('copy'))
                            ): null,
                            $hideOperate ? null : h::td
                            (
                                btn
                                (
                                    $lang->instance->management,
                                    setClass('btn text-primary ghost'),
                                    set::disabled($instance->type === 'store' && $instance->status != 'running'),
                                    set::url(createLink($instance->appName, 'browseProject', "{$instance->appName}ID={$instance->externalID}"))
                                )
                            )
                        )
                    )
                ),
                /* 数据库区块 */
                empty($dbList) ? null : section
                (
                    set::title($lang->instance->dbList),
                    h::table
                    (
                        setStyle('min-width', '700px'),
                        setClass('table w-full max-w-full bordered mt-4 text-center store-info'),
                        h::tr
                        (
                            h::th($lang->instance->dbName),
                            h::th($lang->instance->dbType, setStyle('width', '100px')),
                            h::th($lang->instance->status, setStyle('width', '100px')),
                            h::th($lang->instance->action, setStyle('width', '100px'))
                        ),
                        $dbListWg
                    )
                ),
                /* 应用实例备份块。 */
                /* Instance Backup block. */
                ($config->inQuickon && $type == 'store' && $instance->name != 'ZenTao') ? section(
                    set::title($lang->instance->backup->common),
                    set::id('backupSection'),
                    div(
                        setClass('flex justify-end'),
                        btn(
                            set(array
                            (
                                'icon'      => 'refresh',
                                'class'     => 'ghost btn',
                                'text'      => $lang->instance->backup->operators['manual'] ,
                                'data-size' => 'sm',
                                'onclick'   => "onManualBackup({$instance->id})"
                            ))
                        ),
                        btn(
                            set(array
                            (
                                'icon'        => 'backend',
                                'class'       => 'ghost btn',
                                'text'        => $lang->instance->backup->operators['settings'] ,
                                'url'         => createLink('instance', 'backupSettings', "id={$instance->id}"),
                                'data-size'   => 'sm',
                                'data-toggle' => 'modal'
                            ))
                        ),
                    ),
                    div
                    (
                        setID('backupList'),
                        h::js("if(!$('#backupList').find('.dtable-header-cell').length) loadTarget($.createLink('instance', 'backupList', 'id={$instance->id}'), '#backupList');"),
                    )
                ) : null,
            ),
            floatToolbar
            (
                set::object($instance),
                isAjaxRequest('modal') ? null : to::prefix(backBtn(
                    set::icon('back'),
                    set::className('ghost text-white'),
                    set::url(createLink('space', 'browse')),
                    $lang->goback
                )),
                set::main($actions['mainActions']),
                set::suffix($actions['suffixActions'])
            )
        )
    ),
    div
    (
        setClass('w-1/3'),
        history
        (
            set::objectID($instance->id),
            set::objectType($type === 'store' ? 'instance' : $instance->type),
            set::commentUrl(createLink('action', 'comment', array('objectType' => $type === 'store' ? 'instance' : $instance->type, 'objectID' => $instance->id)))
        )
    )
);
