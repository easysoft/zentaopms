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

function initLinkTable(array $listData, string $url): array
{
    $listDom = array();
    $tdDom   = array();
    foreach($listData as $id => $object)
    {
        $tdDom[] = $url ? h::td(h::a(set::href(sprintf($url, $id)), $object->name)) : h::td($object->name);

        $tdCount = count($tdDom);
        if($tdCount == 3)
        {
            $listDom[] = h::tr($tdDom);
            $tdDom = array();
        }

        if($id == end($listData)->id)
        {
            if($tdCount == 3) continue;
            for($i = $tdCount; $i < 3; $i++)
            {
                $tdDom[] = h::td();
            }
            $listDom[] = h::tr($tdDom);
        }
    }

    return $listDom;
}
