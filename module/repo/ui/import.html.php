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
$items[] = array('name' => 'no', 'label' => $lang->user->abbr->id, 'control' => 'static', 'width' => '32px', 'class' => 'no');
$items[] = array('name' => 'serviceProject', 'label' => '', 'hidden' => true);
$items[] = array('name' => $defaultServer->type == 'gitlab' ? 'name_with_namespace' : 'path', 'label' => $lang->repo->repo, 'control' => 'static', 'width' => '264px');
$items[] = array('name' => $defaultServer->type == 'gitlab' ? 'name' : 'identifier', 'label' => $lang->repo->importName);
$items[] = array('name' => 'product', 'label' => $lang->repo->product, 'control' => array('control' => 'picker', 'multiple' => true), 'items' => $products);
$items[] = array('name' => 'actions', 'label' => $lang->actions, 'control' => array('control' => 'btn', 'className' => 'ghost hideRepo', 'icon'  => 'eye-off', 'hint'  => $this->lang->repo->hide));

$no = 1;
foreach($repoList as $repo)
{
    $repo->serviceProject = $repo->id;
    $repo->no             = $no ++;
}

\zin\featureBar
(
    h::a
    (
        setClass('form-title'),
        set::href($this->createLink('repo', 'import')),
        $lang->repo->batchCreate
    ),
    picker
    (
        setClass('ml-3'),
        width('200px'),
        on::change('selectServer'),
        set::name('servers'),
        set::id('servers'),
        set::placeholder($lang->repo->importServer),
        set::items(array('' => '') + $servers),
        set::value($defaultServer->id)
    ),
    li(
        setClass('ml-2'),
        checkbox
        (
            on::change('window.toggleShowRepo'),
            $this->lang->repo->showHidden
        )
    )
);

jsVar('serverID', $defaultServer->id);
jsVar('hiddenRepos', $hiddenRepos);
jsVar('hideLang', $this->lang->repo->hide);
jsVar('showLang', $this->lang->repo->show);
formBatchPanel
(
    h::input
    (
        set::type('hidden'),
        set::name('serviceHost'),
        set::value($defaultServer->id)
    ),
    set::id('repoList'),
    set::back('repo-maintain'),
    set::mode('edit'),
    set::addRowIcon('false'),
    set::deleteRowIcon('icon-eye-off'),
    set::items($items),
    set::data($repoList),
    set::maxRows(count($repoList)),
    set::onRenderRowCol(jsRaw('window.onRenderRowCol')),
    on::click('.hideRepo', 'window.hideRepo'),
    on::click('.showRepo', 'window.showRepo'),
);

render();
