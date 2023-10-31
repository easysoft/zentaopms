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
    set::title($lang->repo->createRepoAction),
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
    formGroup
    (
        set::width('1/2'),
        set::name("namespace"),
        set::label($lang->repo->namespace),
        set::required(true),
        set::items($repoGroups),
        set::control("picker"),
    ),
    formRow
    (
        ($config->inContainer || $config->inQuickon) ? setClass('hidden') : setClass('hide-service'),
        set::style(array('display' => $server->type == 'gitlab' ? 'none' : 'flex')),
        formGroup
        (
            set::width('1/2'),
            set::name("client"),
            set::label($lang->repo->client),
            set::required(true),
            set::control("text"),
            set::placeholder($lang->repo->example->client->git),
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
