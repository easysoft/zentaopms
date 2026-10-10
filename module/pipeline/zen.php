<?php
declare(strict_types=1);
/**
 * The zen file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zenggang <zenggang@easycorp.ltd>
 * @package     pipeline
 * @link        https://www.zentao.net
 * @property    pipelineModel $pipeline
 */
class pipelineZen extends pipeline
{
    /**
     * 检测版本库数量。
     * Check repo empty.
     *
     * @access protected
     * @return void
     */
    protected function checkRepoEmpty(): void
    {
        $repos = $this->loadModel('repo')->getRepoPairs('devops');
        if(empty($repos)) $this->locate($this->repo->createLink('create'));
    }

    /**
     * 构建搜索表单。
     * Build search form.
     *
     * @param  array $searchConfig
     * @param  string|int $queryID
     * @param  string $actionURL
     * @access protected
     * @return void
     */
    protected function buildSearchForm(array $searchConfig, string|int $queryID, string $actionURL)
    {
        $searchConfig['queryID']   = (int)$queryID;
        $searchConfig['actionURL'] = $actionURL;

        if(isset($searchConfig['params']['repoID']))    $searchConfig['params']['repoID']['values']    = $this->loadModel('repo')->getRepoPairs('');
        if(isset($searchConfig['params']['spaceID']))   $searchConfig['params']['spaceID']['values']   = $this->loadModel('space')->getPairs();
        if(isset($searchConfig['params']['createdBy'])) $searchConfig['params']['createdBy']['values'] = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 获取搜索条件。
     * Get search condition.
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function getPipelineSearchQuery(int $queryID, string $queryName = 'pipelineQuery'): string
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set('pipelineForm', $query->form);
            }
        }
        if(!$this->session->$queryName) $this->session->set($queryName, ' 1 = 1');
        $pipelineQuery = $this->session->$queryName;
        $pipelineQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $pipelineQuery);
        if($queryName == 'pipelineexecQuery')
        {
            $pipelineQuery = str_replace(array('t1.`repoID`', 't1.`name`'), array('t2.`repoID`', 't2.`name`'), $pipelineQuery);
        }

        return $pipelineQuery;
    }

    /**
     * 获取现有的流水线列表。
     * getExistPipelines
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function getExistPipelines(int $repoID = 0)
    {
        $spaces = $this->loadModel('space')->getPairs($this->app->user->admin ? '' : $this->app->user->account);

        $spaceIdList = array_keys($spaces);
        $pipelines   = $this->pipeline->getBySpaces($spaceIdList);
        $repoList    = $this->loadModel('repo')->getListBySpaces($spaceIdList);

        $pipelineItems = array();
        if($repoID)
        {
            foreach($pipelines as $pipeline)
            {
                if(empty($pipeline->repoID)) continue;
                if(empty($repoList[$pipeline->repoID]) || $repoList[$pipeline->repoID]->mirror) continue;
                if(!isset($pipelineItems[$pipeline->repoID]))
                {
                    $pipelineItems[$pipeline->repoID]['items'] = array();
                    $pipelineItems[$pipeline->repoID]['text']  = $repoList[$pipeline->repoID]->name;
                }
                $pipelineItems[$pipeline->repoID]['items'][] = array('value' => $pipeline->id, 'text' => $pipeline->name);
            }
        }
        else
        {
            foreach($pipelines as $pipeline)
            {
                if(empty($pipeline->spaceID)) continue;
                if(!empty($repoList[$pipeline->repoID]) && $repoList[$pipeline->repoID]->mirror) continue;
                if(!isset($pipelineItems[$pipeline->spaceID]))
                {
                    $pipelineItems[$pipeline->spaceID]['items'] = array();
                    $pipelineItems[$pipeline->spaceID]['text']  = $spaces[$pipeline->spaceID];
                }
                $pipelineItems[$pipeline->spaceID]['items'][] = array('value' => $pipeline->id, 'text' => $pipeline->name);
            }
        }
        return $pipelineItems;
    }

    /**
     * 构建导入表单数据。
     * Build import form data.
     *
     * @param  int    $repoID
     * @param  int    $providerID
     * @access public
     * @return void
     */
    public function buildImportForm(int $repoID, int $providerID = 0)
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!$repo) return;

        $repoProvider     = $this->loadModel('provider')->getByID((int)$repo->providerID);
        $gitlabProviders  = array();
        $jenkinsProviders = $this->loadModel('provider')->getPairs('Jenkins');

        if($repoProvider && $repoProvider->type === 'GitLab')
        {
            $gitlabProviders[$repoProvider->id] = $repoProvider->name;
        }
        $providers = $gitlabProviders + $jenkinsProviders;

        $selectedProviderID = $providerID ?: (int)key($providers);
        $selectedProvider   = $selectedProviderID ? $this->loadModel('provider')->getByID($selectedProviderID) : null;
        $isJenkins          = $selectedProvider && $selectedProvider->type !== 'GitLab';
        $jenkinsPipelines   = array();
        $hidePipeline       = false;
        $defaultName        = $isJenkins ? '' : $repo->name;

        if($selectedProvider)
        {
            if($isJenkins)
            {
                $jenkinsPipelines = $this->getJenkinsPipelineList($selectedProviderID);
            }
            else
            {
                $hidePipeline = true;
            }
        }

        $this->view->repo               = $repo;
        $this->view->repoID             = $repoID;
        $this->view->providers          = $providers;
        $this->view->defaultProviderID  = $selectedProviderID;
        $this->view->isJenkins          = $isJenkins;
        $this->view->pipelines          = $jenkinsPipelines;
        $this->view->defaultName        = $defaultName;
        $this->view->hidePipeline       = $hidePipeline;
    }

    /**
     * 获取 Jenkins 流水线列表。
     * Get Jenkins pipeline list from provider.
     *
     * @param  int    $providerID
     * @access public
     * @return array
     */
    public function getJenkinsPipelineList(int $providerID): array
    {
        $provider = $this->loadModel('provider')->getByID($providerID);
        if(!$provider || $provider->type !== 'Jenkins') return array();

        $userPWD  = "$provider->account:$provider->token";
        $response = common::http($provider->url . '/api/json/items/list?depth=1', '', array(CURLOPT_USERPWD => $userPWD));
        $response = json_decode($response);

        $tasks = array();
        if(isset($response->jobs)) $tasks = $this->loadModel('jenkins')->getDepthJobs($response->jobs, $userPWD, 1);

        return $this->buildJenkinsTree($tasks);
    }

    /**
     * 构建 Jenkins 任务树形结构。
     * Build Jenkins task tree.
     *
     * @param  array  $tasks
     * @access private
     * @return array
     */
    private function buildJenkinsTree(array $tasks): array
    {
        $result = array();
        foreach($tasks as $key => $task)
        {
            if(empty($task)) continue;

            $item = array(
                'text' => is_array($task) ? urldecode($key) : urldecode($task),
                'keys' => urldecode(zget(common::convert2Pinyin(array($key)), $key, '')),
            );
            if(is_array($task))
            {
                $item['items'] = $this->buildJenkinsTree($task);
                $item['type']  = 'folder';
            }
            else
            {
                $item['value'] = $key;
            }

            $result[] = $item;
        }
        return $result;
    }

    /**
     * 构建符合 schema 渲染器协议的多层嵌套下拉数据。
     * Build nested picker items compatible with schema renderer.
     *
     * 协议：分组与叶子均为 {text, value?, items?}，text 为纯字符串；
     * 分组无 value 时渲染为禁用分组头，叶子带 value 可选。层级：空间 > 代码库 > 制品库。
     * 叶子 value 语义：file 型为制品库 id；container 型为寻址串——空间级 space.code/lib.code，
     * 仓库级 repo{repoID}/lib.code。
     *
     * @param  string $scope 制品库作用域，逗号分隔，取值 space/repo；空为不限
     * @param  string $type  制品类型过滤，container(docker)/file 等；空为不限
     * @access public
     * @return array
     */
    public function buildArtifactLibSchemaItems(string $scope = 'space,repo', string $type = 'container'): array
    {
        $spaces = $this->loadModel('space')->getPairs($this->app->user->admin ? '' : $this->app->user->account);
        $repos  = $this->loadModel('repo')->getRepoPairs();

        $spaceIdList = array_keys($spaces);
        $repoIdList  = array_keys($repos);

        $libs     = array();
        $artifact = $this->loadModel('artifact');
        foreach(explode(',', $scope) as $scopeItem)
        {
            $scopeItem = trim($scopeItem);
            if(empty($scopeItem)) continue;

            $scopeLibs = $artifact->getLibListByScope($scopeItem, $type, $spaceIdList, $repoIdList);
            foreach($scopeLibs as $lib) $libs[$lib->id] = $lib;
        }

        $buildKeys = function(string $text): string
        {
            $pinyin = common::convert2Pinyin(array($text));
            return trim($text . ' ' . urldecode(zget($pinyin, $text, '')));
        };

        /* container 制品叶子的 value 用制品库寻址标识而非库 id：空间级=space.code/lib.code，仓库级=repo{repoID}/lib.code；其余类型沿用库 id。 */
        $spaceCodeMap = array();
        if($type === 'container')
        {
            $spaceIdSet = array();
            foreach($libs as $lib) $spaceIdSet[(int)$lib->spaceID] = (int)$lib->spaceID;
            if($spaceIdSet)
            {
                $spaceList = $this->loadModel('space')->getByIdList($spaceIdSet, false);
                foreach($spaceList as $space) $spaceCodeMap[(int)$space->id] = $space->code;
            }
        }

        $buildLeaf = function(string $leafText, object $lib, string $keys) use ($type, $spaceCodeMap): array
        {
            if($type === 'container')
            {
                if(empty($lib->repoID))
                {
                    $spaceCode = isset($spaceCodeMap[(int)$lib->spaceID]) ? $spaceCodeMap[(int)$lib->spaceID] : '';
                    $value     = (empty($spaceCode) ? (string)$lib->spaceID : $spaceCode) . '/' . $lib->code;
                }
                else
                {
                    $value = 'repo' . (int)$lib->repoID . '/' . $lib->code;
                }
            }
            else
            {
                $value = (int)$lib->id;
            }

            return array('text' => $leafText, 'value' => $value, 'keys' => $keys);
        };

        $groups = array();
        foreach($spaces as $spaceID => $spaceName)
        {
            $spaceLeafs = array();
            $repoGroups = array();

            foreach($libs as $lib)
            {
                if((int)$lib->spaceID != $spaceID) continue;

                if(empty($lib->repoID))
                {
                    $spaceLeafs[] = $buildLeaf($lib->name, $lib, $buildKeys($spaceName . ' ' . $lib->name));
                }
                else
                {
                    $repoGroups[$lib->repoID][] = $lib;
                }
            }

            /* 空间下的可选项：直挂库收进「空间」容器，仓库级库收进「代码库」容器，结构对称。 */
            $items = array();
            if(!empty($spaceLeafs))
            {
                $items[] = array('text' => $this->lang->pipeline->typeList['space'], 'disabled' => true, 'items' => $spaceLeafs);
            }

            if(!empty($repoGroups))
            {
                $codeLibNodes = array();
                foreach($repoGroups as $repoID => $repoLibs)
                {
                    if(!isset($repos[$repoID])) continue;
                    $repoName = $repos[$repoID];

                    $libLeafs = array();
                    foreach($repoLibs as $lib)
                    {
                        $libLeafs[] = $buildLeaf($lib->name, $lib, $buildKeys($spaceName . ' ' . $repoName . ' ' . $lib->name));
                    }

                    $codeLibNodes[] = array('text' => $repoName, 'disabled' => true, 'items' => $libLeafs);
                }

                $items[] = array('text' => $this->lang->pipeline->typeList['repo'], 'disabled' => true, 'items' => $codeLibNodes);
            }

            if(empty($items)) continue;

            $groups[] = array('text' => $spaceName, 'value' => (string)$spaceID, 'disabled' => true, 'items' => $items);
        }

        return $groups;
    }

    /**
     * 渲染关键字链接。
     * Render keywords link.
     *
     * @param  object|bool $pipeline
     * @param  string      $params
     * @access public
     * @return array
     */
    public function renderSchemaKeywords(object|bool $pipeline, string $params = ''): array
    {
        $jsonSchemaKeywords = $this->config->pipeline->jsonSchemaKeywords;

        $renderResult = array();
        $params       = array_filter(explode(',', trim(urldecode($params))));

        $pramList = array();
        foreach($params as $param)
        {
            list($key, $value) = explode('=', $param);
            $pramList[$key] = $value;
        }

        foreach($jsonSchemaKeywords as $key => $link)
        {
            $linkParams = array();
            if(isset($link['params']))
            {
                foreach($link['params'] as $linkParam)
                {
                    if(!empty($pipeline) && isset($pipeline->{$linkParam})) $linkParams[$linkParam] = $pipeline->{$linkParam};
                    if(isset($pramList[$linkParam])) $linkParams[$linkParam] = $pramList[$linkParam];
                }
            }

            $renderResult[$key] = $this->createLink($link['module'], $link['method'], $linkParams);
        }
        return $renderResult;
    }
}
