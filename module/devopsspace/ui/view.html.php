<?php
declare(strict_types=1);
/**
 * The view view file of devopsspace module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     devopsspace
 * @link        https://www.zentao.net
 */
namespace zin;

$repoBrowseURL = common::hasPriv('repo', 'browse') ? $this->createLink('repo', 'browse', "repoID=%s") : '';
$repoDom       = initLinkTable($repoList, $repoBrowseURL);

$artifactRepoURL = common::hasPriv('artifactrepo', 'view') ? $this->createLink('artifactrepo', 'view', "id=%s") : '';
$artifactRepoDom = initLinkTable($artifactRepoList, $artifactRepoURL);
