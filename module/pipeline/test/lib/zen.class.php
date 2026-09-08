<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pipelineZenTest extends baseTest
{
    protected $moduleName = 'pipeline';
    protected $className  = 'zen';

    /**
     * Test getJenkinsPipelineList method.
     *
     * @param  int $providerID
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getJenkinsPipelineListTest(int $providerID, int $repoID = 0): array
    {
        $result = $this->invokeArgs('getJenkinsPipelineList', [$providerID, $repoID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildImportForm method.
     *
     * @param  int $repoID
     * @param  int $providerID
     * @access public
     * @return array
     */
    public function buildImportFormTest(int $repoID, int $providerID = 0): array
    {
        ob_start();
        $this->invokeArgs('buildImportForm', [$repoID, $providerID]);
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        $view = $this->getProperty('view');

        return array(
            'repoID'            => isset($view->repoID) ? (int)$view->repoID : 0,
            'defaultProviderID' => isset($view->defaultProviderID) ? (int)$view->defaultProviderID : 0,
            'isJenkins'         => isset($view->isJenkins) ? ($view->isJenkins ? 1 : 0) : 0,
            'hidePipeline'      => isset($view->hidePipeline) ? ($view->hidePipeline ? 1 : 0) : 0,
            'defaultName'       => isset($view->defaultName) ? (string)$view->defaultName : '',
            'providersCount'    => isset($view->providers) ? count($view->providers) : 0,
            'pipelinesCount'    => isset($view->pipelines) ? count($view->pipelines) : 0,
        );
    }

    public function checkRepoEmptyTest(): void
    {
        $oldLevel = error_reporting(0);
        ob_start();
        try { $this->invokeArgs('checkRepoEmpty', []); } catch(Throwable $e) {}
        ob_end_clean();
        error_reporting($oldLevel);
    }

    public function buildSearchFormTest(array $searchConfig, string|int $queryID, string $actionURL)
    {
        $oldLevel = error_reporting(0);
        ob_start();
        try { $result = $this->invokeArgs('buildSearchForm', [$searchConfig, $queryID, $actionURL]); } catch(Throwable $e) { $result = ''; }
        ob_end_clean();
        error_reporting($oldLevel);
        return $result;
    }

    public function getPipelineSearchQueryTest(int $queryID, string $queryName = 'pipelineQuery'): string
    {
        $result = $this->invokeArgs('getPipelineSearchQuery', [$queryID, $queryName]);
        if(dao::isError()) return false;
        return $result;
    }

    public function getExistPipelinesTest(int $repoID = 0)
    {
        $result = $this->invokeArgs('getExistPipelines', [$repoID]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test buildJenkinsTree method.
     *
     * @access public
     * @return mixed
     */
    public function buildJenkinsTreeTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('buildJenkinsTree', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test renderSchemaKeywords method.
     *
     * @access public
     * @return mixed
     */
    public function renderSchemaKeywordsTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('renderSchemaKeywords', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }

    /**
     * Test buildArtifactLibSchemaItems method.
     *
     * @param  string $scope
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function buildArtifactLibSchemaItemsTest(string $scope = 'space,repo', string $type = 'container')
    {
        try
        {
            $result = $this->invokeArgs('buildArtifactLibSchemaItems', array($scope, $type));
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);

            $values = array();
            $this->collectLeafValues($result, $values);
            sort($values, SORT_STRING);

            return array('groupCount' => count($result), 'values' => implode(',', $values));
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }

    /**
     * 收集嵌套树中所有叶子节点的 value，跳过带子项的分组节点。
     * Collect leaf node values in nested picker items.
     *
     * @param  array $nodes
     * @param  array $values
     * @access private
     * @return void
     */
    private function collectLeafValues(array $nodes, array &$values): void
    {
        foreach($nodes as $node)
        {
            if(isset($node['items']) && !empty($node['items']))
            {
                $this->collectLeafValues($node['items'], $values);
                continue;
            }

            if(array_key_exists('value', $node)) $values[] = (string)$node['value'];
        }
    }

}
