<?php
declare(strict_types=1);
/**
 * The edit view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('scmList', $lang->repo->scmList);
$members = $repo->acl == 'private' ? array_keys(zget($repo, 'members', array())) : array();

formPanel
(
    set::title($lang->repo->edit),
    set::back('repo-maintain'),
    formGroup
    (
        set::width('1/2'),
        set::name("name"),
        set::label($lang->repo->name),
        set::value($repo->name),
        set::disabled(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("space"),
        set::label($lang->repo->space),
        set::required(true),
        set::value($repo->spaceID),
        set::disabled(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("product[]"),
        set::label($lang->story->product),
        set::required(true),
        set::items($products),
        set::multiple(true),
        set::value($repo->product)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("desc"),
        set::label($lang->story->spec),
        set::control("input"),
        set::placeholder($lang->repo->descPlaceholder),
        set::value(strip_tags($repo->desc))
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("defaultBranch"),
        set::label($lang->repo->defaultBranch),
        set::required(true),
        set::items($branchList),
        set::value($defaultBranch)
    ),
    formGroup
    (
        set::id('aclList'),
        set::width('1/2'),
        set::name('acl'),
        set::label($lang->repo->acl),
        set::control('radioList'),
        set::items($lang->repo->aclList),
        set::value($repo->acl),
        on::change('onAclChange')
    ),
    formGroup
    (
        setID('members'),
        setClass('hidden'),
        set::width('1/2'),
        set::name('members'),
        set::label($lang->repo->members),
        set::required(true),
        set::items($users),
        set::multiple(true),
        set::value($members)
    )
);
