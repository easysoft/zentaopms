<?php
declare(strict_types=1);
/**
 * The model file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id: $
 * @link        https://www.zentao.net
 */

class sonarqubeModel extends model
{
    /**
     * 获取sonarqube api基础url和头。
     * Get sonarqube api base url and header by id.
     *
     * @param  int    $sonarqubeID
     * @access public
     * @return array
     */
    public function getApiBase(int $sonarqubeID): array
    {
        $sonarqube = $this->loadModel('pipeline')->getByID($sonarqubeID);
        if(!$sonarqube) return array('', array());

        $url      = rtrim($sonarqube->url, '/') . '/api/%s';
        $header[] = 'Authorization: Basic ' . $sonarqube->token;

        return array($url, $header);
    }

    /**
     * 检查sonarqube是否可用。
     * check sonarqube valid.
     *
     * @param  string $host
     * @param  string $token
     * @access public
     * @return array
     */
    public function apiValidate(string $host, string $token): array
    {
        $url    = rtrim($host, '/') . "/api/authentication/validate";
        $header = array('Authorization: Basic ' . $token);
        $result = json_decode(commonModel::http($url, null, array(), $header));
        if(!isset($result->valid) or !$result->valid) return array('password' => array($this->lang->sonarqube->validError));

        $url     = rtrim($host, '/') . "/api/user_groups/search";
        $adminer = json_decode(commonModel::http($url, null, array(), $header));
        if(empty($adminer) or isset($adminer->errors)) return array('account' => array($this->lang->sonarqube->notAdminer));

        return array();
    }

    /**
     * 获取sonarqube报告。
     * Get sonarqube report.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @param  string $metricKeys
     * @access public
     * @return object|array|null
     */
    public function apiGetReport(int $sonarqubeID, string $projectKey, string $metricKeys = ''): object|array|null
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return array();

        if(!$metricKeys) $metricKeys = 'bugs,coverage,vulnerabilities,duplicated_lines_density,code_smells,ncloc,security_hotspots_reviewed';
        $url = sprintf($apiRoot, "measures/component?component={$projectKey}&metricKeys={$metricKeys}");
        return json_decode(commonModel::http($url, null, array(), $header));
    }

    /**
     * 获取sonarqube项目质量门。
     * Get sonarqube qualitygate by project.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return object|array|null
     */
    public function apiGetQualitygate(int $sonarqubeID, string $projectKey): object|array|null
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot, "qualitygates/project_status?projectKey={$projectKey}");
        return json_decode(commonModel::http($url, null, array(), $header));
    }

    /**
     * 获取sonarqube问题列表。
     * Get issues of one sonarqube.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return array
     */
    public function apiGetIssues(int $sonarqubeID, string $projectKey = ''): array
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return array();

        $url  = sprintf($apiRoot, "issues/search");
        $url .= "?ps=500";
        if($projectKey) $url .= "&componentKeys={$projectKey}";

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $result = json_decode(commonModel::http($url . "&p={$page}", null, array(), $header));
            if(!isset($result->issues)) break;
            if(!empty($result->issues)) $allResults = array_merge($allResults, $result->issues);
            if(count($result->issues) < 500) break;
        }

        return $allResults;
    }

    /**
     * 获取sonarqube项目列表。
     * Get projects of one sonarqube.
     *
     * @param  int    $sonarqubeID
     * @param  string $keyword
     * @access public
     * @return array
     */
    public function apiGetProjects(int $sonarqubeID, string $keyword = '', string $projectKey = ''): array
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return array();

        $url  = sprintf($apiRoot, "projects/search");
        $url .= "?ps=500";
        if($keyword)    $url .= "&q={$keyword}";
        if($projectKey) $url .= "&projects={$projectKey}";

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $result = json_decode(commonModel::http($url . "&p={$page}", null, array(), $header));
            if(!isset($result->components)) break;
            if(!empty($result->components)) $allResults = array_merge($allResults, $result->components);
            if(count($result->components) < 500) break;
        }

        return $allResults;
    }

    /**
     * api创建sonarqube项目。
     * Create a sonarqube project by api.
     *
     * @param int    $sonarqubeID
     * @param object $project
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateProject(int $sonarqubeID, object $project): object|array|null|false
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return false;

        $url    = sprintf($apiRoot, "projects/create?name=" . urlencode($project->projectName) . "&project=" . urlencode($project->projectKey));
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header));

    }


    /**
     * api删除sonarqube项目。
     * Delete sonarqube project by api.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteProject(int $sonarqubeID, string $projectKey): object|array|null|false
    {
        list($apiRoot, $header) = $this->getApiBase($sonarqubeID);
        if(!$apiRoot) return false;

        $url    = sprintf($apiRoot, "projects/delete?project=$projectKey");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header));
    }

    /**
     * 创建sonarqube项目。
     * Create a sonarqube project.
     *
     * @param  int    $sonarqubeID
     * @param  object $project
     * @access public
     * @return bool
     */
    public function createProject(int $sonarqubeID, object $project): bool
    {
        if(mb_strlen($project->projectName) > 255) dao::$errors['projectName'][] = sprintf($this->lang->sonarqube->lengthError, $this->lang->sonarqube->projectName, 255);
        if(mb_strlen($project->projectKey) > 400)  dao::$errors['projectKey'][]  = sprintf($this->lang->sonarqube->lengthError, $this->lang->sonarqube->projectKey, 400);
        if(dao::isError()) return false;

        $response = $this->apiCreateProject($sonarqubeID, $project);

        if(!empty($response->project->key))
        {
            $this->loadModel('action')->create('sonarqubeproject', 0, 'created', '', $response->project->name);
            return true;
        }

        if(!$response) return false;
        return $this->apiErrorHandling($response);
    }

    /**
     * api错误处理。
     * Api error handling.
     *
     * @param  object $response
     * @access public
     * @return bool
     */
    public function apiErrorHandling(object|null $response): bool
    {
        if(!empty($response->errors))
        {
            foreach($response->errors as $error)
            {
                if(isset($error->msg))
                {
                    dao::$errors['name'][] = $this->convertApiError($error->msg);
                }
            }
            return false;
        }

        dao::$errors['name'][] = 'error';
        return false;
    }

    /**
     * api错误转换处理。
     * Convert API error.
     *
     * @param  array|string $message
     * @access public
     * @return string
     */
    public function convertApiError(array|string $message): string
    {
        if(is_array($message)) $message = $message[0];
        if(!is_string($message)) return $message;

        if(!isset($this->lang->sonarqube->apiErrorMap)) return $message;
        foreach($this->lang->sonarqube->apiErrorMap as $key => $errorMsg)
        {
            if(strpos($errorMsg, '/') === 0)
            {
                $result = preg_match($errorMsg, $message, $matches);
                if($result) $errorMessage = sprintf(zget($this->lang->sonarqube->errorLang, $key), $matches[1]);
            }
            elseif($message == $errorMsg)
            {
                $errorMessage = zget($this->lang->mr->errorLang, $key, $message);
            }
            if(isset($errorMessage)) break;
        }
        return isset($errorMessage) ? $errorMessage : $message;
    }

    /**
     * 获取缓存文件。
     * Get cache file.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return string
     */
    public function getCacheFile($sonarqubeID, $projectKey): string
    {
        $cachePath = $this->app->getCacheRoot() . '/' . 'sonarqube';
        if(!is_dir($cachePath)) mkdir($cachePath, 0777, true);
        if(!is_writable($cachePath)) return false;
        return $cachePath . '/' . $sonarqubeID . '-' . md5($projectKey);
    }

    /**
     * 获取关联产品。
     * Get linked products.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return string
     */
    public function getLinkedProducts($sonarqubeID, $projectKey): string
    {
        return $this->dao->select('t2.product')->from(TABLE_JOB)->alias('t1')
            ->leftJoin(TABLE_REPO)->alias('t2')->on('t1.repo=t2.id')
            ->where('t1.frame')->eq('sonarqube')
            ->andWhere('sonarqubeServer')->eq($sonarqubeID)
            ->andWhere('projectKey')->eq($projectKey)
            ->fetch('product');
    }

    /**
     * 获取项目键值对。
     * Get project pairs.
     *
     * @param  int    $sonarqubeID
     * @param  string $projectKey
     * @access public
     * @return array
     */
    public function getProjectPairs(int $sonarqubeID, string $projectKey = ''): array
    {
        $jobPairs      = $this->loadModel('job')->getJobBySonarqubeProject($sonarqubeID, array(), true, true);
        $existsProject = array_diff(array_keys($jobPairs), array($projectKey));

        $projectList = $this->apiGetProjects($sonarqubeID);

        $projectPairs = array();
        foreach($projectList as $project)
        {
            if(!empty($project) and !in_array($project->key, $existsProject)) $projectPairs[$project->key] = $project->name;
        }

        return $projectPairs;
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $sonarqube
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $sonarqube, string $action): bool
    {
        $action = strtolower($action);

        global $app;
        if($action == 'execjob')    return $app->rawModule == 'repo' ? !$sonarqube->exec : !empty($sonarqube->jobID);
        if($action == 'reportview') return $app->rawModule == 'repo' ? !$sonarqube->report : !empty($sonarqube->reportView);

        return true;
    }

    /**
     * 判断按钮是否显示在列表页。
     * Judge an action is displayed in browse page.
     *
     * @param  object $sonarqube
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isDisplay(object $sonarqube, string $action): bool
    {
        $action = strtolower($action);

        if(!commonModel::hasPriv('space', 'browse')) return false;

        if(!in_array($action, array('browseproject', 'reportview', 'browseissue')))
        {
            if(!commonModel::hasPriv('instance', 'manage')) return false;
        }

        return true;
    }
}
