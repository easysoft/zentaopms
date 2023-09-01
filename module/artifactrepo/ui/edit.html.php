<?php
declare(strict_types=1);
/**
 * The edit  view file of artifactrepo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     artifactrepo
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->artifactrepo->edit),
    set::actionsClass('w-2/3'),
    formGroup
    (
        set::width('2/3'),
        set::name('name'),
        set::required(true),
        set::label($lang->artifactrepo->name),
        set::value($artifactRepo->name),
    ),
    formGroup
    (
        set::width('2/3'),
        set::name('products[]'),
        set::label($lang->repo->product),
        set::control(array('type' => 'picker', 'multiple' => true)),
        set::items($products),
        set::value($artifactRepo->products),
    ),
    formGroup
    (
        set::width('2/3'),
        setClass('servers'),
        set::label($lang->artifactrepo->serverID),
        set::control('static'),
        set::value($artifactRepo->serverName),
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->artifactrepo->repoName),
        set::control('static'),
        set::value($artifactRepo->repoName),
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->artifactrepo->type),
        set::control('static'),
        set::value($artifactRepo->type),
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->artifactrepo->status),
        set::control('static'),
        set::value($artifactRepo->status),
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->artifactrepo->url),
        set::control('static'),
        set::value($artifactRepo->url),
    ),
);

render();

