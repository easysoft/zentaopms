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

if($this->app->tab != 'devops')
{
    dropmenu
    (
        set::module($app->tab),
        set::tab($app->tab),
        set::url(createLink($app->tab, 'ajaxGetDropMenuData', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
    );
}

jsVar('pathGitTip', $lang->repo->example->path->git);
jsVar('pathSvnTip', $lang->repo->example->path->svn);
jsVar('clientGitTip', $lang->repo->example->client->git);
jsVar('clientSvnTip', $lang->repo->example->client->svn);
jsVar('scmList', $lang->repo->scmList);

formPanel
(
    on::change('#product', 'onProductChange'),
    on::change('#SCM', 'onScmChange'),
    on::change('#serviceHost', 'onHostChange'),
    on::change('#serviceProject', 'onProjectChange'),
    set::title($lang->repo->createAction),
    set::back('GLOBAL'),
    formRow
    (
        $this->app->tab != 'devops' ? setClass('hidden') : null,
        formGroup
        (
            set::width('1/2'),
            set::name("product[]"),
            set::label($lang->story->product),
            set::required(true),
            set::control(array("type" => "picker","multiple" => true)),
            set::items($products),
            set::value(empty($objectID) ? '' : implode(',', array_keys($products)))
        ),
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("projects[]"),
        set::label($lang->repo->projects),
        set::control(array("type" => "picker","multiple" => true)),
        set::items($projects),
        set::value(empty($relatedProjects) ? '' : implode(',', array_values($relatedProjects)))
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::id('SCM'),
            set::name('SCM'),
            set::label($lang->product->typeAB),
            set::required(true),
            set::value('Gitlab'),
            set::control('picker'),
            set::items($lang->repo->scmList)
        ),
        h::span
        (
            setClass('tips-git leading-8 ml-2'),
            html($lang->repo->syncTips),
        ),
    ),
    formRow
    (
        setClass('service hide'),
        formGroup
        (
            set::width('1/2'),
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
        setClass('service hide service-project'),
        formGroup
        (
            set::width('1/2'),
            set::name("serviceProject"),
            set::items(array()),
            set::label($lang->repo->serviceProject),
            set::control("picker"),
        ),
    ),
    formGroup
    (
        set::width('1/2'),
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
            set::width('1/2'),
            set::name("path"),
            set::label($lang->repo->path),
            set::required(true),
            set::control("text"),
            set::placeholder($lang->repo->example->path->git),
        ),
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("encoding"),
        set::label($lang->repo->encoding),
        set::required(true),
        set::value("utf-8"),
        set::control("text"),
        set::placeholder($lang->repo->encodingsTips),
    ),
    formRow
    (
        ($config->inContainer || $config->inQuickon) ? setClass('hidden') : setClass('hide-service'),
        formGroup
        (
            set::width('1/2'),
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
            set::required(true),
            set::width('1/2'),
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
            set::required(true),
            set::width('1/2'),
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
        set::width('1/2'),
        set::name("desc"),
        set::label($lang->story->spec),
        set::control("input"),
        set::placeholder($lang->repo->descPlaceholder),
    ),
    formRow
    (
        set::id('aclList'),
        formGroup
        (
            set::width('1/2'),
            set::name('acl[acl]'),
            set::label($lang->repo->acl),
            set::control('radioList'),
            set::items($lang->repo->aclList),
            set::value('open'),
            on::change('onAclChange'),
        )
    ),
    formRow
    (
        set::id('whitelist'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->product->whitelist),
            inputGroup
            (
                $lang->repo->group,
                width('full'),
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
    ),
);

render();
