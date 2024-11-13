<?php
declare(strict_types=1);
/**
 * The detail view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */

namespace zin;
detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title(
        entityLabel(
            set(array('entityID' => $host->id, 'level' => 1, 'text' => $host->name, 'className' => 'clip', 'title' => $host->name))
        )
    )
);

$actions = $this->loadModel('common')->buildOperateMenu($host);

detailBody
(
    sectionList
    (
        h::table
        (
            setClass('table table-fixed canvas host-view-table'),
            h::tr
            (
                isset($host->CD) ? h::th($lang->host->CD) : null,
                isset($host->CD) ? h::td($lang->host->CDlist[$host->CD]) : null,
                h::th($lang->host->name),
                h::td($host->name)
            ),
            h::tr
            (
                h::th($lang->host->group),
                h::td(zget($optionMenu, $host->group)),
                h::th($lang->host->serverRoom),
                h::td(zget($rooms, $host->serverRoom, ""))
            ),
            zget($host, 'CD') == 'spug' ? h::tr
            (
                h::th($lang->host->admin),
                h::td(zget($host, 'admin')),
            ) : null,
            zget($host, 'CD') == 'spug' ?  h::tr
            (
                h::th($lang->host->password),
                h::td($lang->host->defaultPWD),
                h::th($lang->host->sshPort),
                h::td($host->sshPort)
            ) : null,
            h::tr
            (
                h::th($lang->host->cpuNumber),
                h::td($host->cpuNumber ? $host->cpuNumber : ''),
            ),
            h::tr
            (
                h::th($lang->host->memory),
                h::td($host->memory ? $host->memory . ' GB' : ''),
                h::th($lang->host->diskSize),
                h::td($host->diskSize ? $host->diskSize . ' GB' : '')
            ),
            h::tr
            (
                h::th($lang->host->intranet),
                h::td($host->intranet, set::title($host->intranet)),
                h::th($lang->host->extranet),
                h::td($host->extranet, set::title($host->extranet))
            ),
            h::tr
            (
                zget($host, 'CD') != 'spug' ? h::th($lang->host->osName) : null,
                zget($host, 'CD') != 'spug' ? h::td($host->osName) : null,
                h::th($lang->host->osVersion),
                h::td(zget($host, 'CD') != 'spug' ? zget($lang->host->{$host->osName.'List'}, $host->osVersion) : $host->osName)
            ),
            zget($host, 'CD') != 'spug' ? h::tr
            (
                h::th($lang->host->status),
                h::td($lang->host->statusList[$host->status]),
                h::th(),
                h::td()
            ) : null
        )
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'host', 'objectID' => $host->id)))
    ),
    floatToolbar
    (
        set::object($host),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::className('ghost text-white'), $lang->goback)),
        set::suffix($actions['suffixActions'])
    )
);
