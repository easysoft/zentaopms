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
jsVar('tab', $this->app->tab);
if($app->tab == 'execution') jsVar('objectID', $executionID);
if($app->tab == 'project')   jsVar('objectID', $projectID);
if($app->tab == 'qa')        jsVar('objectID', 0);

unset($lang->testcase->typeList['unit']);

$fields = useFields('testcase.create');

$autoLoad = array();
$autoLoad['product'] = 'branch,module,story,scene';
$autoLoad['branch']  = 'module,story,scene';
$autoLoad['module']  = 'story,scene';

formGridPanel
(
    set::title($lang->testcase->create),
    set::fields($fields),
    set::autoLoad($autoLoad),
    set::loadUrl(helper::createLink('testcase', 'create', "productID={product}&branch={branch}&moduleID={module}&from=&param=0&storyID={story}")),
    !empty($gobackLink) ? set::backUrl($gobackLink) : null,
    on::change('#scriptFile', 'readScriptContent'),
    on::change('#scriptFile', 'hideUploadScriptBtn'),
    on::click('#auto', 'checkScript'),
    on::click('.autoScript .file-delete', 'showUploadScriptBtn')
);
