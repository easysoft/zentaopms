<?php
declare(strict_types=1);
/**
 * The import view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;
$originValue = $type;
if(!empty($importRepo)) $originValue = zget($importRepo, 'origin', '');
$isFileSubversion = !empty($provider->type) && $provider->type == 'Subversion' && strpos($provider->url, 'file://') === 0;

$fields = defineFieldList('repo');
$fields->field('sourceRepo')->control(array('control' => 'formRowGroup', 'title' => $lang->repo->originRepo))->width('1/8')->wrapAfter(true);
$fields->field('origin')->required(true)->control('picker')->items($lang->repo->sourceList)->value($originValue)->width('1/2');
$fields->field('provider')->required(true)->width('1/2')
    ->control('inputGroup')
    ->itemBegin('providerID')->id('providerBox')->control('picker')->items($providers)->value(zget($importRepo, 'providerID', 0))->itemEnd()
    ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal', 'id' => 'createProvider', 'data-size' => 'lg'))
    ->text($lang->repo->create)->hint($lang->repo->create)
    ->url(createLink('provider', 'create', 'type=' . $type . "&callBack=refreshProvider"))
    ->itemEnd();
$fields->field('organize')->required(true)->control('picker')->items($groups)->width('1/2')->value(zget($importRepo, 'organize', ''))->hidden($type == 'Subversion');
$fields->field('repo')->label($lang->repo->common)
    ->control('picker')
    ->required(true)
    ->items($repos)
    ->value(zget($importRepo, 'repo', ''))
    ->width('1/2')
    ->wrapAfter(true)
    ->hidden($type == 'Subversion');
$fields->field('account')->required(true)->width('1/2')->value(zget($importRepo, 'account', ''))->hidden($type != 'Subversion' || $isFileSubversion);
$fields->field('password')->required(true)->width('1/2')->value(zget($importRepo, 'password', ''))->hidden($type != 'Subversion' || $isFileSubversion);
$fields->field('repoPath')->required(true)->width('1/2')->wrapAfter(true)->hidden($type != 'Subversion')
    ->control('inputGroup')
    ->itemBegin('path')->required(true)->title(zget($provider, 'url'))->value(!empty($importRepo) ? zget($importRepo, 'path') : zget($provider, 'url'))->disabled(true)->itemEnd()
    ->itemBegin('slug')->value(zget($importRepo, 'slug', ''))->itemEnd();

$fields->field('target')->label('')->control(array('control' => 'formRowGroup', 'title' => $lang->repo->targetRepo))->width('1/8')->wrapAfter(true);
$fields->field('name')->required(true)->width('1/2')->value(zget($importRepo, 'name', ''))->wrapAfter(true);
$fields->field('space')->required(true)->control('picker')->items($spaces)->value(!empty($importRepo) ? zget($importRepo, 'space') : $spaceID)->width('1/2');
$fields->field('product')->required(true)->control('picker')->items($products)->multiple(true)->value(zget($importRepo, 'product', ''))->width('1/2');
$fields->field('desc')->control(array('control' => 'textarea', 'rows' => 2))->value(zget($importRepo, 'desc', ''))->width('full');

$mirrorValue = !empty($importRepo) ? zget($importRepo, 'mirror') : 'writable';
if($type == 'Subversion') $mirrorValue = 'readonly';
$fields->field('mirror')->label($lang->repo->afterImport)
    ->width('full')
    ->control('radioList')
    ->items($lang->repo->accessList)
    ->hidden($type == 'Subversion')
    ->value($mirrorValue);
$fields->field('acl')->width('full')->control('radioList')->items($lang->repo->aclList)->value(!empty($importRepo) ? zget($importRepo, 'acl') : 'open');

$fields->autoLoad('origin', 'provider,organize,repo,account,password,repoPath,mirror');
$fields->autoLoad('providerID', 'organize,repo,account,password,repoPath');
$fields->autoLoad('organize', 'repo');

formGridPanel
(
    setID('createForm'),
    on::change('[name=repo]', 'loadName'),
    set::modeSwitcher(false),
    set::title($title),
    set::labelWidth($app->clientLang == 'zh-cn' ? '6em' : '10em'),
    set::fields($fields),
    set::loadUrl(createLink('repo', 'import', "spaceID={$spaceID}&type={origin}&providerID={providerID}&groupID={organize|urlencode}")),
);
