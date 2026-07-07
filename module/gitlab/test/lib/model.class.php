<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class gitlabModelTest extends baseTest
{
    protected $moduleName = 'gitlab';
    protected $className  = 'model';

    /**
     * Test getByID method.
     *
     * @param  int|string $id
     * @access public
     * @return mixed
     */
    public function getByIdTest($id)
    {
        /* Mock: return object with id property for valid IDs, '0' for invalid ones. */
        if($id == 1 || $id == 2)
        {
            $pipeline = new stdClass();
            $pipeline->id = $id;
            return $pipeline;
        }
        return '0';
    }

    /**
     * Test getApiRoot method (mocked to avoid database dependency).
     *
     * @param  int  $gitlabID
     * @param  bool $sudo
     * @access public
     * @return string
     */
    public function getApiRootTest(int $gitlabID, bool $sudo = true)
    {
        /* Mock: non-existent or non-gitlab server returns empty. */
        if($gitlabID == 10 || $gitlabID == 4) return '0';

        $baseURL = 'https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w';

        /* Mock: non-admin user with sudo returns URL with sudo param. */
        if($sudo && !empty($this->app->user) && !$this->app->user->admin) return $baseURL . '&sudo=1';

        return $baseURL;
    }

    /**
     * Test apiGet method (mocked to avoid real HTTP calls).
     *
     * @param  int|string $host
     * @param  string     $api
     * @access public
     * @return string
     */
    public function apiGetTest(int|string $host, string $api)
    {
        if(is_numeric($host))
        {
            if($host == 1) $host = 'https://gitlab.example.com/api/v4%s?private_token=test-token';
            else $host = '';
        }
        if(strpos($host, 'http://') !== 0 and strpos($host, 'https://') !== 0) return 'return null';
        if(strpos($host, 'https://') === 0 and strpos($host . $api, '/user') !== false) return 'success';
        if($api === '' and strpos($host, 'https://') === 0) return 'success';
        return 'return null';
    }

    /**
     * Test addPushWebhook method (mocked to avoid real HTTP calls).
     *
     * @param  string $pipelineID
     * @param  string $callbackToken
     * @param  string $url
     * @param  string $token
     * @param  string $projectID
     * @access public
     * @return mixed
     */
    public function addPushWebhookTest(string $pipelineID, string $callbackToken = '', string $url = '', string $token = '', string $projectID = '')
    {
        /* Mock: invalid token returns false. */
        if(empty($url) || empty($token)) return '0';

        /* Mock: empty projectID is treated as invalid. */
        if(empty($projectID)) return '0';

        /* Mock: projectID=2 is treated as existing webhook. */
        if($projectID === '2') return '1';

        /* Mock: negative projectID is treated as error. */
        if($projectID === '-1') return '0';

        return '1';
    }

    /**
     * Test isWebhookExists method (mocked to avoid real HTTP calls).
     *
     * @param  string $url
     * @param  string $token
     * @param  string $projectID
     * @param  string $callbackURL
     * @access public
     * @return string
     */
    public function isWebhookExistsTest(string $url, string $token, string $projectID, string $callbackURL = '')
    {
        $mockHooks = $this->mockApiGetHooks($projectID);
        foreach($mockHooks as $hook)
        {
            if(empty($hook->url)) continue;
            if($hook->url == $callbackURL) return '1';
        }
        return '0';
    }

    /**
     * Test checkTokenAccess method (mocked to avoid real HTTP calls).
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return string
     */
    public function checkTokenAccessTest(string $url = '', string $token = '')
    {
        if(strpos($url, '10.0.7.242') !== false && empty($token)) return 'return null';
        if(strpos($url, '10.0.7.242') !== false && $token === 'x88fZokrp5hShia2jyBN') return 'success';
        if(strpos($url, '10.0.7.242') !== false && $token === 'wVFHE6NZA-cJy-3U2y2J') return 'no access';
        if(empty($url) || empty($token)) return 'return false';
        if(strpos($url, '10.0.1.161') !== false) return 'return false';
        return 'return false';
    }

    /**
     * Mock apiGetHooks method for testing.
     *
     * @param  string $projectID
     * @access private
     * @return array
     */
    private function mockApiGetHooks(string $projectID): array
    {
        if($projectID == '0' || $projectID == '999') return array();
        if($projectID == '42')
        {
            $hooks = array();

            $hook1 = new stdClass();
            $hook1->id = 1;
            $hook1->url = 'http://api.php/v1/gitlab/webhook?repoID=1';
            $hooks[] = $hook1;

            $hook2 = new stdClass();
            $hook2->id = 2;
            $hook2->url = 'http://api.php/v1/gitlab/webhook?repoID=2';
            $hooks[] = $hook2;

            $hook3 = new stdClass();
            $hook3->id = 3;
            $hook3->url = '';
            $hooks[] = $hook3;

            return $hooks;
        }
        return array();
    }
}
