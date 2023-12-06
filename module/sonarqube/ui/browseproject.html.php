<?php
declare(strict_types=1);
/**
 * The browseproject view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @link        https://www.zentao.net
 */

namespace zin;

$projectList = array();
foreach($sonarqubeProjectList as $project)
{
    $project->jobID       = 0;
    $project->reportView  = 0;
    $project->sonarqubeID = $sonarqube->id ;
    $project->projectKey  = str_replace('-', '*', $project->key);
    if(isset($projectJobPairs[$project->key]))
    {
        $project->jobID = $projectJobPairs[$project->key];
        if(in_array($project->jobID, $successJobs)) $project->reportView = 1;
    }

    $projectList[] = $project;
}
$tableData = initTableData($sonarqubeProjectList, $config->sonarqube->dtable->project->fieldList, $this->sonarqube);

featureBar
(
    backBtn
    (
        set::text($lang->goback),
        set::url(createLink('instance', 'view', "id={$sonarqube->instanceID}&type={$sonarqube->type}"))
    ),
    form
    (
        setID('searchForm'),
        setClass('ml-4'),
        set::actions(array()),
        formRow
        (
            input
            (
                set::placeholder($lang->sonarqube->placeholderSearch),
                set::name('keyword'),
                set::value($keyword)
            ),
            btn
            (
                setClass('primary'),
                $lang->sonarqube->search,
                on::click('search')
            )
        )
    )
);

hasPriv('instance', 'manage') ? toolbar
(
    item
    (
        set::text($lang->sonarqube->createProject),
        set::url($this->createLink('sonarqube', 'createProject', "sonarqubeID={$sonarqube->id}")),
        set('data-toggle', 'modal'),
        setClass('primary'),
        set::icon('plus')
    )
) : null;

dtable
(
    set::cols($config->sonarqube->dtable->project->fieldList),
    set::data($tableData),
    set::sortLink(createLink('sonarqube', 'browseProject', "sonarqubeID={$sonarqube->id}&orderBy={name}_{sortType}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);

render();
