<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pipelineModelTest extends baseTest
{
    protected $moduleName = 'pipeline';
    protected $className  = 'model';

    private function safeCall(callable $fn, $fallback = false)
    {
        ob_start();
        try { $result = $fn(); } catch(Throwable $e) { $result = $fallback; }
        ob_end_clean();
        if(dao::isError()) return $fallback;
        return $result;
    }

    public function getByIDTest(int $id) { return $this->safeCall(fn() => $this->instance->getByID($id), false); }
    public function getListTest(int $spaceID = 0, int $repoID = 0, string $type = '', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1): array { $this->instance->app->loadClass('pager', true); $pager = new pager(0, $recPerPage, $pageID); return $this->safeCall(fn() => $this->instance->getList($spaceID, $repoID, $type, '', $orderBy, $pager), array()); }
    public function getExecutionListTest(int $spaceID = 0, int $repoID = 0, string $type = '', int $pipelineID = 0, string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1): array { $this->instance->app->loadClass('pager', true); $pager = new pager(0, $recPerPage, $pageID); return $this->safeCall(fn() => $this->instance->getExecutionList($spaceID, $repoID, $type, $pipelineID, '', $orderBy, $pager), array()); }
    public function getExecutionByPipelineTest(array $pipelineIdList, bool $showLast = false): array { return $this->safeCall(fn() => $this->instance->getExecutionByPipeline($pipelineIdList, $showLast), array()); }
    public function getPairsTest(int $repoID): array { return $this->safeCall(fn() => $this->instance->getPairs($repoID), array()); }
    public function getTriggerConfigTest(object $pipeline) { return $this->safeCall(fn() => $this->instance->getTriggerConfig($pipeline), ''); }
    public function getTriggerGroupTest(string $triggerType, array $repoIdList) { return $this->safeCall(fn() => $this->instance->getTriggerGroup($triggerType, $repoIdList), array()); }
    public function createTest(object $pipeline) { return $this->safeCall(fn() => $this->instance->create($pipeline), 0); }
    public function updateTest(int $id, object $pipeline) { return $this->safeCall(fn() => $this->instance->update($id, $pipeline) ? 1 : 0, 0); }
    public function execTest(int $id, object $variables) { return $this->safeCall(fn() => $this->instance->exec($id, $variables), false); }
    public function execGitlabPipelineTest(object $pipeline, string $triggerType = 'manual') { return $this->safeCall(fn() => $this->instance->execGitlabPipeline($pipeline, $triggerType), false); }
    public function execJenkinsPipelineTest(object $pipeline, string $triggerType = 'manual') { return $this->safeCall(fn() => $this->instance->execJenkinsPipeline($pipeline, $triggerType), false); }
    public function getBySpacesTest(array $spaceIdList): array { return $this->safeCall(fn() => $this->instance->getBySpaces($spaceIdList), array()); }
    public function importFromProviderTest(object $repo, object $formData) { return $this->safeCall(fn() => $this->instance->importFromProvider($repo, $formData), 0); }
    public function isClickableTest(object $pipeline, string $action) { return $this->instance->isClickable($pipeline, $action); }
    public function getStepGroupsTest(): object|bool|array { return $this->safeCall(fn() => $this->instance->getStepGroups(), false); }
    public function getStepSchemaTest(string $stepName): string { return $this->safeCall(fn() => $this->instance->getStepSchema($stepName), ''); }
    public function updateContentTest(int $pipelineID, object $content): bool { return $this->safeCall(fn() => $this->instance->updateContent($pipelineID, $content) ? true : false, false); }
    public function parseTriggersTest(string $cron, string $events): array { return $this->instance->parseTriggers($cron, $events); }
    public function saveTriggerTest(object $trigger): void { $this->instance->saveTrigger($trigger); }
    public function getTriggersTest(int $pipelineID): array { return $this->safeCall(fn() => $this->instance->getTriggers($pipelineID), array()); }
    public function updateTriggerFieldTest(int $triggerID, string $field, string $value): void { $this->instance->updateTriggerField($triggerID, $field, $value); }
    public function deleteTriggerTest(int $triggerID): void { $this->instance->deleteTrigger($triggerID); }
    public function apiGetExecInfoTest(int $execID): object|false { return $this->safeCall(fn() => $this->instance->apiGetExecInfo($execID), false); }
    public function getExecByIDTest(int $execID): object|false { return $this->safeCall(fn() => $this->instance->getExecByID($execID), false); }
    public function formatSecondsTest(int|float $seconds): string { return $this->instance->formatSeconds($seconds); }
    public function handleWebhookTest(string $event, object $data, object $pipeline): bool { return $this->safeCall(fn() => $this->instance->handleWebhook($event, $data, $pipeline), false); }
    public function addTriggerCronJobTest(int $pipelineID, string $cronDef, string $engine = 'gitlab'): bool { return $this->safeCall(fn() => $this->instance->addTriggerCronJob($pipelineID, $cronDef, $engine), false); }
    public function deleteTriggerCronJobTest(int $pipelineID, string $engine = 'gitlab'): bool { return $this->safeCall(fn() => $this->instance->deleteTriggerCronJob($pipelineID, $engine), false); }
    public function migrateJobsToOpsPipelinesTest(): bool { return $this->safeCall(fn() => $this->instance->migrateJobsToOpsPipelines(), false); }
    public function getExternalPipelineTest(array $statusList = array()): array { return $this->safeCall(fn() => $this->instance->getExternalPipeline($statusList), array()); }
    public function syncExternalPipelineTest(): bool { return $this->safeCall(fn() => $this->instance->syncExternalPipeline(), false); }
}
