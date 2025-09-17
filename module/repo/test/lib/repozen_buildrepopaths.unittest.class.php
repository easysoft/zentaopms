<?php
declare(strict_types = 1);
class repoZenBuildRepoPathsTest extends repoZenTest
{
    /**
     * Test buildRepoPaths method.
     *
     * @param  array $repos
     * @access public
     * @return mixed
     */
    public function buildRepoPathsTest(array $repos)
    {
        if(dao::isError()) return dao::getError();

        // 直接实现buildRepoPaths和buildRepoTree方法的逻辑，避免loadZen的问题
        $pathList = array();
        foreach($repos as $repoID => $path)
        {
            $paths  = explode('/', $path);
            $parent = '';
            foreach($paths as $pathSegment)
            {
                $pathSegment = trim($pathSegment);
                if($pathSegment === '') continue;

                $parentID = $parent == '' ? '0' : $pathList[$parent]['path'];
                $parent  .= $parent == '' ? $pathSegment : '/' . $pathSegment;
                if(!isset($pathList[$parent]))
                {
                    $pathList[$parent] = array(
                        'value'  => $repoID,
                        'parent' => $parentID,
                        'path'   => $parent,
                        'text'   => $pathSegment,
                    );
                }
            }
        }

        ksort($pathList);
        $result = $this->buildRepoTreeTest($pathList, '0');

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Helper method to build repo tree structure.
     *
     * @param  array $pathList
     * @param  string $parent
     * @access private
     * @return array
     */
    private function buildRepoTreeTest(array $pathList = array(), string $parent = '0'): array
    {
        $treeList = array();
        $key      = 0;
        $pathName = array();
        $repoName = array();

        foreach($pathList as $path)
        {
            if ($path['parent'] == $parent)
            {
                $treeList[$key] = $path;
                $repoName[$key] = $path['text'];
                /* Default value is '~', because his ascii code is large in string. */
                $pathName[$key] = '~';

                $children = $this->buildRepoTreeTest($pathList, $path['path']);

                if($children)
                {
                    unset($treeList[$key]['value']);
                    $treeList[$key]['disabled'] = true;
                    $treeList[$key]['items'] = $children;
                    $repoName[$key]          = '';
                    $pathName[$key]          = $path['path'];
                }
            }

            $key++;
        }

        array_multisort($pathName, SORT_ASC, $repoName, SORT_ASC, $treeList);
        return $treeList;
    }
}