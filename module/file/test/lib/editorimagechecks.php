<?php
declare(strict_types=1);
// Standalone regression checks for editor image source normalization and retention.
// Pure processing checks; database and disk outcomes are exercised by the ZTF integration test.
class model {}
$api = in_array('--api', $argv, true);
if($api) define('RUN_MODE', 'api');
require dirname(__DIR__, 4) . '/framework/base/router.class.php';
require dirname(__DIR__, 4) . ($api ? '/framework/api/helper.class.php' : '/framework/helper.class.php');
require isset($argv[1]) && $argv[1][0] !== '-' ? $argv[1] : dirname(__DIR__, 4) . '/module/file/model.php';

function check($condition, $message)
{
    if(!$condition) throw new RuntimeException($message);
}

class EditorImageProbe extends fileModel
{
    public $config;
    public function __construct($config) { $this->config = $config; }
}

$app = new class {
    public $apiVersion = '';
    public function getAppName() { return ''; }
    public function getViewType() { return 'html'; }
};
$config = (object)array('requestType' => 'PATH_INFO', 'webRoot' => '/zentao/', 'requestFix' => '-',
    'moduleVar' => 'm', 'methodVar' => 'f', 'viewVar' => 't', 'default' => (object)array('method' => 'index'));
$_SERVER['HTTP_HOST'] = 'retention.example';
$_SERVER['SCRIPT_NAME'] = '/zentao/index.php';
$_GET = array();
if(in_array('--parser-failure', $argv, true))
{
    // Disable JIT before the patterns are compiled, then force a deterministic engine limit.
    ini_set('pcre.jit', '0');
    ini_set('pcre.recursion_limit', '50');
    $config->requestType = 'PATH_INFO';
    $cases = array(
        'tag parser' => '<img ' . str_repeat('data-x="x" ', 100) . 'src="/zentao/file-read-101.png" />',
    );
    foreach($cases as $name => $html)
    {
        ini_set('pcre.backtrack_limit', '1000');
        $data = (object)array('spec' => $html);
        $_SESSION = array('album' => array('editor' => array(101)));
        $stopped = false;
        try { (new EditorImageProbe($config))->processImgURL($data, 'spec', 'editor'); }
        catch(EndResponseException $response)
        {
            $failure = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
            check($failure['code'] === 'editor_image_parse_failed', "$name: stable parser failure response");
            if($api)
            {
                check(http_response_code() === 400, 'API parser failure must return HTTP 400');
                check(isset($failure['error']) && str_contains($failure['error'], 'retry'), 'API error envelope with recovery guidance');
            }
            else check($failure['result'] === 'fail' && str_contains($failure['message'], 'retry'), "$name: actionable web failure response");
            $stopped = true;
        }
        check($stopped, "$name: parser failure must stop saving before association or cleanup");
        check($data->spec === $html, "$name: failed parsing must preserve the input field");
        check(empty($_SESSION['album']['used']['editor']), "$name: failure must not publish partial usage markers");
        check($_SESSION['album']['editor'] === array(101), "$name: failure must retain uploaded images for retry");
    }
    echo "PASS: parser failure stops saving without losing image references\n";
    exit;
}
$failures = array();
$checks = 0;
foreach(array('PATH_INFO', 'GET', 'API') as $mode)
{
    unset($config->originRequestType);
    $config->requestType = $mode === 'API' ? 'GET' : $mode;
    if($mode === 'API') $config->originRequestType = 'PATH_INFO';
    $relative = helper::createLink('file', 'read', 'fileID=101', 'png');
    $cases = array(
        'relative' => array('<img src="' . $relative . '" />', true),
        'absolute' => array('<img src="https://retention.example' . $relative . '" />', true),
        'newline after source' => array('<img src="https://retention.example' . $relative . '"' . "\n" . 'alt="sample">', true),
        'tab after source' => array('<img src="https://retention.example' . $relative . '"' . "\t" . 'alt="sample">', true),
        'http' => array('<img src="http://retention.example' . $relative . '" />', true),
        'authority case' => array('<img src="https://RETENTION.EXAMPLE' . $relative . '" />', true),
        'default port' => array('<img src="https://retention.example:443' . $relative . '" />', true),
        'protocol relative' => array('<img src="//retention.example' . $relative . '" />', true),
        'attribute syntax' => array("<IMG alt='a > b' SRC = 'https://retention.example" . $relative . "'/>", true),
        'unquoted source' => array('<img src=' . $relative . '>', true),
        'large attribute' => array('<img data-label=' . str_repeat('x', 100000) . ' src="' . $relative . '" />', true),
        'boolean attribute and whitespace' => array('<img' . str_repeat(' ', 10000) . 'disabled src="' . $relative . '" />', true),
        'escaped query' => array('<img src="' . htmlspecialchars($relative, ENT_QUOTES) . '">', true),
        'internal' => array('<img src="{101.png}" />', true),
        'foreign host' => array('<img src="https://external.example' . $relative . '" />', false),
        'host suffix' => array('<img src="https://retention.example.external.example' . $relative . '" />', false),
        'foreign port' => array('<img src="https://retention.example:8443' . $relative . '" />', false),
        'credentials' => array('<img src="https://external@retention.example' . $relative . '" />', false),
        'foreign path' => array('<img src="https://retention.example/other' . $relative . '" />', false),
        'data attribute' => array('<img data-src="' . $relative . '" />', false),
        'attribute text' => array('<img title=\' src="' . $relative . '" \' />', false),
        'non-image' => array('<a href="' . $relative . '">image</a>', false),
        'removed' => array('<p>Removed image</p>', false),
    );
    foreach($cases as $name => list($html, $retained))
    {
        $probe = new EditorImageProbe($config);
        $_SESSION = array('album' => array('editor' => array(101), 'other' => array(102)));
        $data = (object)array('spec' => $html, 'verify' => '<p>Acceptance</p>', 'comment' => '');
        try
        {
            $probe->processImgURL($data, 'spec,verify', 'editor');
            $once = $data->spec;
            $probe->processImgURL($data, 'spec,verify', 'editor');
            check($data->spec === $once, 'processing a stored reference again must be idempotent');
            $probe->processImgURL($data, 'comment', 'editor');
            check(isset($_SESSION['album']['used']['editor'][101]) === $retained, 'upload usage passed to association and cleanup');
            if($retained)
            {
                check(str_contains($data->spec, ' src="{101.png}" '), 'canonical renderable source');
                check(!str_contains($data->spec, '/ >'), 'valid self-closing image tag');
                if($mode === 'PATH_INFO')
                {
                    $rendered = $probe->setImgSize($data->spec);
                    check(str_contains($rendered, 'src="' . $relative . '"'), 'stored image must render as a file URL');
                    check(!str_contains($rendered, '{101.png}'), 'rendered image must not contain an internal reference');
                }
            }
            else check($data->spec === $html, 'unrelated HTML must remain unchanged');
            check($_SESSION['album']['other'] === array(102), 'other editor remains untouched');
            check($data->verify === '<p>Acceptance</p>', 'other field remains untouched');
            $checks++;
        }
        catch(Throwable $error) { $failures[] = "$mode / $name: " . $error->getMessage(); }
    }
}
if($failures) { fwrite(STDERR, implode("\n", $failures) . "\n"); exit(1); }
echo "PASS: $checks editor image retention scenarios\n";
