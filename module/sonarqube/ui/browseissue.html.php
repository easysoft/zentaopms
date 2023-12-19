<?php
declare(strict_types=1);
/**
 * The browseissue view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @link        https://www.zentao.net
 */

namespace zin;

$replaceKey = str_replace('-', '*', $projectKey);
$issueList  = array();
foreach($sonarqubeIssueList as $issue)
{
    $issue->productID   = $productID;
    $issue->projectKey  = $projectKey;
    $issue->replaceKey  = $replaceKey;
    $issue->sonarqubeID = $sonarqube->id ;
    $issue->issueKey    = str_replace('-', '*', $issue->key);
    $issue->message     = htmlspecialchars_decode($issue->message);
    $issue->actions     = array('createBug');

    $issueList[] = $issue;
}

$config->sonarqube->dtable->issue->fieldList['message']['link']['url'] = sprintf($config->sonarqube->dtable->issue->fieldList['message']['link']['url'], trim($sonarqube->url, '/'));
if(!hasPriv('bug', 'create')) unset($config->sonarqube->dtable->issue->fieldList['actions']);

featureBar
(
    backBtn
    (
        set::text($lang->goback),
        set::url(createLink('sonarqube', 'browseProject', "sonarqubeID={$sonarqube->id}"))
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
                set::placeholder($lang->sonarqube->placeholder->searchIssue),
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

dtable
(
    set::cols($config->sonarqube->dtable->issue->fieldList),
    set::data($sonarqubeIssueList),
    set::sortLink(createLink('sonarqube', 'browseIssue', "sonarqubeID={$sonarqube->id}&projectKey={$replaceKey}&search={$search}&orderBy={name}_{sortType}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
