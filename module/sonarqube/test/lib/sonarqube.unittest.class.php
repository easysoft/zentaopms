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
        if(isset($result->errors))
        {
            $result = $result->errors;
            if($result[0]->msg == 'Could not create Project with key: "' . $project['projectKey'] . '". A similar key already exists: "' . $project['projectKey'] . '"') return true;
        }
        if(isset($result->name) && $result->name == $project['projectName']) return true;
        return $result;
    }

    public function createProjectTest($sonarqubeID, $post)
    {
        $result = $this->objectModel->createProject($sonarqubeID, (object)$post);
        $errors = dao::getError();
        if($errors)
        {
            if(current($errors['name']) == "项目标识的格式不正确。允许的字符为字母、数字、'-'、''、'.'和“：”，至少有一个非数字。") return 'return error';
            if(current($errors['name']) == false) return 'return false';
            if(current($errors['name']) == 'Could not create Project with key: "' . $post['projectKey'] . '". A similar key already exists: "' . $post['projectKey'] . '"') return true;
            return $errors;
        }
        else
        {
            if(empty($result)) $result = 'return false';
            return $result;
        }
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
        return $result;
    }

    public function apiGetProjectsTest($sonarqubeID, $keyword = '')
    {
        $result = $this->objectModel->apiGetProjects($sonarqubeID, $keyword);

        if(empty($result)) $result = 'return empty';
        return $result;
    }

    public function apiValidateTest($sonarqubeID, $url = '', $token = '')
    {
        global $tester;
        $sonarqubeServer = $tester->loadModel('pipeline')->getByID($sonarqubeID);
        $url   = $url ? $url : $sonarqubeServer->url;
        $token = $token ? $token : $sonarqubeServer->token;

        $result = $this->objectModel->apiValidate($url, $token);

        if(empty($result)) $result = 'success';
        if(isset($result['password'])) $result = 'return false';
        if(isset($result['account'])) $result = 'return false';
        return $result;
    }

    public function getApiBaseTest($sonarqubeID)
    {
        list($apiRoot, $header) = $this->objectModel->getApiBase($sonarqubeID);

        if(empty($apiRoot)) return 'return empty';
        return $apiRoot;
    }

    public function getCacheFileTest($sonarqubeID, $projectKey)
    {
        $result = $this->objectModel->getCacheFile($sonarqubeID, $projectKey);

        if(strPos($result, '/' . $sonarqubeID . '-' ) !== false) return true;
        if($result === false) return true;
        return $result;
    }
}
