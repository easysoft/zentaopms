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

$fields = defineFieldList('repo');
$fields->field('sourceRepo')->control(array('control' => 'formRowGroup', 'title' => $lang->repo->originRepo))->width('1/8')->wrapAfter(true);
$fields->field('origin')->required(true)->control('picker')->items($lang->repo->sourceList)->value($type)->width('1/2');
$fields->field('provider')->required(true)->width('1/2')
   ->control('inputGroup')
   ->itemBegin('providerID')->id('providerBox')->required(true)->control('picker')->items($providers)->itemEnd()
   ->itemBegin()->control(array('control' => 'btn', 'data-toggle' => 'modal', 'id' => 'createProvider', 'data-size' => 'lg'))
   ->text($lang->repo->create)->hint($lang->repo->create)
   ->url(createLink('provider', 'create', 'type=' . $type . "&callBack=refreshProvider"))
   ->itemEnd();
$fields->field('organize')->required(true)->control('picker')->items($groups)->width('1/2')->hidden($type == 'Subversion');
$fields->field('repo')->label($lang->repo->common)->required(true)->control('picker')->items($repos)->width('1/2')->wrapAfter(true)->hidden($type == 'Subversion');
$fields->field('account')->required(true)->width('1/2')->hidden($type != 'Subversion');
$fields->field('password')->required(true)->width('1/2')->hidden($type != 'Subversion');
$fields->field('repoPath')->required(true)->width('1/2')->wrapAfter(true)->hidden($type != 'Subversion')
   ->control('inputGroup')
   ->itemBegin('path')->required(true)->title(zget($provider, 'url'))->value(zget($provider, 'url'))->disabled(true)->itemEnd()
   ->itemBegin('slug')->itemEnd();

$fields->field('target')->label('')->control(array('control' => 'formRowGroup', 'title' => $lang->repo->targetRepo))->width('1/8')->wrapAfter(true);
$fields->field('name')->required(true)->width('1/2')->wrapAfter(true);
$fields->field('space')->required(true)->control('picker')->items($spaces)->width('1/2');
$fields->field('product')->required(true)->control('picker')->items($products)->multiple(true)->width('1/2');
$fields->field('desc')->control(array('control' => 'textarea', 'rows' => 2))->width('full');
$fields->field('mirror')->label($lang->repo->afterImport)->width('full')->control('radioList')->items($lang->repo->accessList)->value('writable');
$fields->field('acl')->width('full')->control('radioList')->items($lang->repo->aclList)->value('open');

$fields->autoLoad('origin', 'provider,organize,repo,account,password,repoPath');
$fields->autoLoad('providerID', 'organize,repo,account,password,repoPath');
$fields->autoLoad('organize', 'repo');

formGridPanel
(
    setID('createForm'),
    set::modeSwitcher(false),
    set::title($title),
    set::labelWidth($app->clientLang == 'zh-cn' ? '6em' : '10em'),
    set::fields($fields),
    set::loadUrl(createLink('repo', 'import', "type={origin}&providerID={providerID}&groupID={organize}")),
);
