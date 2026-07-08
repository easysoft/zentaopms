<?php
declare(strict_types=1);
/**
 * The create view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
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
        set::url(createLink($app->tab, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
    );
}

formPanel
(
    on::change('#space')->call('loadMembers'),
    set::title($lang->repo->createRepoAction),
    formGroup
    (
        set::width('1/2'),
        set::name("name"),
        set::label($lang->repo->name),
        set::required(true),
    ),
    formGroup
    (
        setID('space'),
        set::width('1/2'),
        set::name("space"),
        set::label($lang->repo->space),
        set::required(true),
        set::items($spaces),
        !empty($spaceID) ? set::value($spaceID) : null
    ),
    formRow
    (
        $this->app->tab != 'devops' ? setClass('hidden') : null,
        formGroup
        (
            set::width('1/2'),
            set::name("product[]"),
            set::label($lang->story->product),
            set::required(true),
            set::control(array("control" => "picker","multiple" => true)),
            set::items($products),
            set::value(empty($objectID) ? '' : implode(',', array_keys($products)))
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name("desc"),
        set::label($lang->story->spec),
        set::control("input"),
        set::placeholder($lang->repo->descPlaceholder)
    ),
    formGroup
    (
        set::id('aclList'),
        set::width('1/2'),
        set::name('acl'),
        set::label($lang->repo->acl),
        set::control('radioList'),
        set::items($lang->repo->aclList),
        set::value('open'),
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
        set::multiple(true)
    )
);
