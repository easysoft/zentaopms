<?php
declare(strict_types=1);
/**
 * The model file of codescan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
class codescanModel extends model
{
    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $codeScan
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $codeScan, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'enable')  return $codeScan->status != 'enabled';
        if($action == 'disable') return $codeScan->status != 'disabled';

        if($action == 'task') return common::hasPriv('codescan', 'task');
        if($action == 'exec' && !in_array($this->app->rawMethod, array('plan', 'planview'))) return false;
        if($action == 'issue' && $this->app->rawMethod != 'task') return false;

        if($action == 'bug')          return empty($codeScan->bugID) && $codeScan->status != 'closed';
        if($action == 'confirmissue') return $codeScan->status == 'wait';
        if($action == 'activeissue')  return $codeScan->status == 'closed';
        if($action == 'ignoreissue')  return in_array($codeScan->status, array('wait', 'todo'));

        if($action == 'resend') return $codeScan->status == 'failed';

        return true;
    }

    /**
     * 通过API获取代码扫描规则集。
     * Api get code scan rulesets.
     *
     * @access public
     * @return array|object
     */
    public function getScanRulesets(array $params): array|object
    {
        if(isset($params['id'])) $params['id'] = (int)$params['id'];
        return $this->loadModel('gitfox')->request('/scan/rulesets/list', 'POST', $params) ?: array();
    }

    /**
     * 通过API获取代码扫描规则集下的规则。
     * Api get code scan rulesets rules.
     *
     * @param  int    $rulesetID
     * @param  array  $param
     * @access public
     * @return array|object
     */
    public function getScanRulesetRules(int $rulesetID, array $param): array|object
    {
        return $this->loadModel('gitfox')->request("/scan/rulesets/{$rulesetID}/rules/bind/list", 'POST', $param) ?: array();
    }

    /**
     * 通过API获取代码扫描规则集下未关联规则。
     * Api get code scan rulesets unlinked rules.
     *
     * @param  int    $rulesetID
     * @param  array  $param
     * @access public
     * @return array|object
     */
    public function getScanRulesetUnlinkRules(int $rulesetID, array $param): array|object
    {
        $url = "/scan/rulesets/{$rulesetID}/rules/unbind/list";

        return $this->loadModel('gitfox')->request($url, 'POST', $param) ?: array();
    }

    /**
     * 创建规则集。
     * Create ruleset.
     *
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function createRuleset(object $formData): int|false
    {
        $result = $this->loadModel('gitfox')->request('/scan/rulesets', 'POST', $formData);
        if(empty($result) || !isset($result->id)) return false;
        return $result->id;
    }

    /**
     * 获取规则集详情。
     * Get ruleset detail.
     *
     * @param  int    $rulesetID
     * @access public
     * @return object|false
     */
    public function getRuleset(int $rulesetID): object|false
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/rulesets/{$rulesetID}");
        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        return $this->gitfox->getResponse($result);
    }

    /**
     * 编辑规则集。
     * Edit ruleset.
     *
     * @param  int    $ruleID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function editRuleset(int $ruleID, object $formData): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/rulesets/{$ruleID}");

        $result = json_decode(common::http($url, $formData, array(), $apiRoot->header, 'json', 'PUT'));
        if(empty($result) || $result->code === 'failure')
        {
            if(isset($result->message))
            {
                dao::$errors[] = $result->message;
            }
            else
            {
                dao::$errors[] = $this->lang->error->httpServerError;
            }
            return false;
        }
        return true;
    }

    /**
     * 删除规则集。
     * Delete ruleset.
     *
     * @param  int    $ruleID
     * @access public
     * @return bool
     */
    public function deleteRuleset(int $ruleID): bool
    {
        return $this->loadModel('gitfox')->request("/scan/rulesets/{$ruleID}", 'DELETE') ?: false;
    }

    /**
     * 通过API获取代码扫描规则。
     * Api get code scan rules.
     *
     * @param  array  $params
     * @access public
     * @return array|object
     */
    public function getScanRules(array $params = array()): array|object
    {
        if(isset($params['id'])) $params['id'] = (int)$params['id'];
        if(isset($params['lang']) && is_numeric($params['lang']))
        {
            $params['langID'] = (int)$params['lang'];
            unset($params['lang']);
        }
        return $this->loadModel('gitfox')->request('/scan/rules/list', 'POST', $params) ?: array();
    }

    /**
     * 通过API获取一个扫描规则。
     * Api get code scan rule.
     *
     * @param  int    $ruleID
     * @access public
     * @return object
     */
    public function getScanRule(int $ruleID): object|null
    {
        return $this->loadModel('gitfox')->request("/scan/rules/{$ruleID}") ?: null;
    }

    /**
     * 获取扫描规则基础配置信息。
     * Get scan rules config.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getScanRulesConfig(string $type): array
    {
        return $this->loadModel('gitfox')->request("/scan/{$type}") ?: array();
    }

    /**
     * 通过API更新一个扫描规则状态。
     * Api update code scan rule status.
     *
     * @param  int   $ruleID
     * @access public
     * @return bool
     */
    public function updateScanRuleStatus(int $ruleID): bool
    {
        return $this->loadModel('gitfox')->request("/scan/rules/{$ruleID}/status", 'PUT') ?: false;
    }

    /**
     * 通过API更新一个扫描集状态。
     * Api update code scan ruleset status.
     *
     * @param  int   $rulesetID
     * @access public
     * @return bool
     */
    public function updateScanRulesetStatus(int $rulesetID, string $status = 'disabled'): bool | object
    {
        $data = new stdclass();
        $data->status = $status === '1' ? 'enabled' : 'disabled';
        $result = $this->loadModel('gitfox')->request("/scan/rulesets/{$rulesetID}", 'PUT', $data) ?: false;

        return $result;
    }

    /**
     * 通过API绑定扫描规则到扫描集。
     * Api bind code scan rule to ruleset.
     *
     * @param  int $rulesetID
     * @param  array $rules
     * @access public
     * @return bool
     */
    public function linkRulesInRuleset(int $rulesetID, array $rules = array()): bool
    {
        foreach($rules as &$value) $value = (int)$value;

        $data = new stdclass();
        $data->ruleIDList = $rules;

        return $this->loadModel('gitfox')->request("/scan/rulesets/$rulesetID/rules/bind", 'POST', $data) ?: false;
    }

    /**
     * 通过API解绑扫描规则到扫描集。
     * Api unbind code scan rule to ruleset.
     *
     * @param  int $rulesetID
     * @param  array $rules
     * @access public
     * @return bool
     */
    public function unlinkRules(int $rulesetID, array $rules = array()): bool
    {
        foreach($rules as &$value) $value = (int)$value;

        $data = new stdclass();
        $data->ruleIDList = $rules;

        return $this->loadModel('gitfox')->request("/scan/rulesets/$rulesetID/rules/unbind", 'POST', $data) ?: false;
    }

    /**
     * 通过API获取代码扫描方案。
     * Api get code scan solutions.
     *
     * @param  array  $params
     * @access public
     * @return array|object
     */
    public function getScanSolutions(array $params = array()): array|object
    {
        if(isset($params['id'])) $params['id'] = (int)$params['id'];
        $result = $this->loadModel('gitfox')->request('/scan/solutions/list', 'POST', $params);
        if(empty($result) || !isset($result->data)) return array();
        if(empty($result->data) && isset($result->pager) && $result->pager->total != 0)
        {
            $params['page'] = 1;
            $result = $this->getScanSolutions($params);
        }
        return $result;
    }

    /**
     * 获取扫描方案详情。
     * Get solution detail.
     *
     * @param  int    $solutionID
     * @access public
     * @return object|false
     */
    public function getSolution(int $solutionID): object|false
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}");

        $result = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        return $this->gitfox->getResponse($result);
    }

    /**
     * 通过API更新一个扫描方案状态。
     * Api update code scan solution status.
     *
     * @param  int   $solutionID
     * @access public
     * @return bool
     */
    public function updateSolutionStatus(int $solutionID): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/$solutionID/status");
        $result  = json_decode(common::http($url, array(), array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json', 'PATCH'));
        $this->gitfox->getResponse($result);
        return !dao::isError();
    }

    /**
     * 创建扫描方案。
     * Create solution.
     *
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function createSolution(object $formData): int|false
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, '/scan/solutions');

        $rulesets = $formData->rulesets;
        unset($formData->rulesets);

        $result = json_decode(common::http($url, $formData, array(), $apiRoot->header, 'json', 'POST'));
        $result = $this->gitfox->getResponse($result);
        if(dao::isError()) return false;

        $this->bindRulesets($result->id, $rulesets);
        return $result->id;
    }

    /**
     * 扫描方案绑定规则集。
     * Bind rulesets to solution.
     *
     * @param  int   $solutionID
     * @param  array $rulesets
     * @access public
     * @return bool
     */
    public function bindRulesets(int $solutionID, array $rulesets = array()): bool
    {
        foreach($rulesets as &$value) $value = (int)$value;

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}/rulesets/bind");
        $result  = json_decode(common::http($url, array('rulesetIDList' => $rulesets), array(), $apiRoot->header, 'json', 'POST'));
        $result  = $this->gitfox->getResponse($result);

        if(!$result) return false;
        return true;
    }

    /**
     * 扫描方案解绑规则集。
     * unBind rulesets to solution.
     *
     * @param  int   $solutionID
     * @param  array $rulesets
     * @access public
     * @return bool
     */
    public function unbindRulesets(int $solutionID, array $rulesets = array()): bool
    {
        foreach($rulesets as &$value) $value = (int)$value;

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}/rulesets/unbind");
        $result = json_decode(common::http($url, array('rulesetIDList' => $rulesets), array(), $apiRoot->header, 'json', 'POST'));
        $result = $this->gitfox->getResponse($result);

        if(!$result) return false;
        return true;
    }

    /**
     * 编辑扫描方案。
     * Edit solution.
     *
     * @param  int    $solutionID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function editSolution(int $solutionID, object $formData): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}");

        $rulesets = explode(',', $formData->rulesets);
        unset($formData->rulesets);

        $formData->updatedBy = $this->app->user->account;
        $result = json_decode(common::http($url, $formData, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $apiRoot->header, 'json', 'PUT'));
        $result = $this->gitfox->getResponse($result);
        if(dao::isError()) return false;

        $this->bindRulesets($solutionID, $rulesets);
        return true;
    }

    /**
     * 删除扫描方案。
     * Delete solution.
     *
     * @param  int    $solutionID
     * @access public
     * @return bool
     */
    public function deleteSolution(int $solutionID): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}");

        $result = json_decode(common::http($url, array(), array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));
        $result = $this->gitfox->getResponse($result);
        return !dao::isError();
    }

    /**
     * 通过API获取代码扫描方案未关联的规则集。
     * Api get code scan solution unlinked rulesets.
     *
     * @param  int    $solutionID
     * @param  array  $param
     * @access public
     * @return array|object
     */
    public function getSolutionUnlinkRulesets(int $solutionID, array $param): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}/rulesets/unbind");
        if(!empty($param)) $url .= '?'. http_build_query($param);

        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        return $this->gitfox->getResponse($result);
    }

    /**
     * 通过API绑定规则集到扫描方案。
     * Api bind rulesets to solution.
     *
     * @param  int   $solutionID
     * @param  array $rulesets
     * @access public
     * @return bool
     */
    public function linkRulesetInSolution(int $solutionID, array $rulesets = array()): bool
    {
        foreach($rulesets as &$value) $value = (int)$value;

        $data = new stdclass();
        $data->rulesetIDList = $rulesets;

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/solutions/{$solutionID}/rulesets/bind");
        $result  = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json'));
        $this->gitfox->getResponse($result);
        return !dao::isError();
    }

    /**
     * 通过API获取扫描计划列表。
     * Api get scan plans.
     *
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanPlans(int $repoID, array $params): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();

        $url = "/scan/repos/{$repoID}/scanplans/list";
        $url = sprintf($apiRoot->url, $url);

        $result = json_decode(common::http($url, $params, array(), $apiRoot->header, 'json', 'POST'));
        $result = $this->gitfox->getResponse($result);

        if(empty($result) || !isset($result->data)) return array();
        if(empty($result->data) && isset($result->pager) && $result->pager->total != 0)
        {
            $params['page'] = 1;
            $result = $this->getScanPlans($repoID, $params);
        }
        return $result;
    }

    /**
     * 通过API获取扫描计划详情。
     * Api get scan plan.
     *
     * @param  int   $planID
     * @access public
     * @return array|object
     */
    public function getScanPlan(int $planID, int $repoID): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$repoID}/scanplans/{$planID}");

        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        $result  = $this->gitfox->getResponse($result);
        if(empty($result) || dao::isError()) return array();

        return $result;
    }

    /**
     * 通过API删除扫描计划。
     * Api delete scan plan.
     *
     * @param  int $planID
     * @access public
     * @return bool
     */
    public function deleteScanPlan(int $serviceRepoID, int $planID): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$serviceRepoID}/scanplans/{$planID}");

        $result  = json_decode(common::http($url, array(), array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));
        $this->gitfox->getResponse($result);
        return !dao::isError();
    }

    /**
     * 创建扫描计划。
     * Create plan.
     *
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function createPlan(object $formData): int|false
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$formData->repoID}/scanplans");

        unset($formData->repoID);
        $result = json_decode(common::http($url, $formData, array(), $apiRoot->header, 'json', 'POST'));
        $result = $this->gitfox->getResponse($result);
        if(dao::isError()) return false;

        return (int)$result->id;
    }

    /**
     * 创建扫描计划。
     * Create plan.
     *
     * @param  int    $repoID
     * @param  int    $planID
     * @param  array  $conditions
     * @access public
     * @return bool
     */
    public function batchCreateConditions(int $repoID, int $planID, array $conditions): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$repoID}/scanplans/{$planID}/conditions/batch/new");

        $data = new stdClass();
        $data->conditions = $conditions;

        $result = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json', 'POST'));
        $this->gitfox->getResponse($result);

        return !dao::isError();
    }

    /**
     * 通过API获取扫描计划条件列表。
     * Api get scan plan conditions.
     *
     * @param  int $repoID
     * @param  int $planID
     * @access public
     * @return array|object
     */
    public function getPlanConditions(int $repoID, int $planID): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$repoID}/scanplans/{$planID}/conditions");

        $result = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        $result = $this->gitfox->getResponse($result);

        return empty($result->data) ? array() : $result->data;
    }

    /**
     * 批量删除扫描计划条件。
     * Batch delete plan conditions.
     *
     * @param  int $repoID
     * @param  int $planID
     * @param  array $conditions
     * @access public
     * @return bool
     */
    public function batchDeletePlanConditions(int $repoID, int $planID, array $conditions): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$repoID}/scanplans/{$planID}/conditions/batch/delete");

        $data = new stdClass();
        $data->ids = $conditions;

        $result = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json', 'POST'));
        $this->gitfox->getResponse($result);
        return !dao::isError();
    }

    /**
     * 编辑扫描计划。
     * Edit plan.
     *
     * @param  int    $planID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function editPlan(int $repoID, int $planID, object $formData): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/repos/{$repoID}/scanplans/{$planID}");

        $solutions = $formData->solutionIDs;
        unset($formData->solutionIDs);

        $result = json_decode(common::http($url, $formData, array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json', 'PATCH'));
        $this->gitfox->getResponse($result);
        if(dao::isError()) return false;

        $bindResult = $this->bindOrUnbindSolutions($repoID, $planID, $solutions);
        if(!$bindResult) return false;

        return true;
    }

    /**
     * 绑定或者解除绑定扫描计划解决方案。
     * Bind or unbind plan solutions.
     *
     * @param  int $repoID
     * @param  int $planID
     * @param  array $solutions
     * @param  bool $bind
     * @access public
     * @return bool
     */
    public function bindOrUnbindSolutions(int $repoID, int $planID, array $solutions, bool $bind = true): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $isBind  = $bind ? 'bind' : 'unbind';
        $url     = sprintf($apiRoot->url, "/scan/repos/{$repoID}/scanplans/{$planID}/{$isBind}");

        $solutionList = array();
        foreach($solutions as &$value) $solutionList[] = (int)$value;

        $result = json_decode(common::http($url, array('solutionIDs' => $solutionList), array(), $apiRoot->header, 'json', 'POST'));
        $this->gitfox->getResponse($result);

        return !dao::isError();
    }

    /**
     * 通过API获取扫描任务列表。
     * Api get scan tasks.
     *
     * @param  int   $repoID
     * @param  int   $planID
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanTasks(int $repoID, int $planID, array $params): array|object
    {
        if(!isset($params['repoID'])) $params['repoID'] = (int)$repoID;
        if(!isset($params['planID'])) $params['planID'] = (int)$planID;

        if(isset($params['taskID'])) $params['taskID'] = (int)$params['taskID'];
        if(isset($params['repoID'])) $params['repoID'] = (int)$params['repoID'];
        if(isset($params['planID'])) $params['planID'] = (int)$params['planID'];
        return $this->loadModel('gitfox')->request('/scan/tasks/list', 'POST', $params);
    }

    /**
     * 通过API获取列表总数和数据。
     * Api get list total and data.
     *
     * @param  string $api
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getListByAPI(string $api, array $params): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, $api);
        if(!empty($params))
        {
            $priorityParams = '';
            if(!empty($params['priority']))
            {
                foreach(explode(',', $params['priority']) as $value) $priorityParams .= "&priority={$value}";
                unset($params['priority']);
            }

            $url .= '?'. http_build_query($params) . $priorityParams;
        }

        $result = common::http($url, array(), array(), $apiRoot->header, 'json', 'GET', 30, true);
        if(empty($result) || $result[1] != 200) return array();

        $response = new stdclass();
        $response->data  = json_decode($result['body']);
        $response->total = zget($result['header'], 'X-Total', 0);

        if(isset($response->data->data)) $response->data = $response->data->data;
        if(empty($response->data) && $response->total != 0)
        {
            $params['page'] = 1;
            $response = $this->getListByAPI($api, $params);
        }

        return $response;
    }

    /**
     * 通过API获取扫描任务详情。
     * Api get scan task.
     *
     * @param  int   $taskID
     * @access public
     * @return array|object
     */
    public function getScanTask(int $taskID): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/tasks/{$taskID}");

        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        return $this->gitfox->getResponse($result);
    }

    /**
     * 处理耗时。
     * Format duration.
     *
     * @param  int $duration
     * @access public
     * @return string
     */
    public function formatDuration(int $duration): string
    {
        $hours   = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        $result = [];
        if($hours > 0)
        {
            $result[] = $hours . 'h';
            $result[] = $minutes . 'm';
        }
        else
        {
            if ($minutes > 0) $result[] = $minutes . 'm';
            if ($seconds > 0 || ($minutes == 0 && $seconds == 0)) $result[] = $seconds . 's';
        }
        return implode('', $result);
    }

    /**
     * 执行扫描任务。
     * Execute scan task.
     *
     * @param  object $plan
     * @param  string $branch
     * @access public
     * @return object|false
     */
    public function execScanTask(object $plan, string $branch): object|false
    {
        $data = new stdclass();
        $data->branch = $branch;
        $data->planID = $plan->id;
        $data->repoID = $plan->repoID;
        return $this->loadModel('gitfox')->request('/scan/tasks', 'POST', $data);
    }

    /**
     * 修改问题状态。
     * Change issue status.
     *
     * @param  int|array $issueIdList
     * @param  string    $status
     * @param  string    $solution
     * @param  string    $solutionDate
     * @param  int       $ignoreDate
     * @access public
     * @return void
     */
    public function changeIssueState(int|array $issueIdList, string $status, string $solution = '', string $solutionDate = '', int $ignoreDate = 0): bool
    {
        if(is_numeric($issueIdList)) $issueIdList = array($issueIdList);
        foreach($issueIdList as $index => $issueID) $issueIdList[$index] = (int)$issueID;

        $data = new stdclass();
        $data->status = $status;
        $data->ids    = $issueIdList;
        if(!empty($solution))     $data->resolution = $solution;
        if(!empty($solutionDate)) $data->resolved   = $solutionDate;
        if(!empty($ignoreDate))   $data->ignored    = $ignoreDate == -1 ? -1 : $ignoreDate * 1000;
        $this->loadModel('gitfox')->request('/scan/issues/status', 'PUT', $data);
        if(dao::isError()) return false;

        $this->loadModel('action');
        $status = !empty($ignoreDate) ? 'ignore' : $data->status;
        $repoPair = $this->loadModel('repo')->getRepoPairs();
        foreach($issueIdList as $issueID)
        {
            $issue = $this->getScanIssue($issueID, false);
            $extra = empty($issue) || empty($repoPair[$issue->repoID]) ? '' : "{$issue->message}|issueID={$issueID}&repoID={$repoPair[$issue->repoID]}";
            $this->action->create('codescanissue', $issueID, $status . 'ScanIssue', '', $extra);
        }

        return true;
    }

    /**
     * 通过API获取扫描问题列表。
     * Api get scan issue list.
     *
     * @param  int    $taskID
     * @param  array  $params
     * @access public
     * @return object|array
     */
    public function getScanIssueList(int $taskID, array $params = array()): object|array
    {
        if(isset($params['repoID']))   $params['repoID'] = (int)$params['repoID'];
        if(isset($params['ruleID']))   $params['ruleID'] = (int)$params['ruleID'];
        if(isset($params['planID']))   $params['planID'] = (int)$params['planID'];
        if(isset($params['taskID']))   $params['taskID'] = (int)$params['taskID'];
        if(isset($params['issueID']))  $params['id']     = (int)$params['issueID'];

        if(isset($params['priority']))  $params['rulePriority'] = $params['priority'];
        if(isset($params['type']))      $params['ruleType']     = $params['type'];
        if(isset($params['plugin']))    $params['tool']         = $params['plugin'];
        if(isset($params['createdAt'])) $params['createDate']   = $params['createdAt'];
        if(isset($params['plan']))      $params['planID']       = (int)$params['plan'];
        if(isset($params['sort']) && $params['sort'] == 'createdAt') $params['sort'] = 'createDate';

        $api    = $taskID ? "/scan/tasks/{$taskID}/issues" : '/scan/issues/list';
        $result = $this->loadModel('gitfox')->request($api, 'POST', $params);

        if(empty($result) || empty($result->data)) return array();

        $bugList = $this->dao->select('issueKey,id')->from(TABLE_BUG)->where('issueKey')->in(array_column($result->data, 'id'))->fetchPairs();
        foreach($result->data as $issue)
        {
            $issue->bugID     = isset($bugList[$issue->id]) ? $bugList[$issue->id] : 0;
            $issue->createdAt = $issue->createdDate;
            if(!empty($issue->trigger)) $issue->triggerName = isset($issue->trigger->triggerName) ? $issue->trigger->triggerName : zget($this->lang->codescan->triggerTypeList, $issue->trigger->triggerType);
            if(!empty($issue->ignored) && $issue->ignored > 0 && $issue->ignored <= (time() * 1000)) $this->changeIssueState($issue->id, 'wait');
        }

        return $result;
    }

    /**
     * 通过问题ID列表获取扫描问题列表。
     * Get scan issue list by issue id list.
     *
     * @param  array  $issueIdList
     * @access public
     * @return object|array
     */
    public function getScanIssueListByIdList(array $issueIdList): object|array
    {
        $IdList = array();
        foreach($issueIdList as $issueID) $IdList[] = (int)$issueID;

        $params = array('ids' => $IdList);
        return $this->loadModel('gitfox')->request('/scan/issues/batch', 'POST', $params);
    }

    /**
     * 通过API获取扫描问题详情。
     * Api get scan issue.
     *
     * @param  int    $issueID
     * @param  bool   $showBug
     * @access public
     * @return array|object
     */
    public function getScanIssue(int $issueID, bool $showBug = true): array|object
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/issues/{$issueID}");

        $result = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        $result = $this->gitfox->getResponse($result);

        if($showBug)
        {
            $bugID = $this->dao->select('`id`')->from(TABLE_BUG)->where('issueKey')->eq($issueID)->fetch('id');
            if($bugID) $result->bugID = $bugID;
        }
        $result->tool               = zget($result->payload, 'tool', '');
        $result->snippet            = zget($result->payload, 'snippet', '');
        $result->snippetWithContext = zget($result->payload, 'snippetWithContext', '');
        $result->rangeStartLine     = (int)zget($result->payload->location->range, 'startLine', 0);
        $result->rangeEndLine       = (int)zget($result->payload->location->range, 'endLine', 0);
        $result->commit             = zget($result->payload->location, 'commit', array());
        if($result->rangeStartLine < 1 && !empty($result->line))
        {
            $result->rangeStartLine = (int)$result->line;
            $result->rangeEndLine   = (int)$result->line;
        }
        elseif($result->rangeEndLine < $result->rangeStartLine || ($result->rangeEndLine === 0 && $result->rangeStartLine > 0))
        {
            $result->rangeEndLine = $result->rangeStartLine;
        }
        $result = $this->processIssueSnipe($result);

        return $result;
    }

    /**
     * 处理问题代码片段。
     * Process issue snippet.
     *
     * @param  object $issue
     * @access public
     * @return object
     */
    public function processIssueSnipe(object $issue): object
    {
        if(empty($issue->snippetWithContext) || empty($issue->snippet)) return $issue;

        $startLine = (int)$issue->rangeStartLine;
        $endLine   = (int)$issue->rangeEndLine;
        if($startLine < 1 || $endLine < $startLine) return $issue;

        $snippetContext = $issue->snippetWithContext;
        $snippet        = $issue->snippet;

        $snippetContextLines = preg_split('/\r\n|\n|\r/', $snippetContext);
        $snippetLines        = preg_split('/\r\n|\n|\r/', $snippet);

        $snippetStartLineContext = $snippetLines[0];

        $matchedLineNo = 0;
        foreach($snippetContextLines as $index => $line)
        {
            if($line == $snippetStartLineContext)
            {
                $matchedLineNo = $index + 1;
                break;
            }
        }
        if($matchedLineNo === 0)
        {
            $issue->snippetStartLine = 0;
            $issue->snippetEndLine   = 0;
            return $issue;
        }

        $issue->snippetStartLine = $startLine - $matchedLineNo + 1;
        $issue->snippetEndLine   = count($snippetContextLines) - $matchedLineNo - count($snippetLines) + 1 + $endLine;
        return $issue;
    }

    /**
     * 获取扫描问题的关联Bug列表。
     * Get linked bug list.
     *
     * @param  array|int $issueList
     * @param  string    $status
     * @access public
     * @return array
     */
    public function getLinkedBugList(array|int $issueList, string $status = ''): array
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->beginIF(is_array($issueList))->andWhere('issueKey')->in($issueList)->fi()
            ->beginIF(!is_array($issueList))->andWhere('issueKey')->eq($issueList)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取扫描问题的解决人排行榜。
     * Get scan issue resolved by top.
     *
     * @param  int $repoID
     * @param  int $top
     * @access public
     * @return array
     */
    public function getIssueResolvedByTop(int $repoID = 0, int $top = 10): array
    {
        return $this->dao->select('t2.realname, count(t1.id) as count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.resolvedBy = t2.account')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.issueKey')->ne('')
            ->andWhere('t1.resolution')->eq('fixed')
            ->beginIF($repoID)->andWhere('t1.repo')->eq($repoID)->fi()
            ->groupBy('t1.resolvedBy')
            ->orderBy('count_desc')
            ->limit($top)
            ->fetchPairs();
    }

    /**
     * 通过代码库ID和任务ID获取扫描问题的树信息。
     * Get scan issue tree list.
     *
     * @param  int    $repoID
     * @param  int    $taskID
     * @param  string $type      file|rule
     * @access public
     * @return array
     */
    public function getIssueTreeList(int $repoID, int $taskID, string $type = 'file'): array
    {
        $url    = "/scan/issues/{$type}-tree";
        $params = array('repoID' => $repoID, 'taskID' => $taskID);
        $result = $this->loadModel('gitfox')->request($url, 'GET', $params);
        return array($result);
    }

    /**
     * 格式化数字为W/W+格式
     * Format number to W/W+ format
     *
     * @param  int $number
     * @return string
     */
    public function formatNumberToW(int $number): string
    {
        if ($number < 10000) return (string)$number;

        $w = floor($number / 10000);
        return $number % 10000 === 0 ? $w . 'W' : $w . 'W+';
    }

    /**
     * 获取代码库的统计数据。
     * Get code repo statistics.
     *
     * @param  int    $repoID
     * @param  int    $taskID
     * @access public
     * @return object|array
     */
    public function getRepoMetrics(int $repoID, int $taskID = 0): object|array
    {
        $url = '/scan/metrics';
        if($repoID) $url .= "/repo/{$repoID}";
        if($taskID) $url .= "/task/{$taskID}";
        if(common::checkNotCN()) $url .= "?lang=en";

        $result = $this->loadModel('gitfox')->request($url, 'GET', array());
        if(empty($result) || isset($result->message)) return array();

        return $result;
    }

    /**
     * 刷新概况数据。
     * Refresh overview data.
     *
     * @access public
     * @return bool
     */
    public function refreshOverview(): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/metrics/trigger");

        common::http($url, array('refresh' => 'true'), array(), $apiRoot->header, 'json', 'POST');
        return true;
    }

    /**
     * 获取最近一次执行的时间。
     * Get last execute time.
     *
     * @access public
     * @return string
     */
    public function getLastExecuteTime(): string
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, '/scan/metrics/trigger/last_executed');

        $result = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json'));
        if(empty($result) || isset($result->message) || empty($result->last_executed_unixtime)) return '';

        $duration = time() - $result->last_executed_unixtime;
        return $this->formatDuration($duration > 0 ? $duration : 0);
    }

    /**
     * 通过代码库ID和任务ID获取扫描问题的趋势数据。
     * Get scan issue trends.
     *
     * @param  int    $repoID
     * @param  int    $beginDate
     * @param  string $scope
     * @access public
     * @return array
     */
    public function getIssueTrendsByRepo(int $repoID, int $beginDate = 0, string $scope = 'day'): array
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/metrics/repo/{$repoID}/query");
        $url    .= "?step={$scope}&metric=issue_added&metric=issue_fixed&after={$beginDate}&before=" . (time() * 1000);

        $result = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json'));
        return $this->gitfox->getResponse($result);
    }

    /**
     * 重试扫描任务。
     * Retry scan task.
     *
     * @param  int $taskID
     * @access public
     * @return bool
     */
    public function resendTask(int $taskID): bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/scan/tasks/{$taskID}/retry");

        $result = json_decode(common::http($url, array('refresh' => 'true'), array(), $apiRoot->header, 'json'));
        $this->gitfox->getResponse($result);
        return !dao::isError();
    }
}
