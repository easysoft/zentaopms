<?php
declare(strict_types=1);
/**
 * The create file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

data('testcase', $case);
include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

$params = $app->getParams();
array_shift($params);
jsVar('createParams', http_build_query($params));
jsVar('tab', $this->app->tab);
if($app->tab == 'execution') jsVar('objectID', $executionID);
if($app->tab == 'project')   jsVar('objectID', $projectID);
if($app->tab == 'qa')        jsVar('objectID', 0);

unset($lang->testcase->typeList['unit']);

$fields = useFields('testcase.create');
$fields->autoLoad('product', 'product,branch,module,story,scene')
    ->autoLoad('branch', 'module,story,scene')
    ->autoLoad('module', 'story,scene');

formGridPanel
(
    set::modeSwitcher(false),
    set::title($lang->testcase->create),
    set::fields($fields),
    set::data($case),
    set::loadUrl(helper::createLink('testcase', 'create', "productID={product}&branch={branch}&moduleID={module}&from=$from&param=$param")),
    !empty($gobackLink) ? set::backUrl($gobackLink) : null,
    on::change('#story', 'changeStory'),
    on::change('[name=product]', 'loadProduct'),
    on::click('#auto', 'checkScript'),
);
