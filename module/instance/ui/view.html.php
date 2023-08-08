<?php
declare(strict_types=1);

/**
 * The appview view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     instance
 * @link        http://www.zentao.net
 */

namespace zin;

jsVar('instanceID', $instance->id);

$setting    = usePager('pager');
$cpuInfo    = $this->instance->printCpuUsage($instance, $instanceMetric->cpu, 'array');
$memoryInfo = $this->instance->printMemUsage($instance, $instanceMetric->memory, 'array');
$actions    = $this->loadModel('common')->buildOperateMenu($instance);

$dbListWg = array();
foreach($dbList as $db)
{
    $disabledClass = $db->ready && commonModel::hasPriv('instance', 'ajaxDBAuthUrl') ? '' : 'disabled';
    $dbListWg[] = h::tr
    (
        h::td($db->db_name),
        h::td($db->db_type),
        h::td
        (
            $db->ready ? $lang->instance->dbReady : $lang->instance->dbWaiting,
            setClass('text-' . ($db->ready ? 'success' : 'danger'))
        ),
        
        h::td
        (
            btn
            (
                $lang->instance->management,
                setClass('btn text-primary ghost ' .  $disabledClass),
                setData('dbname', $db->name),
                setData('dbtype', $db->db_type),
                setData('id',   $instance->id),
                on::click('openAdminer'),
            )
        ),
    );
}

detailHeader(
    to::prefix(''),
    to::title(''),
);
div
(
    setClass('flex flex-normal gap-x-5'),
    div
    (
        setClass('basis-2/3'),
        setID('instanceInfoContainer'),
        detailBody
        (
            sectionList
            (
                /* 应用名称信息图标区块 */
                section
                (
                    div
                    (
                        setClass('flex justify-between'),
                        div
                        (
                            setClass('flex basis-full'),
                            img(set::src($instance->logo), setStyle(array('width' => '50px', 'height' => '50px'))),
                            div
                            (
                                setClass('ml-3 flex col gap-y-1 basis-full'),
                                div
                                (
                                    $instance->name, setClass('text-xl'),
                                    span($cloudApp->app_version, setClass('ml-3 label lighter rounded-full'))
                                ),
                                div
                                (
                                    setClass('flex progress-container'),
                                    set::title($cpuInfo['tip']),
                                    icon('cog-outline text-' . $cpuInfo['color']),
                                    $lang->instance->cpuUsage,
                                    div
                                    (
                                        setClass('progress rounded-lg'),
                                        setStyle('background', "var(--color-{$cpuInfo['color']}-50)"),
                                        div
                                        (
                                            setClass('progress-bar ' . $cpuInfo['color']),
                                            set::role('progressbar'),
                                            setStyle('width', $cpuInfo['rate'])
                                        )
                                    ),
                                    icon('desktop text-' . $memoryInfo['color']),
                                    $lang->instance->memUsage,
                                    span
                                    (
                                        setClass('text-gray'),
                                        sprintf($lang->instance->memTotal, helper::formatKB($instanceMetric->memory->limit)),
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
                                    )
                                ),
                            ),
                        ),
                        btn
                        (
                            $lang->instance->setting,
                            setClass('btn ghost'),
                            set::disabled($instance->status != 'running'),
                            set::icon('backend'),
                            setData('toggle', 'modal'),
                            setData('size', 'sm'),
                            set::url(createLink('instance', 'setting', "id={$instance->id}")))
                    ),
                ),
                /* 基本信息区块 */
                section
                (
                    set::title($lang->instance->baseInfo),
                    h::table
                    (
                        setStyle('min-width', '700px'),
                        setClass('table w-auto max-w-full bordered mt-4'),
                        h::tr
                        (
                            h::th($lang->instance->status),
                            h::th($lang->instance->source),
                            // h::th($lang->instance->appTemplate),
                            h::th($lang->instance->installBy),
                            h::th($lang->instance->installAt),
                            h::th($lang->instance->runDuration),
                            $defaultAccount ? h::th($lang->instance->defaultAccount) : null,
                            $defaultAccount ? h::th($lang->instance->defaultPassword) : null,
                        ),
                        h::tr
                        (
                            h::td
                            (
                                setID('statusTD'),
                                setData('reload', in_array($instance->status, array('creating', 'initializing', 'pulling', 'startup', 'starting', 'suspending', 'installing', 'uninstalling', 'stopping', 'destroying', 'upgrading'))),
                                span
                                (
                                    setClass('label label-dot mr-1 ' . zget($this->lang->instance->htmlStatusesClass, $instance->status, ''))
                                ),
                                zget($this->lang->instance->statusList, $instance->status, ''), setClass('text-' . zget($this->lang->instance->htmlStatusesClass, $instance->status, ''))
                            ),
                            h::td(zget($lang->instance->sourceList, $instance->source, '')),
                            // h::td(a(set::href($this->createLink('store', 'appView', "id=$instance->appID")), $instance->appName)),
                            h::td(zget($users, $instance->createdBy, '')),
                            h::td(substr($instance->createdAt, 0, 16)),
                            h::td(common::printDuration($instance->runDuration)),
                            $defaultAccount ? h::td($defaultAccount->username) : null,
                            $defaultAccount ? h::td($defaultAccount->password) : null,
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
                        setClass('table w-auto max-w-full bordered mt-4'),
                        h::tr
                        (
                            h::th($lang->instance->dbName),
                            h::th($lang->instance->dbType, setStyle('width', '100px')),
                            h::th($lang->instance->status, setStyle('width', '100px')),
                            h::th($lang->instance->action, setStyle('width', '100px')),
                        ),
                        $dbListWg
                    )
                ),
            ),
            floatToolbar
            (
                set::object($instance),
                isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::class('ghost text-white'), $lang->goback, set::url(createLink('space', 'browse')))),
                set::main($actions['mainActions']),
                set::suffix($actions['suffixActions'])
            ),
        )
    ),
    div
    (
        setClass('basis-auto'),
        history
        (
            set::commentUrl(createLink('action', 'comment', array('objectType' => 'instance', 'objectID' => $instance->id))),
        )
    )
);