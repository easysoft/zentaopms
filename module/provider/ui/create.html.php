<?php
declare(strict_types=1);
/**
 * The create view file of provider module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     provider
 * @link        https://www.zentao.net
 */
namespace zin;
$requiresToken = in_array($type, array('GitLab', 'GitHub', 'Gitea', 'Gogs', 'Jenkins'));
$fields = defineFieldList('provider');
$fields->field('type')->required(true)->control('picker')->items($lang->provider->typeList)->value($type)->width('1/2')->wrapAfter(true);
$fields->field('name')->required(true)->width('1/2')->wrapAfter(true);
$fields->field('url')->required(true)->placeholder($type == 'Subversion' ? $lang->provider->notice->svnPath : '')->width('full');
$fields->field('account')->required(true)->width('1/2')->hidden($type != 'Jenkins');
$fields->field('token')->required($requiresToken)->control(array('control' => 'textarea', 'rows' => $type == 'Jenkins' ? 1 : 3))->hidden(!$requiresToken)->width($type == 'Jenkins' ? '1/2' : 'full');

$fields->autoLoad('type', 'token,account,url');

formGridPanel
(
    setID('createForm'),
    set::modeSwitcher(false),
    set::title($title),
    set::labelWidth($app->clientLang == 'zh-cn' ? '6em' : '10em'),
    set::fields($fields),
    set::loadUrl(createLink('provider', 'create', "type={type}&callback={$callback}"))
);
