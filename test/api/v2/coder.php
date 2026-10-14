<?php
/**
 * Offline contract checks: php test/api/v2/coder.php
 * Uses the real router, controllers, form validation and repository/SSH models.
 * Database records and external services are fixtures; no HTTP requests or writes are made.
 */
define('DS', DIRECTORY_SEPARATOR);
define('DT_DATETIME1', 'Y-m-d H:i:s');
$root = dirname(__DIR__, 3);
require $root . '/framework/api/helper.class.php';
require $root . '/framework/api/router.class.php';
require $root . '/framework/api/control.class.php';
require $root . '/framework/model.class.php';
require $root . '/lib/form/form.class.php';
require $root . '/lib/dao/dao.class.php';
foreach(array('repo', 'my', 'space') as $module)
{
    require $root . "/module/$module/control.php";
    require $root . "/module/$module/model.php";
}
require $root . '/module/my/zen.php';
require $root . '/module/repo/zen.php';
require $root . '/module/gitfox/model.php';
require $root . '/module/common/model.php';
require $root . '/module/common/tao.php';
require $root . '/config/apiv2.php';

preg_match_all("/define\('((?:TABLE_)[A-Z0-9_]+)'/", file_get_contents($root . '/config/zentaopms.php'), $tables);
foreach(array_unique($tables[1]) as $table) define($table, strtolower($table));

class coderApiRouter extends api
{
    public function setControlFile($exitIfNone = true) {return true;}
    public function getDefaultParams()
    {
        $params = array();
        foreach((new ReflectionMethod($this->moduleName, $this->methodName))->getParameters() as $param)
        {
            $params[$param->getName()] = array('default' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '_NOT_SET', 'type' => $param->hasType() ? $param->getType()->getName() : 'string');
        }
        return $params;
    }
    public function checkObjectExists($table, $ids)
    {
        return $table == TABLE_PRODUCT && array_values((array)$ids) == array(12) ? array((object)array('id' => 12)) : false;
    }
}

trait coderModelLoader
{
    public function loadModel($moduleName = '', $appName = ''): object|bool
    {
        global $models;
        return $this->$moduleName = $models[$moduleName];
    }
}

class coderRepoControl extends repo {use coderModelLoader;}
class coderRepoZen extends repoZen {use coderModelLoader;}
class coderCommonModel extends commonModel {use coderModelLoader;}

class coderWebRepo extends coderRepoControl
{
    public bool $displayed = false;
    public function commonAction(int $repoID = 0, int $objectID = 0, int $spaceID = 0) {}
    public function display($moduleName = '', $methodName = '') {$this->displayed = true;}
}

class coderWebRepoZen extends coderRepoZen
{
    protected function buildCreateRepoForm(int $objectID): void {}
}

class coderRepoModel extends repoModel
{
    use coderModelLoader;
    public array $records = array();
    public array $cloned = array();
    public array $memberships = array();
    public array $listArgs = array();
    public function getListByProduct(int $productID, int $limit = 0): array {return $this->records;}
    public function getRepoUsers(int $repoID): array {return $repoID == 9 ? array('coder') : array('other');}
    public function getListByCondition(string $repoQuery, int $space = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $this->listArgs = array($space, $orderBy);
        $repos = array_filter($this->records, function($repo) use ($space) {return $repo->space == $space;});
        [$field, $direction] = explode('_', $orderBy);
        uasort($repos, function($a, $b) use ($field, $direction) {return ($a->$field <=> $b->$field) * ($direction == 'asc' ? 1 : -1);});
        return $repos;
    }
    public function updateMembers(int $repoID, array $members): bool
    {
        $this->memberships[$repoID] = $members;
        return true;
    }
    public function processGitService(object $repo): object {return $repo;}
    public function createLink(string $method, string $params = '', string $viewType = 'html') {return "/repo/$method?$params";}
    public function getCloneUrl(object $repo): object
    {
        $this->cloned[] = $repo->id;
        return (object)array('http' => 'https://git.example.com/demo.git', 'ssh' => 'git@git.example.com:demo.git');
    }
}

class coderHTTPClient
{
    public static $response;
    public static array $requests = array();
    public function request($url, $data, $options, $headers, $type)
    {
        self::$requests[] = array('url' => $url, 'data' => $data, 'headers' => $headers);
        return self::$response;
    }
}

class coderGitfoxModel extends gitfoxModel
{
    public array $hooks = array();
    public bool $hookSuccess = true;
    public function getServer(string $account = ''): object {return (object)array('url' => 'https://git.example.com', 'token' => 'fixture');}
    public function addPushWebhook(object $repo, string $token = ''): bool|array
    {
        $this->hooks[] = clone $repo;
        return $this->hookSuccess;
    }
}

class coderSpaceModel extends spaceModel
{
    public array $records = array();
    public array $members = array();
    public array $visibleIDs = array();
    public string $requestedAccount = '';
    public function fetchByID(int $objectID, string $moduleName = ''): object|bool {return $this->records[$objectID] ?? false;}
    public function getMemberList(bool $includeRepoUsers = true): array {return $this->members;}
    public function getListByAccount(string $account, ?object $pager = null, $type = '', $queryID = 0): array|object
    {
        $this->requestedAccount = $account;
        return array_intersect_key($this->records, array_flip($this->visibleIDs));
    }
}

class coderGroupModel
{
    public array $privs = array();
    public function getDevOpsSpacePrivs(int $spaceID): ?array {return $this->privs[$spaceID] ?? null;}
}

class coderUserModel
{
    public function getPairs(string $params): array {return array('coder' => 'Coder', 'other' => 'Other');}
    public function isLogon(): bool {return true;}
}

class coderProductModel
{
    public function getProductIDByProject(int $projectID, bool $filter): array {return array(12 => 12);}
}

class coderProviderModel
{
    public function getList(): array {return array();}
}

class coderEntryModel
{
    public bool $enabled = true;
    public function getByCode(string $code): object|false {return $this->enabled ? (object)array('id' => 1) : false;}
}

class coderActionModel
{
    public array $created = array();
    public bool $fail = false;
    public function create(string $type, int $id, string $action): void
    {
        $this->created[] = array($type, $id, $action);
        if($this->fail) dao::$errors['action'] = 'Cannot save action.';
    }
}

class coderMyModel extends myModel
{
    public function loadModel($moduleName, $appName = ''): object|bool {return $this->gitfox;}
}

class coderWebMy extends my
{
    public bool $displayed = false;
    public function display($moduleName = '', $methodName = '') {$this->displayed = true;}
}

function fixture(string $class): object {return (new ReflectionClass($class))->newInstanceWithoutConstructor();}
function check(bool $condition, string $message): void
{
    global $checks;
    if(!$condition) throw new RuntimeException($message);
    $checks++;
}

$checks = 0;
$config = (object)array('edition' => 'open', 'sessionVar' => 'zentaosid', 'framework' => (object)array('filterParam' => 0));
$config->vision = 'rnd';
$config->importWhiteList = array();
require $root . '/config/filter.php';
require $root . '/module/my/config.php';
require $root . '/module/my/config/form.php';
$config->repo = new stdClass();
require $root . '/module/repo/config/form.php';
require $root . '/config/privilege.php';
$lang = (object)array('saveSuccess' => 'Saved', 'error' => (object)array('httpServerError' => 'Server error', 'notempty' => '%s is required'), 'my' => (object)array('nameFormat' => 'Invalid name', 'sshKeyTip' => 'Invalid public key'));
$lang->repo = (object)array('members' => 'Members', 'error' => (object)array('repoNameInvalid' => 'Invalid repository name'));
$lang->gitlab = (object)array('failCreateWebhook' => 'Cannot create webhook');
$repoModel = fixture('coderRepoModel');
$gitfoxModel = fixture('coderGitfoxModel');
$myModel = fixture('coderMyModel');
$myModel->gitfox = $gitfoxModel;
$spaceModel = fixture('coderSpaceModel');
$groupModel = new coderGroupModel();
$actionModel = new coderActionModel();
$entryModel = new coderEntryModel();
$models = array('repo' => $repoModel, 'my' => $myModel, 'gitfox' => $gitfoxModel, 'space' => $spaceModel, 'group' => $groupModel, 'action' => $actionModel, 'entry' => $entryModel, 'user' => new coderUserModel(), 'product' => new coderProductModel(), 'provider' => new coderProviderModel());
$common = fixture('coderCommonModel');
$common->config = $config;
common::$httpClient = new coderHTTPClient();

function request(string $verb, string $url, array $body = array(), string $products = '12', bool $admin = false, ?array $rights = null): array
{
    global $app, $config, $lang, $routes, $models, $common;
    $_GET = $_POST = $_COOKIE = array();
    $_SERVER['REQUEST_METHOD'] = $verb;
    dao::$errors = array();
    commonModel::$userPrivs = array();
    $parts = parse_url($url);
    if(isset($parts['query'])) parse_str($parts['query'], $_GET);
    $app = fixture('coderApiRouter');
    $app->config = $config;
    $app->clientLang = 'zh-cn';
    $app->lang = $lang;
    $app->apiVersion = 'v2';
    $app->path = $parts['path'];
    $app->user = (object)array('account' => 'coder', 'admin' => $admin, 'view' => (object)array('products' => $products));
    $app->company = (object)array('admins' => $admin ? 'admin,coder' : 'admin');
    $app->session = (object)array('project' => 0, 'product' => 0, 'program' => 0, 'execution' => 0);
    $app->user->rights = array('rights' => $rights ?? array('repo' => array('browse' => 1, 'maintain' => 1, 'createrepo' => 1), 'space' => array('browse' => 1)));
    $common->app = $app;
    foreach(array('repo', 'gitfox', 'my', 'space') as $name)
    {
        $model = $models[$name];
        $model->app = $app;
        $model->lang = $lang;
    }
    http_response_code(200);
    ob_start();
    try
    {
        $app->routeV2($routes);
        $app->rawModule = $app->moduleName;
        $app->rawMethod = $app->methodName;
        $control = fixture($app->moduleName == 'repo' ? 'coderRepoControl' : $app->moduleName);
        $control->app = $app;
        $control->config = $config;
        $control->lang = $lang;
        $control->moduleName = $app->moduleName;
        $control->repo = $models['repo'];
        $control->my = $models['my'];
        $control->space = $models['space'];
        $control->myZen = fixture('myZen');
        $control->myZen->lang = $lang;
        $control->myZen->config = $config;
        $control->repoZen = fixture('coderRepoZen');
        foreach(array('app', 'config', 'lang', 'repo') as $property) $control->repoZen->$property = $control->$property;
        $app->control = $control;
        $app->setRequestBody(json_encode($body));
        $app->setParams();
        $app->checkPriv();
        if(in_array($app->action, array('post', 'put', 'delete'))) $app->setFormData();
        else $app->checkAccess();
        call_user_func_array(array($control, $app->methodName), $app->params);
        $output = ob_get_contents();
    }
    catch(EndResponseException $exception)
    {
        $output = $exception->getContent();
    }
    finally
    {
        ob_end_clean();
    }
    return array(http_response_code(), json_decode($output, true, 512, JSON_THROW_ON_ERROR));
}

check($config->ajaxDependencies['repo.ajaxgetlistbyproduct'] == 'repo.browse', 'Repository method privilege must be retained.');
check(in_array('my.createssh', $config->logonMethods), 'SSH creation must retain its login requirement.');

foreach(array(7 => 'open', 8 => 'private', 9 => 'private') as $id => $acl)
{
    $repoModel->records[$id] = (object)array('id' => $id, 'name' => 'repo' . $id, 'acl' => $acl, 'password' => 'private', 'connector' => 'private');
}
[$code, $data] = request('GET', '/products/12/repos?productID=99');
check($code == 200 && $data['status'] == 'success' && array_column($data['repos'], 'id') === array(7, 9), 'Filter private repositories and preserve the path product.');
check($repoModel->cloned === array(7, 9), 'Do not fetch clone URLs for inaccessible repositories.');
check(array_keys($data['repos'][0]) === array('id', 'name', 'url'), 'Do not expose stored repository credentials.');
check($data['repos'][0]['url']['ssh'] === 'git@git.example.com:demo.git', 'Preserve clone URLs.');
check(request('GET', '/products/12/repos', array(), '99')[1]['status'] == 'fail', 'Reject invisible products.');
check(request('GET', '/products/99/repos')[1]['status'] == 'fail', 'Reject missing or deleted products.');
check(request('GET', '/products/0/repos')[0] == 400, 'Reject zero product IDs.');
$repoModel->records = array();
check(request('GET', '/products/12/repos')[1]['repos'] === array(), 'An empty repository list must stay an array.');

$key = array('name' => ' coder-key ', 'publicKey' => ' ssh-ed25519 fixture-key ', 'account' => 'other');
coderHTTPClient::$response = json_encode(array('code' => 'success', 'data' => (object)array('id' => 5)));
[$code, $data] = request('POST', '/my/sshkeys', $key);
check($code == 200 && $data['status'] == 'success' && !isset($data['load']), 'SSH success must use an API response.');
$sent = end(coderHTTPClient::$requests);
check((array)$sent['data'] === array('identifier' => 'coder-key', 'content' => 'ssh-ed25519 fixture-key'), 'Use form validation and the existing GitFox payload.');
check(in_array('Operator: coder', $sent['headers']), 'SSH keys belong to the token user regardless of body account.');
$before = count(coderHTTPClient::$requests);
foreach(array(array(), array('name' => 'coder'), array('name' => '', 'publicKey' => 'ssh-ed25519 key'), array('name' => null, 'publicKey' => 'ssh-ed25519 key'), array('name' => 'bad name', 'publicKey' => 'ssh-ed25519 key'), array('name' => 'coder', 'publicKey' => 'invalid')) as $body)
{
    [, $data] = request('POST', '/my/sshkeys', $body);
    check(($data['status'] ?? $data['result'] ?? '') == 'fail', 'Reject invalid SSH input.');
}
check(count(coderHTTPClient::$requests) == $before, 'Invalid input must not call GitFox.');
coderHTTPClient::$response = '';
check(request('POST', '/my/sshkeys', $key)[1]['status'] == 'fail', 'A failed GitFox request must not report success.');
coderHTTPClient::$response = json_encode(array('code' => 'fail', 'message' => 'key::Duplicate key'));
check(isset(request('POST', '/my/sshkeys', $key)[1]['message']['publicKey']), 'Map GitFox key errors to publicKey.');

/* Space list results are database fixtures; permission enforcement on a supplied space uses the real zen helper. */
foreach(array(10 => 'open', 20 => 'private', 30 => 'private', 40 => 'open', 50 => 'private', 60 => 'open', 70 => 'open') as $id => $acl)
{
    $spaceModel->records[$id] = (object)array('id' => $id, 'name' => 'Space ' . $id, 'code' => 'space' . $id, 'desc' => 'Description', 'acl' => $acl, 'auth' => in_array($id, array(40, 50)) ? 'reset' : 'extend', 'deleted' => $id == 60 ? 1 : 0);
}
$spaceModel->members = array(20 => array('coder' => true), 30 => array('other' => true), 50 => array('coder' => true));
$spaceModel->visibleIDs = array(10, 20, 40, 50, 70);
[$code, $data] = request('GET', '/spaces?recPerPage=2&pageID=2&account=other');
check($code == 200 && $data['status'] == 'success' && array_column($data['spaces'], 'id') === array(40, 50), 'Paginate the visible space list.');
check($spaceModel->requestedAccount === 'coder', 'Space listings must use the token account.');
check($data['pager'] === array('recTotal' => 5, 'recPerPage' => 2, 'pageID' => 2, 'pageTotal' => 3), 'Space pagination reports the visible total.');
check(array_keys($data['spaces'][0]) === array('id', 'name', 'code', 'desc', 'acl', 'auth'), 'Return only the documented space fields.');
$spaceModel->visibleIDs = array();
check(request('GET', '/spaces')[1]['spaces'] === array(), 'Empty spaces must be an array.');
$spaceModel->visibleIDs = array(10, 20, 40, 50, 70);

foreach(array('/products/12/repos' => 'GET', '/spaces' => 'GET') as $path => $verb)
{
    check(request($verb, $path, $verb == 'POST' ? $newRepo : array(), '12', false, array())[0] == 403, 'Retain method permission checks on ' . $path);
}

foreach(array('/products/12/repos' => 'GET', '/my/sshkeys' => 'POST', '/spaces' => 'GET') as $path => $allowed)
{
    foreach(array('GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS') as $verb)
    {
        if($verb == $allowed) continue;
        check(request($verb, $path)[0] == 405, "$verb $path must be rejected.");
    }
}

/* The shared SSH Web action must still render its form and request a reload on success. */
coderHTTPClient::$response = json_encode(array('code' => 'success'));
request('POST', '/my/sshkeys', $key);
$app->apiVersion = '';
$web = fixture('coderWebMy');
foreach(array('app', 'config', 'lang', 'my', 'myZen') as $property) $web->$property = $app->control->$property;
$web->view = new stdClass();
$lang->my->createSSH = 'Create SSH key';
$_POST = array();
$before = count(coderHTTPClient::$requests);
$web->createSSH();
check($web->displayed && count(coderHTTPClient::$requests) == $before, 'Web form reads must not create SSH keys.');
$_POST = $key;
try
{
    $web->createSSH();
}
catch(EndResponseException $exception)
{
    $data = json_decode($exception->getContent(), true, 512, JSON_THROW_ON_ERROR);
    check($data['status'] == 'success' && $data['load'] === true, 'Retain the shared Web action reload instruction.');
}
echo "Passed $checks offline coding API checks.\n";
