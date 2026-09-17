<?php
declare(strict_types=1);
/**
 * The edit file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('bug',                   $bug);
jsVar('confirmChangeProduct',  $lang->bug->notice->confirmChangeProduct);
jsVar('moduleID',              $bug->module);
jsVar('methodName',            $app->methodName);
jsVar('tab',                   $this->app->tab);
jsVar('released',              $lang->build->released);
jsVar('confirmUnlinkBuild',    sprintf($lang->bug->notice->confirmUnlinkBuild, zget($resolvedBuildPairs, $bug->resolvedBuild)));
jsVar('projectExecutionPairs', $projectExecutionPairs);
jsVar('edition',               $config->edition);
jsVar('isShadowProduct',       !empty($product->shadow));
jsVar('duplicateBugID',        !empty($bug->duplicateBug) ? $bug->duplicateBug : 0);

$fields = useFields('bug.edit');
detailForm
(
    on::change('[name="product"]',       'changeProduct'),
    on::change('[name="branch"]',        'changeBranch'),
    on::change('[name="project"]',       'changeProject'),
    on::change('[name="execution"]',     'changeExecution'),
    on::change('[name="module"]',        'changeModule'),
    on::change('[name="resolvedBuild"]', 'changeResolvedBuild'),
    on::change('[name="resolution"]',    'changeResolution'),
    on::click('#linkBug',   'linkBug'),
    on::click('#refresh',   'clickRefresh'),
    on::click('#allBuilds', 'loadAllBuilds'),
    on::click('#allUsers',  'loadAllUsers'),
    set::data($bug),
    set::fields($fields)
);
