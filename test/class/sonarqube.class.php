<?php
class sonarqubeTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('sonarqube');
    }

    public function apiCreateProjectTest($sonarqubeID, $project = array())
    {
        $result = $this->objectModel->apiCreateProject($sonarqubeID, (object)$project);

        if($result === false) $result = 'return false';
        if(isset($result->errors)) $result = $result->errors;
        return $result;
    }

    public function apiDeleteProjectTest($sonarqubeID, $projectKey)
    {
        $result = $this->objectModel->apiDeleteProject($sonarqubeID, $projectKey);

        if($result === false) $result = 'return false';
        if($result === null) $result = 'return true';
        if(isset($result->errors)) $result = $result->errors;
        return $result;
    }

    public function apiGetIssuesTest($sonarqubeID, $projectKey = '')
    {
        $result = $this->objectModel->apiGetIssues($sonarqubeID, $projectKey);

        if(empty($result)) $result = 'return empty';
        if(isset($result[0]->message)) $result = true;
        return $result;
    }

    public function apiGetProjectsTest($sonarqubeID, $keyword = '')
    {
        $result = $this->objectModel->apiGetProjects($sonarqubeID, $keyword);

        if(empty($result)) $result = 'return empty';
        if($keyword == '' and isset($result[0]->name)) $result = true;
        if($keyword != '' and strpos($result[0]->name, $keyword) !== false) $result = true;
        return $result;
    }
}
