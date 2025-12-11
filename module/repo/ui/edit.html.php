<?php
declare(strict_types=1);
/**
 * The edit view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('pathGitTip', $lang->repo->example->path->git);
jsVar('pathSvnTip', $lang->repo->example->path->svn);
jsVar('clientGitTip', $lang->repo->example->client->git);
jsVar('clientSvnTip', $lang->repo->example->client->svn);
jsVar('scmList', $lang->repo->scmList);
jsVar('repoSCM', $repo->SCM);
jsVar('client', $repo->client);
$members = implode(',', zget($repo, 'members', array()));

formPanel
(
    set::title($lang->repo->edit),
    set::back('repo-maintain'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->product->typeAB),
            set::control("static"),
            set::value(zget($lang->repo->scmList, $repo->SCM, ''))
        ),
        formHidden('SCM', $repo->SCM),
        $repo->SCM == 'Git' ? h::span
        (
            setClass('tips-git leading-8 ml-2'),
            html($lang->repo->syncTips)
        ) : null
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("name"),
        set::label($lang->user->name),
        set::required(true),
        set::value($repo->name)
    ),
    formRow
    (
        setClass('hide-service hide-git'),
        formGroup
        (
            set::width('1/2'),
            set::name("path"),
            set::label($lang->repo->path),
            set::required(true),
            set::placeholder($lang->repo->example->path->git),
            set::value($repo->path)
        )
    ),
    formRow
    (
        ($config->inContainer || $config->inQuickon || $this->config->inCompose) ? setClass('hidden') : setClass('hide-service'),
        formGroup
        (
            set::width('1/2'),
            set::name("client"),
            set::label($lang->repo->client),
            set::required(true),
            set::value($repo->client)
        )
    ),
    formRow
    (
        setClass('account-fields hide-service'),
        formGroup
        (
            set::width('1/2'),
            set::name("account"),
            set::label($lang->user->account),
            set::required(true),
            set::value($repo->account)
        )
    ),
    formRow
    (
        setClass('account-fields hide-service'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->user->password),
            set::required(true),
            inputGroup
            (
                control(set(array
                (
                    'name' => "password",
                    'id' => "password",
                    'value' => $repo->password,
                    'type' => "password"
                ))),
                control(set(array
                (
                    'name' => "encrypt",
                    'id' => "encrypt",
                    'value' => $repo->encrypt,
                    'type' => "picker",
                    'items' => $lang->repo->encryptList
                )))
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("space"),
        set::label($lang->repo->space),
        set::required(true),
        set::items($spaces),
        set::value($repo->space)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("product[]"),
        set::label($lang->story->product),
        set::required(true),
        set::control(array("control" => "picker","multiple" => true)),
        set::items($products),
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
