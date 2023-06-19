<?php
declare(strict_types=1);
/**
 * The create view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    on::change('#product', 'onProductChange'),
    on::change('#SCM', 'onScmChange'),
    on::change('#serviceHost', 'onHostChange'),
    on::change('#serviceProject', 'onProjectChange'),
    set::title($lang->repo->createAction),
    formGroup
    (
        set::width("1/2"),
        set::name("product[]"),
        set::label($lang->story->product),
        set::required(true),
        set::control(array("type" => "picker","multiple" => true)),
        set::id("product"),
        set::items($products),
        set::value(empty($objectID) ? '' : array_keys($products))
    ),
    formGroup
    (
        set::width("1/2"),
        set::name("projects[]"),
        set::label($lang->repo->projects),
        set::control(array("type" => "picker","multiple" => true)),
        set::id("projects"),
        set::items($projects),
        set::value($relatedProjects)
    ),
    formGroup
    (
        set::width("1/2"),
        set::name("SCM"),
        set::label($lang->product->typeAB),
        set::required(true),
        set::value("Gitlab"),
        set::control("picker"),
        set::items($lang->repo->scmList)
    ),
    formRow
    (
        setClass('service hide'),
        formGroup
        (
            set::width("1/2"),
            set::name("serviceHost"),
            set::label($lang->repo->serviceHost),
            set::required(true),
            set::value(""),
            set::control("picker"),
            set::items($serviceHosts)
        ),
    ),
    formRow
    (
        setClass('service hide'),
        formGroup
        (
            set::width("1/2"),
            set::name("serviceProject"),
            set::label($lang->repo->serviceProject),
            set::required(true),
            set::control("picker"),
        ),
    ),
    formGroup
    (
        set::width("1/2"),
        set::name("name"),
        set::label($lang->user->name),
        set::required(true),
        set::control("text")
    ),
    formRow
    (
        setClass('hide-service hide-git'),
        formGroup
        (
            set::width("1/2"),
            set::name("path"),
            set::label($lang->repo->path),
            set::required(true),
            set::control("text")
        ),
    ),
    formGroup
    (
        set::width("1/2"),
        set::name("encoding"),
        set::label($lang->repo->encoding),
        set::required(true),
        set::value("utf-8"),
        set::control("text")
    ),
    formRow
    (
        setClass('hide-service'),
        formGroup
        (
            set::width("1/2"),
            set::name("client"),
            set::label($lang->repo->client),
            set::required(true),
            set::control("text")
        ),
    ),
    formRow
    (
        setClass('account-fields hide-service'),
        formGroup
        (
            set::width("1/2"),
            set::name("account"),
            set::label($lang->user->account),
            set::control("text")
        ),
    ),
    formRow
    (
        setClass('account-fields hide-service'),
        formGroup
        (
            set::width("1/2"),
            set::label($lang->user->password),
            inputGroup
            (
                control(set(array
                (
                    'name' => "password",
                    'id' => "password",
                    'value' => "",
                    'type' => "password"
                ))),
                control(set(array
                (
                    'name' => "encrypt",
                    'id' => "encrypt",
                    'value' => "base64",
                    'type' => "picker",
                    'items' => $lang->repo->encryptList
                )))
            )
        )
    ),
    formGroup
    (
        set::width("1/2"),
        set::label($lang->repo->acl),
        inputGroup
        (
            $lang->repo->group,
            control(set(array
            (
                'name' => "acl[groups][]",
                'id' => "aclgroups",
                'value' => NULL,
                'type' => "picker",
                'items' => $groups,
                'multiple' => true
            )))
        ),
        inputGroup
        (
            $lang->repo->user,
            control(set(array
            (
                'name' => "acl[users][]",
                'id' => "aclusers",
                'value' => NULL,
                'type' => "picker",
                'items' => $users,
                'multiple' => true
            ))),
            setClass('mt-2')
        )
    ),
    formGroup
    (
        set::width("2/3"),
        set::name("desc"),
        set::label($lang->story->spec),
        set::control("editor")
    )
);

render();

