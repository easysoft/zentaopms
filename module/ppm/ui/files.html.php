<?php
declare(strict_types=1);
/**
 * The files view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;
$domBox = div
(
    zui::diffHub
    (
        set::diffUrl($this->createLink('repo', 'ajaxGetDiffs', "repoID={$repoID}&fromVersion={$fromRevision}&toVersion={$toRevision}")),
        set::enableAnnotations(true),
        set::product(0),
        set::module(''),
        set::repoID($repoID),
        set::ppmID($ppm->id),
        set::labels($lang->ppm->diffHub->labels)
    )
);
