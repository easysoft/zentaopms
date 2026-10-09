#!/usr/bin/env php
<?php
/**
 * Standalone regression tests for strict knowledge searches.
 * Run: php module/zai/test/model/searchknowledgesstrict.php
 * Uses the real model methods with mocked transport and permissions; no database or server is required.
 */

class model {}
foreach(array('STORY', 'DEMAND', 'BUG', 'DOC', 'DESIGN', 'FEEDBACK') as $table) define('TABLE_' . $table, strtolower($table));
require dirname(__DIR__, 2) . '/model.php';

set_error_handler(function(int $severity, string $message, string $file, int $line)
{
    throw new ErrorException($message, 0, $severity, $file, $line);
});

class strictKnowledgeSearchModel extends zaiModel
{
    public object $app;
    public array $models = array();
    public array $responses = array();
    public array $requests = array();
    public array $permissionCalls = array();
    public array $collections = array(1 => 'memory-one', 2 => 'memory-two');

    public function loadModel($module)
    {
        if(!isset($this->models[$module])) throw new LogicException('Unexpected model lookup.');
        return $this->models[$module];
    }

    public function callAdminAPI($path, $method = 'POST', $params = null, $postData = null)
    {
        $this->requests[] = compact('path', 'method', 'params', 'postData');
        if(!$this->responses) throw new LogicException('Unexpected upstream request.');
        return array_shift($this->responses);
    }

    public function getCollectionKey(string|int $libID): string
    {
        return $this->collections[$libID] ?? '';
    }

    public function filterKnowledgesByPriv(array $knowledges, string $type = 'content', int $limit = 0): array
    {
        $this->permissionCalls[] = compact('knowledges', 'type', 'limit');
        $visible = array_values(array_filter($knowledges, fn(array $knowledge) => $knowledge['visible'] ?? true));
        return $limit > 0 ? array_slice($visible, 0, $limit) : $visible;
    }
}

$checks = 0;
function checkSearch(bool $condition, string $message): void
{
    global $checks;
    $checks++;
    if(!$condition) throw new RuntimeException($message);
}

function checkSearchFailure(callable $search, string $message): void
{
    try
    {
        $search();
    }
    catch(RuntimeException $error)
    {
        checkSearch($error->getMessage() === 'Knowledge search failed.', $message . ': exposes upstream details or unexpected exception.');
        return;
    }
    throw new RuntimeException($message . ': did not reject the failed search.');
}

function searchHit(string $type, int $id, float $similarity, bool $visible = true): array
{
    $attrs = array('knowledgeID' => $id, 'knowledgeType' => 'text', 'knowledgeLib' => $id);
    if($type === 'chunk')
    {
        return array('chunk_id' => 'chunk-' . $id, 'content_key' => 'knowledge-' . $id, 'chunk_content' => 'Content ' . $id, 'content_attrs' => $attrs, 'similarity' => $similarity, 'visible' => $visible);
    }
    return array('id' => 'content-' . $id, 'key' => 'knowledge-' . $id, 'content' => 'Content ' . $id, 'attrs' => $attrs, 'similarity' => $similarity, 'visible' => $visible);
}

/* Legacy callers still receive an empty result; strict callers receive a sanitized exception. */
$failure = array('result' => 'fail', 'message' => 'secret-upstream-token', 'postData' => array('token' => 'secret-upstream-token'));
foreach(array('searchKnowledges' => 'content', 'searchKnowledgeChunks' => 'chunk') as $method => $type)
{
    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array($failure, $failure);
    checkSearch($zai->$method('query', 'memory-one', array()) === array(), "$type legacy failures must remain empty.");
    checkSearchFailure(fn() => $zai->$method('query', 'memory-one', array(), 5, 0.8, true, true), "$type strict upstream failure");

    $hit = searchHit($type, 1, 0.8);
    $idName = $type === 'chunk' ? 'chunk_id' : 'id';
    $keyName = $type === 'chunk' ? 'content_key' : 'key';
    $contentName = $type === 'chunk' ? 'chunk_content' : 'content';
    $attrsName = $type === 'chunk' ? 'content_attrs' : 'attrs';
    $missingID = $hit;
    unset($missingID[$idName]);
    $invalidResponses = array(
        null,
        array(),
        array('result' => 'success'),
        array('result' => 'success', 'data' => null),
        array('result' => 'success', 'data' => 'not a list'),
        array('result' => 'success', 'data' => array('items' => array($hit))),
        array('result' => 'success', 'data' => array('not a hit')),
        array('result' => 'success', 'data' => array($missingID)),
        array('result' => 'success', 'data' => array(array_replace($hit, array($idName => array())))),
        array('result' => 'success', 'data' => array(array_replace($hit, array($keyName => '')))),
        array('result' => 'success', 'data' => array(array_replace($hit, array($contentName => array())))),
        array('result' => 'success', 'data' => array(array_replace($hit, array($attrsName => 'invalid')))),
        array('result' => 'success', 'data' => array(array_replace($hit, array('similarity' => 'invalid')))),
        array('result' => 'success', 'data' => array(array_replace($hit, array('similarity' => INF)))),
        array('result' => 'success', 'data' => array(array_replace($hit, array($keyName => 'knowledge-invalid')))),
        array('result' => 'success', 'data' => array(array_replace($hit, array($keyName => 'knowledge-' . PHP_INT_MAX . '0')))),
    );
    foreach(array('objectType', 'objectID', 'knowledgeID', 'knowledgeLib', 'docType', 'knowledgeType', 'product', 'project') as $field)
    {
        $invalidAttrs = array_replace($hit[$attrsName], array($field => array('unexpected')));
        $invalidResponses[] = array('result' => 'success', 'data' => array(array_replace($hit, array($attrsName => $invalidAttrs))));
    }
    foreach(array('objectID', 'knowledgeID', 'knowledgeLib') as $field)
    {
        foreach(array('invalid', (string)PHP_INT_MAX . '0', -1, 1.5, true) as $value)
        {
            $invalidAttrs = array_replace($hit[$attrsName], array($field => $value));
            $invalidResponses[] = array('result' => 'success', 'data' => array(array_replace($hit, array($attrsName => $invalidAttrs))));
        }
    }
    foreach($invalidResponses as $index => $response)
    {
        $zai = new strictKnowledgeSearchModel();
        $zai->responses = array($response);
        checkSearchFailure(fn() => $zai->$method('query', 'memory-one', array(), 5, 0.8, false, true), "$type invalid response $index");
    }

    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array()));
    checkSearch($zai->$method('query', 'memory-one', array(), 5, 0.8, true, true) === array(), "$type empty search is valid.");

    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array($hit)));
    checkSearch($zai->$method('query', 'memory-one', array(), 5, 0.8, true, true) === array($hit), "$type successful search is unchanged.");
    checkSearch($zai->permissionCalls[0]['type'] === $type, "$type single-library permissions receive the result type.");

    $stringIDs = array_replace($hit, array($attrsName => array('knowledgeID' => '1', 'knowledgeLib' => '1', 'objectID' => '1', 'objectType' => 'story')));
    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array($stringIDs)));
    checkSearch($zai->$method('query', 'memory-one', array(), 5, 0.8, false, true) === array($stringIDs), "$type accepts IDs serialized as strings.");

    $legacyAttrs = array_replace($hit, array($attrsName => array('objectType' => array('legacy'))));
    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array($legacyAttrs)));
    checkSearch($zai->$method('query', 'memory-one', array(), 5, 0.8, false) === array($legacyAttrs), "$type does not add strict metadata validation to legacy callers.");
}

/* Multiple libraries forward filters and thresholds, then sort and apply permissions once. */
$filters = array(1 => array('attrs' => array('knowledgeType' => 'object', 'objectType' => 'story')), 2 => array('attrs' => array('knowledgeType' => 'file')));
foreach(array('chunk', 'content') as $type)
{
    $zai = new strictKnowledgeSearchModel();
    $low = searchHit($type, 1, 0.75);
    $high = searchHit($type, 2, 0.95);
    $denied = searchHit($type, 3, 0.99, false);
    $zai->responses = array(array('result' => 'success', 'data' => array($low)), array('result' => 'success', 'data' => array($denied, $high)));
    $result = $zai->searchKnowledgesInCollections('search phrase', $filters, $type, 1, 0.7, true);
    checkSearch($result === array($high), "$type merges before sorting, checking permissions and limiting.");
    checkSearch(count($zai->permissionCalls) === 1, "$type only applies permissions after merging.");
    checkSearch($zai->permissionCalls[0]['type'] === $type && $zai->permissionCalls[0]['limit'] === 1, "$type forwards the permission type and limit.");
    $endpoint = $type === 'chunk' ? 'embeddings-search-chunks' : 'embeddings-search-contents';
    foreach($zai->requests as $index => $request)
    {
        $libID = $index + 1;
        checkSearch($request['path'] === '/v8/memories/' . $zai->collections[$libID] . '/' . $endpoint, "$type chooses the correct collection and endpoint.");
        checkSearch($request['method'] === 'POST' && $request['params'] === null, "$type uses POST.");
        checkSearch($request['postData'] === array('query' => 'search phrase', 'limit' => 11, 'min_similarity' => 0.7, 'content_filter' => $filters[$libID]), "$type forwards query, overfetch limit, minimum similarity and type filters.");
    }

    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array($low)), $failure);
    checkSearchFailure(fn() => $zai->searchKnowledgesInCollections('query', $filters, $type, 5, 0.5, true), "$type fails the entire search when the second library fails");
    checkSearch($zai->permissionCalls === array(), "$type must not return partially filtered results after failure.");

    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array($low)), $failure);
    checkSearch($zai->searchKnowledgesInCollections('query', $filters, $type) === array($low), "$type legacy multi-library failures preserve other results.");

    $zai = new strictKnowledgeSearchModel();
    checkSearch($zai->searchKnowledgesInCollections('query', array(99 => array()), $type, 5, 0.5, true) === array(), "$type unsynchronized collections are empty even in strict mode.");
    checkSearch($zai->requests === array(), "$type unsynchronized collections do not call the service.");

    $zai = new strictKnowledgeSearchModel();
    $zai->responses = array(array('result' => 'success', 'data' => array($high)));
    checkSearch($zai->searchKnowledgesInCollections('query', array(99 => array(), 2 => array()), $type, 5, 0.5, true) === array($high), "$type an unsynchronized collection does not hide indexed results.");
    checkSearch(count($zai->requests) === 1, "$type only queries indexed collections.");
}

/* Exercise the real permission cache: document and API document IDs can overlap. */
class strictSearchDocumentModel
{
    public int $lookups = 0;

    public function __construct(public bool $visible) {}

    public function getByID(int $id): object
    {
        $this->lookups++;
        return (object)array('id' => $id, 'visible' => $this->visible);
    }

    public function checkPrivDoc(object $doc): bool
    {
        return $doc->visible;
    }
}

foreach(array(true, false) as $docVisible)
{
    foreach(array(array('doc', 'api'), array('api', 'doc')) as $order)
    {
        zaiModel::$objectViews = array();
        $zai = new strictKnowledgeSearchModel();
        $zai->app = (object)array('user' => (object)array('admin' => false));
        $zai->models = array('doc' => new strictSearchDocumentModel($docVisible), 'api' => new strictSearchDocumentModel(!$docVisible));
        foreach($order as $source)
        {
            $attrs = $source === 'api' ? array('docType' => 'api') : array();
            $expected = $source === 'doc' ? $docVisible : !$docVisible;
            checkSearch($zai->canViewObject('doc', 1, $attrs) === $expected, "$source permission must not reuse the other document kind's cached result.");
        }
        foreach($order as $source)
        {
            $attrs = $source === 'api' ? array('docType' => 'api') : array();
            $expected = $source === 'doc' ? $docVisible : !$docVisible;
            checkSearch($zai->canViewObject('doc', 1, $attrs) === $expected, "$source cached permission remains independent.");
            checkSearch($zai->models[$source]->lookups === 1, "$source retains permission caching for repeated checks.");
        }
    }
}

/* Orphaned document references are filtered, including references returned by the vector service. */
foreach(array('doc', 'api') as $source)
{
    zaiModel::$objectViews = array();
    $zai = new strictKnowledgeSearchModel();
    $zai->app = (object)array('user' => (object)array('admin' => false));
    $zai->models = array('doc' => new strictSearchDocumentModel(true));
    $zai->models[$source] = new class
    {
        public int $lookups = 0;
        public function getByID(int $id): bool
        {
            $this->lookups++;
            return false;
        }
        public function checkPrivDoc(object $doc): bool
        {
            throw new LogicException('A missing source must not reach document privilege checks.');
        }
    };
    $attrs = $source === 'api' ? array('docType' => 'api') : array();
    checkSearch($zai->canViewObject('doc', 999, $attrs) === false, "Missing $source references must be denied without an exception.");
    checkSearch($zai->canViewObject('doc', 999, $attrs) === false, "Missing $source references remain denied in the cache.");
    checkSearch($zai->models[$source]->lookups === 1, "Missing $source visibility is cached.");
}

echo "OK: $checks strict knowledge search checks passed.\n";
