<?php
declare(strict_types=1);

/**
 * The test class file of solution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     solution
 * @link        https://www.zentao.net
 */
class solutionTest
{
    public function __construct()
    {
        su('admin');

        global $tester, $app;
        $this->objectModel = $tester->loadModel('solution');

        $app->session->cloudChannel = 'stable';
    }

    /**
     * Test getByID method.
     *
     * @param  int    $solutionID
     * @access public
     * @return object|null
     */
    public function getByIdTest(int $solutionID): object|null
    {
        return $this->objectModel->getByID($solutionID);
    }

    /**
     * Test getLastSolution method.
     *
     * @access public
     * @return object|false
     */
    public function getLastSolutionTest(): object|false
    {
        dao::$cache = array();
        return $this->objectModel->getLastSolution();
    }

    /**
     * Test saveLog method.
     *
     * @param  string $message
     * @access public
     * @return int
     */
    public function saveLogTest(string $message): int
    {
        global $app;
        $errorFile = $app->logRoot . 'php.' . date('Ymd') . '.log.php';
        file_put_contents($errorFile, '');

        $file = $this->objectModel->saveLog($message);
        return strlen(file_get_contents($file));
    }

    /**
     * Test saveStatus method.
     *
     * @param  int    $solutionID
     * @param  string $status
     * @access public
     * @return object|null
     */
    public function saveStatusTest(int $solutionID, string $status): object|null
    {
        $this->objectModel->saveStatus($solutionID, $status);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($solutionID);
    }

    /**
     * Init solution create data for api.
     *
     * @access private
     * @return array
     */
    private function initCreateData(): array
    {
        static $solution, $config;

        if(empty($solution))
        {
            $solution = json_decode('{"id":2,"name":"devops","title":"禅道 DevOps 解决方案","introduction":"覆盖从立项到发布，端到端的软件开发全流程跟踪。全面集成 Jenkins、Gitlab 等工具。","background_url":"https://img.qucheng.com/app/d/devops-bg.png","chart":"devops","app_version":"1.1.0","version":"2023.10.901","apps":[{"id":29,"name":"zentao","alias":"禅道开源版","chart":"zentao","app_version":"18.3","version":"2023.4.701","logo":"https://img.qucheng.com/app/z/zentao-icon.svg"},{"id":54,"name":"zentao-biz","alias":"禅道企业版","chart":"zentao-biz","app_version":"8.3","version":"2023.4.701","logo":"https://img.qucheng.com/app/z/zentao-icon.svg"},{"id":51,"name":"zentao-max","alias":"禅道旗舰版","chart":"zentao-max","app_version":"4.3","version":"2023.4.701","logo":"https://img.qucheng.com/app/z/zentao-icon.svg"},{"id":58,"name":"gitlab","alias":"GitLab","chart":"gitlab","app_version":"15.3.4","version":"2023.10.901","logo":"https://img.qucheng.com/app/g/gitlab-icon.svg"},{"id":59,"name":"jenkins","alias":"Jenkins","chart":"jenkins","app_version":"2.401.3","version":"2023.10.901","logo":"https://img.qucheng.com/app/j/jenkins-icon.svg"},{"id":60,"name":"sonarqube","alias":"SonarQube","chart":"sonarqube","app_version":"9.9.0","version":"2023.10.901","logo":"https://img.qucheng.com/app/s/sonarqube-icon.svg"},{"id":118,"name":"nexus3","alias":"Nexus3","chart":"nexus3","app_version":"3.42.0","version":"2023.10.901","logo":"https://img.qucheng.com/app/n/nexus_icon.png"}],"description":"禅道 DevOps 解决方案是一套软件全生命周期管理、跟踪、集成、发布解决方案。包含禅道项目管理工具、代码仓库、集成工具等。用户可以跟踪软件项目进度，管理代码的集成与发布。","arch_design_url":"https://img.qucheng.com/app/d/devops-arch.svg","scenes":[{"name":"项目管理","description":"软件项目的全生命周期管理"},{"name":"BUG管理","description":"BUG记录与跟踪"},{"name":"文档管理","description":"团队文档共享协同，高效办公"}],"features":[{"name":"自动集成","description":"选择的 Git、CI 应用与禅道自动对接，无需手动配置"},{"name":"代码评审","description":"代码合并前触发评审，可根据代码行、代码段提交 Bug"},{"name":"测试管理","description":"集成 ZTF 自动化测试工具，支持禅道测试用例导入和导出"},{"name":"可定制流水线","description":"根据需要定制流水线步骤，如单元测试、UI测试、镜像安全检查等"}],"customers":[{"name":"禅道","description":"https://img.qucheng.com/app/z/zentao-icon.svg","home":"https://www.zentao.net"}],"links":[{"name":"禅道使用手册","url":"https://www.zentao.net/book/zentaopms/38.html"}]}');
            $solution->apps = array_combine(helper::arrayColumn($solution->apps, 'chart'), $solution->apps);
        }

        if(empty($config)) $config = json_decode('{"category":[{"alias":"项目管理","choices":[{"app_version":"18.3","name":"zentao","version":"2023.4.701"},{"app_version":"8.3","name":"zentao-biz","version":"2023.4.701"},{"app_version":"4.3","name":"zentao-max","version":"2023.4.701"}],"external":false,"name":"pms","required":true},{"alias":"源代码管理","choices":[{"app_version":"15.3.4","external":true,"name":"gitlab","version":"2023.10.901"}],"external":false,"name":"git","required":true},{"alias":"流水线引擎","choices":[{"app_version":"2.401.3","external":false,"name":"jenkins","version":"2023.10.901"}],"name":"ci","required":true},{"alias":"代码扫描","choices":[{"app_version":"9.9.0","external":true,"name":"sonarqube","version":"2023.10.901"}],"name":"analysis","required":false},{"alias":"制品库","choices":[{"app_version":"3.42.0","external":false,"name":"nexus3","version":"2023.10.901"}],"name":"artifact","required":false}],"forms":{"gitea":[{"alias":"主机","keys":["env.GIT_DOMAIN","solution.git.host"],"name":"host","type":"string"},{"alias":"用户名","keys":["solution.git.username"],"name":"username","type":"string"},{"alias":"密码","help":{"link":"https://www.qucheng.com/book/Installation-manual/57.html","text":"jenkins需要此帐号密码来拉取代码执行构建"},"keys":["solution.git.password"],"name":"password","type":"string"},{"alias":"Token","help":{"link":"https://www.qucheng.com/book/Installation-manual/53.html","text":"禅道和jenkins需要此token调用gitea接口"},"keys":["solution.git.token","env.GIT_TOKEN"],"name":"token","type":"string"}],"gitlab":[{"alias":"主机","keys":["env.GIT_DOMAIN","solution.git.host"],"name":"host","type":"string"},{"alias":"用户名","keys":["solution.git.username"],"name":"username","type":"string"},{"alias":"密码","help":{"link":"https://www.qucheng.com/book/Installation-manual/56.html","text":"jenkins需要此帐号密码来拉取代码执行构建"},"keys":["solution.git.password"],"name":"password","type":"string"},{"alias":"Token","help":{"link":"https://www.qucheng.com/book/Installation-manual/52.html","text":"禅道和jenkins需要此token调用gitea接口"},"keys":["solution.git.token","env.GIT_TOKEN"],"name":"token","type":"string"}],"sonarqube":[{"alias":"主机","keys":["solution.sonarqube.host","env.SCAN_URL"],"name":"host","type":"string"},{"alias":"用户名","keys":["solution.sonarqube.username","env.SCAN_USERNAME"],"name":"username","type":"string"},{"alias":"密码","help":{"link":"https://www.qucheng.com/book/Installation-manual/54.html","text":"禅道需要此帐号密码来调用sonarqube相关接口"},"keys":["solution.sonarqube.password","env.SCAN_PASSWORD"],"name":"password","type":"string"},{"alias":"Token","help":{"link":"https://www.qucheng.com/book/Installation-manual/54.html","text":"禅道和jenkins需要此token调用gitea接口"},"keys":["solution.sonarqube.token"],"name":"token","type":"string"}]},"mappings":{"gitea":[{"key":"env.GIT_DOMAIN","path":"ingress.host","type":"helm"},{"key":"env.GIT_USERNAME","path":"auth.username","type":"helm"},{"key":"env.GIT_PASSWORD","path":"default_admin_password","type":"secret"},{"key":"solution.git.host","path":"ingress.host","type":"helm"},{"key":"solution.git.username","path":"z_username","type":"secret"},{"key":"solution.git.password","path":"z_password","type":"secret"},{"key":"solution.git.token","path":"api_token","type":"secret"}],"gitlab":[{"key":"env.GIT_DOMAIN","path":"ingress.host","type":"helm"},{"key":"env.GIT_USERNAME","path":"auth.username","type":"helm"},{"key":"env.GIT_PASSWORD","path":"auth.password","type":"helm"},{"key":"solution.git.host","path":"ingress.host","type":"helm"},{"key":"solution.git.username","path":"z_username","type":"secret"},{"key":"solution.git.password","path":"z_password","type":"secret"},{"key":"solution.git.token","path":"api_token","type":"secret"}],"gogs":[{"key":"env.GIT_DOMAIN","path":"ingress.host","type":"helm"},{"key":"env.GIT_USERNAME","path":"auth.username","type":"helm"},{"key":"env.GIT_PASSWORD","path":"default_admin_password","type":"secret"}],"jenkins":[{"key":"env.CI_URL","path":"ingress.host","type":"helm"},{"key":"env.CI_USERNAME","path":"auth.username","type":"helm"},{"key":"env.CI_PASSWORD","path":"jenkins_password","type":"secret"}],"sonarqube":[{"key":"solution.sonarqube.host","path":"ingress.host","type":"helm"},{"key":"solution.sonarqube.token","path":"api_token","type":"secret"},{"key":"env.SCAN_URL","path":"ingress.host","type":"helm"},{"key":"env.SCAN_USERNAME","path":"auth.username","type":"helm"},{"key":"env.SCAN_PASSWORD","path":"auth.password","type":"helm"}]},"order":["git","analysis","artifact","ci","pms"],"settings":{"gitea":[{"key":"ci.enabled","type":"static","value":"true"}],"gitlab":[{"key":"ci.enabled","type":"static","value":"true"}],"jenkins":[{"key":"initContainers.plugins.enabled","type":"static","value":"true"},{"key":"solution.enabled","type":"static","value":"true"},{"key":"solution.git.protocol","type":"auto","value":"protocol"},{"key":"solution.git.type","target":"git","type":"choose"},{"key":"solution.git.group","type":"static","value":"demo"},{"key":"solution.git.host","target":"git","type":"mappings"},{"key":"solution.git.username","target":"git","type":"mappings"},{"key":"solution.git.password","target":"git","type":"mappings"},{"key":"solution.git.token","target":"git","type":"mappings"},{"key":"solution.sonarqube.enabled","type":"static","value":"true","when":"sonarqube"},{"key":"solution.sonarqube.protocol","type":"auto","value":"protocol","when":"sonarqube"},{"key":"solution.sonarqube.host","target":"analysis","type":"mappings","when":"sonarqube"},{"key":"solution.sonarqube.token","target":"analysis","type":"mappings","when":"sonarqube"}],"sonarqube":[{"key":"ci.enabled","type":"static","value":"true"}],"zentao":[{"key":"env.LINK_GIT","type":"static","value":"true"},{"key":"env.GIT_TYPE","target":"git","type":"choose"},{"key":"env.GIT_INSTANCE_NAME","type":"static","value":"QuickOn"},{"key":"env.GIT_USERNAME","target":"git","type":"mappings"},{"key":"env.GIT_PASSWORD","target":"git","type":"mappings"},{"key":"env.GIT_TOKEN","target":"git","type":"mappings"},{"key":"env.GIT_DOMAIN","target":"git","type":"mappings"},{"key":"env.LINK_CI","type":"static","value":"true"},{"key":"env.CI_TYPE","target":"ci","type":"choose"},{"key":"env.CI_USERNAME","target":"ci","type":"mappings"},{"key":"env.CI_PASSWORD","target":"ci","type":"mappings"},{"key":"env.CI_URL","target":"ci","type":"mappings"},{"key":"env.LINK_SCAN","type":"static","value":"true"},{"key":"env.SCAN_TYPE","target":"analysis","type":"choose"},{"key":"env.SCAN_URL","target":"analysis","type":"mappings"},{"key":"env.SCAN_USERNAME","target":"analysis","type":"mappings"},{"key":"env.SCAN_PASSWORD","target":"analysis","type":"mappings"}],"zentao-biz":[{"key":"env.LINK_GIT","type":"static","value":"true"},{"key":"env.GIT_TYPE","target":"git","type":"choose"},{"key":"env.GIT_INSTANCE_NAME","type":"static","value":"QuickOn"},{"key":"env.GIT_USERNAME","target":"git","type":"mappings"},{"key":"env.GIT_PASSWORD","target":"git","type":"mappings"},{"key":"env.GIT_TOKEN","target":"git","type":"mappings"},{"key":"env.GIT_DOMAIN","target":"git","type":"mappings"},{"key":"env.LINK_CI","type":"static","value":"true"},{"key":"env.CI_TYPE","target":"ci","type":"choose"},{"key":"env.CI_USERNAME","target":"ci","type":"mappings"},{"key":"env.CI_PASSWORD","target":"ci","type":"mappings"},{"key":"env.CI_URL","target":"ci","type":"mappings"},{"key":"env.LINK_SCAN","type":"static","value":"true"},{"key":"env.SCAN_TYPE","target":"analysis","type":"choose"},{"key":"env.SCAN_URL","target":"analysis","type":"mappings"},{"key":"env.SCAN_USERNAME","target":"analysis","type":"mappings"},{"key":"env.SCAN_PASSWORD","target":"analysis","type":"mappings"}],"zentao-max":[{"key":"env.LINK_GIT","type":"static","value":"true"},{"key":"env.GIT_TYPE","target":"git","type":"choose"},{"key":"env.GIT_INSTANCE_NAME","type":"static","value":"QuickOn"},{"key":"env.GIT_USERNAME","target":"git","type":"mappings"},{"key":"env.GIT_PASSWORD","target":"git","type":"mappings"},{"key":"env.GIT_TOKEN","target":"git","type":"mappings"},{"key":"env.GIT_DOMAIN","target":"git","type":"mappings"},{"key":"env.LINK_CI","type":"static","value":"true"},{"key":"env.CI_TYPE","target":"ci","type":"choose"},{"key":"env.CI_USERNAME","target":"ci","type":"mappings"},{"key":"env.CI_PASSWORD","target":"ci","type":"mappings"},{"key":"env.CI_URL","target":"ci","type":"mappings"},{"key":"env.LINK_SCAN","type":"static","value":"true"},{"key":"env.SCAN_TYPE","target":"analysis","type":"choose"},{"key":"env.SCAN_URL","target":"analysis","type":"mappings"},{"key":"env.SCAN_USERNAME","target":"analysis","type":"mappings"},{"key":"env.SCAN_PASSWORD","target":"analysis","type":"mappings"}]}}');
        return array($solution, $config);
    }

    /**
     * Test create method.
     *
     * @param  array  $postData
     * @access public
     * @return object|null
     */
    public function createTest(array $postData): object|null
    {
        global $app;
        $app->user = new stdclass();
        foreach($postData as $key => $value) $app->post->set($key, $value);

        list($cloudSolution, $components) = $this->initCreateData();
        $solution = $this->objectModel->create($cloudSolution, $components);

        foreach($postData as $key => $value) $app->post->set($key, '');
        return $solution;
    }
}
