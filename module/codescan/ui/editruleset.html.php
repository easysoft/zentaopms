<?php
declare(strict_types=1);
/**
 * The edit view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('codeScanLang', $lang->codescan);
jsVar('ruleset', $ruleSet);
jsVar('langPairs', $langList);

formPanel
(
    set::formID('editRulesetForm'),
    set::title($lang->codescan->editRuleset),
    formGroup
    (
        set::name('name'),
        set::required(true),
        set::label($lang->codescan->name),
        set::value($ruleSet->name)
    ),
    formGroup
    (
        set::name('lang'),
        set::label($lang->codescan->language),
        set::required(true),
        set::disabled(true),
        set::items($langList),
        set::value($ruleSet->lang)
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->codescan->desc),
        set::value(empty($ruleSet->desc) ? '' : strip_tags(htmlspecialchars_decode($ruleSet->desc)))
    )
);
