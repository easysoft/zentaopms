<?php
declare(strict_types=1);
/**
 * The import view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Claude
 * @package     pipeline
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('repoID', $repoID);

btn
(
    set::icon('back'),
    setClass('btn secondary mr-2'),
    set::url($gobackLink),
    $lang->goback
);

formPanel
(
    set::title($lang->pipeline->importBtn),
    set::loadUrl(createLink('pipeline', 'import', "repoID={$repoID}&providerID={providerID}")),

    formGroup
    (
        set::width('2/3'),
        set::label($lang->pipeline->server),
        set::name('providerID'),
        set::required(true),
        set::control(array('type' => 'picker', 'items' => $providers)),
        set::value($defaultProviderID)
    ),
    on::change('[name=providerID]')->do("loadForm({target: 'form', partial: true, url: $.createLink('pipeline', 'import', 'repoID={$repoID}&providerID=') + $(this).val()})"),
    formGroup
    (
        set::width('2/3'),
        setClass($hidePipeline ? 'hidden' : ''),
        set::label($lang->pipeline->pipeline),
        set::name('pipeline'),
        set::required(!$hidePipeline),
        set::control(array('type' => 'picker', 'items' => $pipelines))
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->pipeline->pipelineName),
        set::name('name'),
        set::required(true),
        set::value($defaultName)
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->pipeline->desc),
        set::name('desc'),
        set::control(array('type' => 'textarea', 'rows' => 3))
    )
);

render();
