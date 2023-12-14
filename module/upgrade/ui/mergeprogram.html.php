<?php
declare(strict_types=1);
/**
 * The mergeprogram view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('today', date('Y-m-d'));
jsVar('weekend', $config->execution->weekend);
jsVar('type', $type);
jsVar('mode', $systemMode);
jsVar('projectType', $projectType);
jsVar('errorNoProduct', $lang->upgrade->errorNoProduct);
jsVar('errorNoExecution', $lang->upgrade->errorNoExecution);

set::zui(true);
if($type == 'productline')                   include_once('mergebyline.html.php');
if($type == 'product')                       include_once('mergebyproduct.html.php');
if($type == 'sprint' || $type == 'moreLink') include_once('mergebysprint.html.php');

$content = '';
if($noMergedProductCount) $content .= sprintf($lang->upgrade->productCount, $noMergedProductCount) . ',';
if($noMergedSprintCount)  $content .= sprintf($lang->upgrade->projectCount, $noMergedSprintCount) . ',';
$content = rtrim($content, ',');

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        setClass('bg-white p-4'),
        set::style(array('margin' => '50px auto 0', 'width' => '1200px')),
        div
        (
            setClass('text-lg font-bold mb-4'),
            $lang->upgrade->mergeModes['manually']
        ),
        form
        (
            set::actions(''),
            set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
            div
            (
                div
                (
                    set::style(array('background-color' => 'var(--color-secondary-50)')),
                    setClass('p-4'),
                    div(setClass('text-secondary'), sprintf($lang->upgrade->mergeSummary, $content)), div(setClass('text-secondary'), html($lang->upgrade->mergeByProject))
                ),
                div($getMergeData($this->view))
            )
        )
    )
);

render('pagebase');
