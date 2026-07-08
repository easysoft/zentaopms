<?php
declare(strict_types=1);
/**
 * The view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
$content = common::checkNotCN() ? zget($rule, 'content_en') : zget($rule, 'content');

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->control('html')
    ->content(empty($rule->content) ? zget($rule, 'desc', $lang->noDesc) : common::processMarkdown($content));

/* 基本信息。Legend basic items. */
$items[] = array(
    'label'   => $lang->codescan->language,
    'content' => zget($langList, $rule->lang)
);
$items[] = array(
    'label'   => $lang->codescan->tool,
    'content' => zget($pluginList, $rule->plugin)
);
$items[] = array(
    'label'   => $lang->codescan->type,
    'content' => zget($lang->codescan->typeList, $rule->type)
);
$items[] = array(
    'label'   => $lang->codescan->severity,
    'content' => zget($lang->codescan->severityList, $rule->priority)
);
$items[] = array(
    'label'   => $lang->codescan->tag,
    'control' => 'html',
    'content' => "<p>{$rule->tag}</p>"
);
$items[] = array(
    'label'   => $lang->codescan->status,
    'content' => zget($lang->codescan->statusList, $rule->status)
);
$items[] = array(
    'label'   => $lang->codescan->synopsis,
    'control' => 'html',
    'content' => "<p>{$rule->desc}</p>"
);

$tabs[] = setting()
    ->group('basic')
    ->title($lang->basicInfo)
    ->control('datalist')
    ->items($items);

detail
(
    set::title($rule ? $rule->name : ''),
    set::backBtn('codescan-browse,codescan-rulesetview,codescan-ruleset'),
    set::object($rule),
    set::history(empty($actions) ? false : array('objectType' => 'codescanrule')),
    set::sections($sections),
    set::tabs($tabs)
);
