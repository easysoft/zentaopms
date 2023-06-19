<?php
declare(strict_types=1);
/**
 * The import view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items[] = array('name' => 'serviceProject', 'hidden' => true);
$items[] = array('name' => 'name_with_namespace', 'label' => $lang->repo->gitlabList, 'control' => 'static');
$items[] = array('name' => 'name', 'label' => $lang->repo->importName);
$items[] = array('name' => 'product', 'label' => $lang->repo->product, 'control' => array('type' => 'picker', 'multiple' => true), 'items' => $products);
$items[] = array('name' => 'projects', 'label' => $lang->repo->projects, 'control' => array('type' => 'picker', 'multiple' => true), 'items' => $projects);

foreach($repoList as $repo)
{
    $repo->serviceProject = $repo->id;
}

featureBar
(
    h::a
    (
        setClass('form-title'),
        set::href($this->createLink('repo', 'import')),
        $lang->repo->batchCreate,
    ),
    inputGroup
    (
        setClass('ml-3'),
        $lang->repo->importServer,
        select
        (
            on::change('selectServer()'),
            set::name('servers'),
            set::id('servers'),
            set::items($gitlabPairs),
            set::value($gitlab->id)
        )
    )
);

formBatchPanel
(
    set::id('repoList'),
    set::mode('add'),
    set::addRowIcon('false'),
    set::items($items),
    set::data($repoList),
    set::maxRows(count($repoList)),
    on::change('[data-name="product"]', 'loadProductProjects'),
);

render();
