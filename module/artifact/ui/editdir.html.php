<?php
declare(strict_types=1);
/**
 * The edit dir view file of artifact module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('currentArtifactID', (string)$artifactLib->id);
jsVar('currentPathEncoded', $currentPathEncoded);
jsVar('currentParentPath', $parentPath);

formPanel
(
    set::title($title),
    set::labelWidth('100px'),
    set::submitBtnText($lang->artifact->okBtn),
    on::init()->call('loadParents'),
    on::change('[name=artifactID]')->call('loadParents'),
    formGroup
    (
        set::label($lang->artifact->artifactRepo),
        set::name('artifactID'),
        set::control(array('control' => 'picker', 'tree' => true)),
        set::required(true),
        set::items($artifactLibs),
        set::value($artifactLib->id)
    ),
    formGroup
    (
        set::label($lang->artifact->parent),
        set::name('parent'),
        set::control(array('control' => 'picker', 'tree' => true)),
        set::items(array()),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->artifact->dirName),
        set::name('name'),
        set::required(true),
        set::value($dirName)
    )
);
