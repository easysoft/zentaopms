<?php
declare(strict_types=1);
/**
 * The create trigger view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$conditions = zget($plan, 'conditions', array());
include 'standard.html.php';

unset($lang->codescan->triggerTypeList['manual']);

$app->loadLang('cron');
formPanel
(
    set::title($title),
    set::labelWidth('120px'),
    formGroup
    (
        set::name('name'),
        set::required(true),
        set::label($lang->codescan->name)
    ),
    formGroup
    (
        on::change()->call('updateTriggerType'),
        on::init()->do('setTimeout(updateTriggerType, 100);'),
        set::name('triggerType'),
        set::required(true),
        set::label($lang->codescan->triggerType),
        set::items($lang->codescan->triggerTypeList)
    ),
    formGroup
    (
        on::change()->call('updateOperation'),
        setClass('operation hidden'),
        set::name('operation'),
        set::required(true),
        set::label($lang->codescan->operation),
        set::items($lang->codescan->operationList)
    ),
    formGroup
    (
        setClass('keyword hidden'),
        set::name('keywords'),
        set::label($lang->codescan->keyword),
        set::placeholder($lang->codescan->notice->keywordNote)
    ),
    formRow
    (
        setClass('cron hidden'),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->m),
            set::required(true),
            set::name('minute')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->m),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        setClass('cron hidden'),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->h),
            set::required(true),
            set::name('hour')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->h),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        setClass('cron hidden'),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->dom),
            set::required(true),
            set::name('day')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->dom),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        setClass('cron hidden'),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->mon),
            set::required(true),
            set::name('month')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->mon),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        setClass('cron hidden'),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->dow),
            set::required(true),
            set::name('week')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->dow),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formGroup
    (
        setClass('cron hidden'),
        set::name('cronBranch'),
        set::required(true),
        set::items($branches),
        set::label($lang->codescan->scanBranch)
    ),
    formGroup
    (
        set::name('scanType'),
        setClass('scanType'),
        set::required(true),
        set::label($lang->codescan->scope),
        set::value(zget($plan, 'scope', 'all')),
        set::items($lang->codescan->scopeList)
    ),
    formGroup
    (
        set::name('solutions'),
        set::label($lang->codescan->solutions),
        set::required(true),
        set::value(array_column($plan->solutions, 'id')),
        set::items(array_column($solutionList, 'name', 'id')),
        set::control(array(
            'type'     => 'picker',
            'toolbar'  => true,
            'menu'     => array('checkbox' => true),
            'multiple' => true
        ))
    ),
    formRowGroup(set::title($lang->codescan->compliant)),
    formGroup
    (
        set::name(''),
        set::label(''),
        set::control('static'),
        set::labelWidth(isInModal() ? '32px' : ''),
        div(setClass('form-control-static'), $lang->codescan->notice->compliant)
    ),
    $standardBlock
);
