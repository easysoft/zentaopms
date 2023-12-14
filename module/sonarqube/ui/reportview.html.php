<?php
declare(strict_types=1);
/**
 * The yyy view file of xxx module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     xxx
 * @link        https://www.zentao.net
 */
namespace zin;

$report = new stdclass();
$report->bugs                       = zget($measures, 'bugs', 0);
$report->vulnerabilities            = zget($measures, 'vulnerabilities', 0);
$report->security_hotspots_reviewed = zget($measures, 'security_hotspots_reviewed', '0.0%');
$report->code_smells                = zget($measures, 'code_smells', 0);
$report->coverage                   = zget($measures, 'coverage', '0.0%');
$report->duplicated_lines_density   = zget($measures, 'duplicated_lines_density', 0);
$report->ncloc                      = zget($measures, 'ncloc', 0);

$status      = zget($lang->sonarqube->qualitygateList, $qualitygate->projectStatus->status);
$statusClass = zget($config->sonarqube->projectStatusClass, $qualitygate->projectStatus->status);

div
(
    setClass('flex items-center pb-2'),
    a
    (
        setClass('text-md font-bold'),
        set('data-close-modal', true),
        $projectName,
        hasPriv('instance', 'manage') ? set::href(createLink('sonarqube', 'browseIssue', "sonarqubeID={$sonarqubeID}&project=" . str_replace('-', '*', $projectKey))) : null
    ),
    label
    (
        setClass("ml-2 rounded-full {$statusClass}"),
        set::text($status)
    )
);
dtable
(
    set::className('mt-4'),
    set::cols($config->sonarqube->dtable->report->fieldList),
    set::data(array($report))
);
