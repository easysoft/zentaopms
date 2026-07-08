<?php
declare(strict_types=1);
/**
 * The create plan view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

include 'standard.html.php';

if($repoID) dropmenu(set::objectID($repoID), set::tab('repo'));

formPanel
(
    set::title($title),
    set::labelWidth('120px'),
    formGroup
    (
        set::name('name'),
        set::width('2/3'),
        set::required(true),
        set::label($lang->codescan->name)
    ),
    formGroup
    (
        set::name('repo'),
        set::width('2/3'),
        set::required(true),
        $repoID ? set::value($repoID) : null,
        set::readonly($repoID ? true : false),
        set::label($lang->codescan->repo),
        set::items($repoList),
        on::init()->call('getBranches'),
        on::change()->call('getBranches')
    ),
    formRowGroup(set::title($lang->codescan->scanBranch)),
    formGroup
    (
        set::name('branch'),
        set::width('2/3'),
        set::label($lang->codescan->branch),
        set::items(array())
    ),
    formGroup
    (
        set::name('branchReg'),
        set::width('2/3'),
        set::label($lang->codescan->branchReg),
        set::placeholder($lang->codescan->notice->matchBranch)
    ),
    formRow(setClass('form-row-group border-b border-b-1')),
    formGroup
    (
        set::name('excludePath'),
        set::width('2/3'),
        set::label($lang->codescan->excludePath),
        set::placeholder($lang->codescan->notice->excludePath)
    ),
    formGroup
    (
        set::name('excludeFile'),
        set::width('2/3'),
        set::label($lang->codescan->excludeFile),
        set::placeholder($lang->codescan->notice->excludeFile)
    ),
    formGroup
    (
        set::name('scope'),
        set::width('2/3'),
        set::required(true),
        set::label($lang->codescan->scope),
        set::items($lang->codescan->scopeList)
    ),
    formGroup
    (
        set::name('solutions'),
        set::width('2/3'),
        set::label($lang->codescan->solutionIDs),
        set::required(true),
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
        div(setClass('form-control-static'), $lang->codescan->notice->compliant)
    ),
    $standardBlock
);
