<?php
declare(strict_types=1);
/**
 * The import view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mazhiyuan
 * @package     pipeline
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('repoID', $repoID);

if($repo)
{
    dropmenu(set::objectID($repo->id), set::text($repo->name), set::tab('repo'));
}

$fields = defineFieldList('pipeline');

$fields->field('providerID')
    ->label($lang->pipeline->server)
    ->required(true)
    ->control('picker')
    ->items($providers)
    ->value($defaultProviderID)
    ->width('2/3');

$fields->field('pipeline')
    ->label($lang->pipeline->pipeline)
    ->required(!$hidePipeline)
    ->control('picker')
    ->items($pipelines)
    ->width('2/3')
    ->hidden($hidePipeline);

$fields->field('name')
    ->label($lang->pipeline->pipelineName)
    ->required(true)
    ->value($defaultName)
    ->width('2/3');

$fields->field('desc')
    ->label($lang->pipeline->desc)
    ->control(array('control' => 'textarea', 'rows' => 3))
    ->width('2/3');

$fields->autoLoad('providerID', 'pipeline,name,desc');

formGridPanel
(
    setID('importForm'),
    set::modeSwitcher(false),
    set::title($lang->pipeline->importBtn),
    set::labelWidth('8em'),
    set::fields($fields),
    set::loadUrl(createLink('pipeline', 'import', "repoID={$repoID}&providerID={providerID}")),
    on::change('[name=pipeline]', 'loadPipelineName'),
    on::change('[name=name]')->do('$(this).data("auto", false)'),
);
