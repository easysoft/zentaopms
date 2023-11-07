<?php
declare(strict_types=1);
/**
 * The log view file of entry module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     entry
 * @link        https://www.zentao.net
 */
namespace zin;
featureBar
(
    entityLabel
    (
        set::text($entry->name),
        set::level(1)
    ),
    label
    (
        setClass('secondary size-sm mr-2'),
        $lang->entry->log
    )
);

toolbar
(
    btn
    (
        setClass('btn primary'),
        set::icon('cog'),
        set::url(helper::createLink('admin', 'log')),
        set(array('data-toggle' => 'modal', 'data-size' => 'sm')),
        $lang->entry->setting
    ),
    backBtn
    (
        set::back('GLOBAL'),
        $lang->goback
    )
);

panel
(
    setClass('p-0 plan-block'),
    set::bodyClass('p-0 no-shadow'),
    dtable
    (
        set::cols($this->config->entry->log->dtable->fieldList),
        set::data($logs),
        set::footPager(usePager())
    )
);

render();

