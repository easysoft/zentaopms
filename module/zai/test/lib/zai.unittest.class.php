<?php
declare(strict_types = 1);

helper::import(dirname(__FILE__, 3) . '/model.php');

class zaiTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('zai');
    }

    public function getSettingTest($includeAdmin = false): ?object
    {
        $result = $this->objectModel->getSetting($includeAdmin);
        return $result;
    }

    /**
     * Test getToken method.
     *
     * @param  object|null $zaiConfig
     * @param  bool $admin
     * @access public
     * @return array
     */
    public function getTokenTest($zaiConfig = null, $admin = false)
    {
        $result = $this->objectModel->getToken($zaiConfig, $admin);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test formatOldSetting method.
     *
     * @param  object|null $setting
     * @access public
     * @return object|null
     */
    public function formatOldSettingTest($setting)
    {
        $result = $this->objectModel->formatOldSetting($setting);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setSetting method.
     *
     * @param  object|null $setting
     * @access public
     * @return mixed
     */
    public function setSettingTest($setting)
    {
        $this->objectModel->setSetting($setting);
        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Test getVectorizedInfo method.
     *
     * @access public
     * @return object
     */
    public function getVectorizedInfoTest()
    {
        $result = $this->objectModel->getVectorizedInfo();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setVectorizedInfo method.
     *
     * @param  object $info
     * @access public
     * @return mixed
     */
    public function setVectorizedInfoTest($info)
    {
        $this->objectModel->setVectorizedInfo($info);
        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Test getNextTarget method.
     *
     * @param  string $type
     * @param  int $id
     * @access public
     * @return object|null
     */
    public function getNextTargetTest($type, $id)
    {
        $result = $this->objectModel->getNextTarget($type, $id);
        if(dao::isError()) return dao::getError();

        if(is_null($result) || $result === false) return false;
        if(is_object($result) && empty((array)$result)) return false;

        return $result;
    }

    /**
     * Test getNextSyncType static method.
     *
     * @param  string $currentType
     * @access public
     * @return string
     */
    public function getNextSyncTypeTest($currentType = '')
    {
        return zaiModel::getNextSyncType($currentType);
    }

    /**
     * Test getSyncTypes static method.
     *
     * @access public
     * @return array
     */
    public function getSyncTypesTest()
    {
        return zaiModel::getSyncTypes();
    }

    /**
     * Test syncNextTarget method.
     *
     * @param  string $memoryID
     * @param  string $type
     * @param  int $id
     * @access public
     * @return array|null
     */
    public function syncNextTargetTest($memoryID, $type, $id)
    {
        $result = $this->objectModel->syncNextTarget($memoryID, $type, $id);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test convertTargetToMarkdown static method.
     *
     * @param  string $type
     * @param  object $target
     * @access public
     * @return array
     */
    public function convertTargetToMarkdownTest($type, $target)
    {
        return zaiModel::convertTargetToMarkdown($type, $target);
    }

    /**
     * Test convertGenericToMarkdown protected static method via reflection.
     *
     * @param string $type
     * @param object $target
     * @access public
     * @return array
     */
    public function convertGenericToMarkdownTest($type, $target)
    {
        return zaiModel::convertGenericToMarkdown($type, $target);
    }

    /**
     * Test formatFieldValue protected static method.
     *
     * @param array  $langData
     * @param string $field
     * @param mixed  $value
     * @access public
     * @return string
     */
    public function formatFieldValueTest(array $langData, string $field, $value): string
    {
        return zaiModel::formatFieldValue($langData, $field, $value);
    }

    /**
     * Test getFieldLabel protected static method.
     *
     * @param array  $langData
     * @param string $field
     * @access public
     * @return string
     */
    public function getFieldLabelTest(array $langData, string $field): string
    {
        return zaiModel::getFieldLabel($langData, $field);
    }

    /**
     * Test getSectionLabel protected static method.
     *
     * @param array  $langData
     * @param string $section
     * @access public
     * @return string
     */
    public function getSectionLabelTest(array $langData, string $section): string
    {
        return zaiModel::getSectionLabel($langData, $section);
    }

    /**
     * Test convertStoryToMarkdown static method.
     *
     * @param  object $story
     * @access public
     * @return array
     */
    public function convertStoryToMarkdownTest($story)
    {
        return zaiModel::convertStoryToMarkdown($story);
    }

    /**
     * Test convertDemandToMarkdown static method.
     *
     * @param  object $demand
     * @access public
     * @return array
     */
    public function convertDemandToMarkdownTest($demand)
    {
        global $tester;
        try {$tester->loadLang('demand');} catch (Exception $e) {}
        if(!isset($tester->lang->demand))
        {
            $tester->lang->demand = new stdClass();
            $tester->lang->demand->common = '需求';
            $tester->lang->demand->legendBasicInfo = '基本信息';
            $tester->lang->demand->spec = '规格';
            $tester->lang->demand->verify = '验证';
            $tester->lang->demand->status = '状态';
            $tester->lang->demand->stage = '阶段';
            $tester->lang->demand->pri = '优先级';
            $tester->lang->demand->version = '版本';
            $tester->lang->demand->category = '分类';
            $tester->lang->demand->source = '来源';
            $tester->lang->demand->product = '产品';
            $tester->lang->demand->parent = '父需求';
            $tester->lang->demand->module = '模块';
            $tester->lang->demand->keywords = '关键词';
            $tester->lang->demand->assignedTo = '指派给';
            $tester->lang->demand->assignedDate = '指派日期';
            $tester->lang->demand->createdBy = '创建者';
            $tester->lang->demand->createdDate = '创建日期';
            $tester->lang->demand->changedBy = '修改者';
            $tester->lang->demand->changedDate = '修改日期';
            $tester->lang->demand->closedBy = '关闭者';
            $tester->lang->demand->closedDate = '关闭日期';
            $tester->lang->demand->closedReason = '关闭原因';
            $tester->lang->demand->submitedBy = '提交者';
            $tester->lang->demand->distributedBy = '分发者';
            $tester->lang->demand->distributedDate = '分发日期';
            $tester->lang->demand->statusList = array('draft' => '草稿', 'active' => '激活', 'closed' => '关闭', 'changing' => '变更', 'reviewing' => '评审');
            $tester->lang->demand->stageList = array('wait' => '待办', 'planned' => '计划', 'doing' => '进行中', 'done' => '已完成', 'canceled' => '已取消');
            $tester->lang->demand->priList = array('1' => '低', '2' => '中', '3' => '高', '4' => '紧急');
            $tester->lang->demand->categoryList = array('feature' => '功能', 'interface' => '接口', 'performance' => '性能', 'security' => '安全', 'other' => '其他');
            $tester->lang->demand->sourceList = array('customer' => '客户', 'user' => '用户', 'po' => '产品负责人', 'market' => '市场', 'service' => '服务', 'operation' => '运营', 'support' => '支持', 'competitor' => '竞争对手', 'partner' => '合作伙伴', 'dev' => '开发', 'tester' => '测试', 'bug' => '缺陷', 'forum' => '论坛', 'other' => '其他');
        }

        return zaiModel::convertDemandToMarkdown($demand);
    }

    /**
     * Test convertBugToMarkdown static method.
     *
     * @param  object $bug
     * @access public
     * @return array
     */
    public function convertBugToMarkdownTest($bug)
    {
        return zaiModel::convertBugToMarkdown($bug);
    }

    /**
     * Test convertTaskToMarkdown static method.
     *
     * @param  object $task
     * @access public
     * @return array
     */
    public function convertTaskToMarkdownTest($task)
    {
        return zaiModel::convertTaskToMarkdown($task);
    }

    /**
     * Test convertDocToMarkdown static method.
     *
     * @param  object $doc
     * @access public
     * @return array
     */
    public function convertDocToMarkdownTest($doc)
    {
        return zaiModel::convertDocToMarkdown($doc);
    }

    /**
     * Test convertDesignToMarkdown static method.
     *
     * @param  object $design
     * @access public
     * @return array
     */
    public function convertDesignToMarkdownTest($design)
    {
        return zaiModel::convertDesignToMarkdown($design);
    }

    /**
     * Test convertFeedbackToMarkdown static method.
     *
     * @param  object $feedback
     * @access public
     * @return array
     */
    public function convertFeedbackToMarkdownTest($feedback)
    {
        global $tester;
        try {$tester->loadLang('feedback');} catch (Exception $e) {}
        if(!isset($tester->lang->feedback))
        {
            $tester->lang->feedback = new stdClass();
            $tester->lang->feedback->common = '反馈';
            $tester->lang->feedback->labelBasic = '基本信息';
            $tester->lang->feedback->type = '类型';
            $tester->lang->feedback->pri = '优先级';
            $tester->lang->feedback->status = '状态';
            $tester->lang->feedback->solution = '解决方案';
            $tester->lang->feedback->product = '产品';
            $tester->lang->feedback->module = '模块';
            $tester->lang->feedback->openedBy = '创建者';
            $tester->lang->feedback->openedDate = '创建日期';
            $tester->lang->feedback->assignedTo = '指派给';
            $tester->lang->feedback->assignedDate = '指派日期';
            $tester->lang->feedback->reviewedBy = '评审者';
            $tester->lang->feedback->reviewedDate = '评审日期';
            $tester->lang->feedback->closedBy = '关闭者';
            $tester->lang->feedback->feedbackBy = '反馈者';
            $tester->lang->feedback->closedDate = '关闭日期';
            $tester->lang->feedback->closedReason = '关闭原因';
            $tester->lang->feedback->processedBy = '处理者';
            $tester->lang->feedback->processedDate = '处理日期';
            $tester->lang->feedback->source = '来源';
            $tester->lang->feedback->result = '结果';
            $tester->lang->feedback->keywords = '关键词';
            $tester->lang->feedback->faq = 'FAQ';
            $tester->lang->feedback->desc = '描述';
            $tester->lang->feedback->statusList = array('draft' => '草稿', 'active' => '激活', 'closed' => '关闭', 'changing' => '变更', 'reviewing' => '评审');
            $tester->lang->feedback->priList = array('1' => '低', '2' => '中', '3' => '高', '4' => '紧急');
            $tester->lang->feedback->typeList = array('bug' => '缺陷', 'feature' => '功能', 'interface' => '接口', 'performance' => '性能', 'security' => '安全', 'other' => '其他');
            $tester->lang->feedback->solutionList = array('none' => '无', 'implemented' => '已实现', 'notImplemented' => '未实现');
            $tester->lang->feedback->closedReasonList = array('duplicate' => '重复', 'notReproducible' => '无法重现', 'asExpected' => '符合预期', 'other' => '其他');
            $tester->lang->feedback->publicList = array('public' => '公开', 'private' => '私有');
        }
        return zaiModel::convertFeedbackToMarkdown($feedback);
    }

    /**
     * Test enableVectorization method.
     *
     * @param  bool $force
     * @access public
     * @return array
     */
    public function enableVectorizationTest($force = false)
    {
        $result = $this->objectModel->enableVectorization($force);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test callAPI method.
     *
     * @param  string $path
     * @param  string $method
     * @param  array|null $params
     * @param  array|null $postData
     * @param  bool $admin
     * @access public
     * @return array
     */
    public function callAPITest($path, $method = 'POST', $params = null, $postData = null, $admin = false)
    {
        $result = $this->objectModel->callAPI($path, $method, $params, $postData, $admin);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test callAdminAPI method.
     *
     * @param  string $path
     * @param  string $method
     * @param  array|null $params
     * @param  array|null $postData
     * @access public
     * @return array
     */
    public function callAdminAPITest($path, $method = 'POST', $params = null, $postData = null)
    {
        $result = $this->objectModel->callAdminAPI($path, $method, $params, $postData);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test canViewObject method.
     *
     * @param  string $objectType
     * @param  int $objectID
     * @param  array|null $attrs
     * @access public
     * @return bool
     */
    public function canViewObjectTest($objectType, $objectID, $attrs = null)
    {
        $result = $this->objectModel->canViewObject($objectType, $objectID, $attrs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test searchKnowledges method.
     *
     * @param  string $query
     * @param  string $collection
     * @param  array $filter
     * @param  int $limit
     * @param  float $minSimilarity
     * @access public
     * @return array
     */
    public function searchKnowledgesTest($query, $collection, $filter, $limit = 20, $minSimilarity = 0.5)
    {
        $result = $this->objectModel->searchKnowledges($query, $collection, $filter, $limit, $minSimilarity);
        return $result;
    }

    /**
     * Test searchKnowledgeChunks method.
     *
     * @param  string $query
     * @param  string $collection
     * @param  array $filter
     * @param  int $limit
     * @param  float $minSimilarity
     * @access public
     * @return array
     */
    public function searchKnowledgeChunksTest($query, $collection, $filter, $limit = 20, $minSimilarity = 0.5)
    {
        $result = $this->objectModel->searchKnowledgeChunks($query, $collection, $filter, $limit, $minSimilarity);
        return $result;
    }

    /**
     * Test getCollectionKey method.
     *
     * @param  string|int $collection
     * @access public
     * @return string
     */
    public function getCollectionKeyTest($collection)
    {
        $result = $this->objectModel->getCollectionKey($collection);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test collectFieldPairs static method.
     *
     * @param  string $objectType
     * @param  array  $langData
     * @param  object $target
     * @access public
     * @return array
     */
    public function collectFieldPairsTest(string $objectType, array $langData, object $target): array
    {
        return zaiModel::collectFieldPairs($objectType, $langData, $target);
    }

    /**
     * Test getFieldAliasMap static method.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getFieldAliasMapTest(string $objectType): array
    {
        return zaiModel::getFieldAliasMap($objectType);
    }

    /**
     * Test extractFieldValue static method.
     *
     * @param  string $objectType
     * @param  string $field
     * @param  object $target
     * @access public
     * @return mixed
     */
    public function extractFieldValueTest(string $objectType, string $field, object $target)
    {
        return zaiModel::extractFieldValue($objectType, $field, $target);
    }

    /**
     * Test getUserFieldsMap static method.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getUserFieldsMapTest(string $objectType): array
    {
        return zaiModel::getUserFieldsMap($objectType);
    }

    /**
     * Test convertUserFieldToRealname static method.
     *
     * @param  string $objectType
     * @param  object $target
     * @access public
     * @return object
     */
    public function convertUserFieldToRealnameTest(string $objectType, object $target): object
    {
        return zaiModel::convertUserFieldToRealname($objectType, $target);
    }

    /**
     * Test convertCaseToMarkdown static method.
     *
     * @param  object     $case
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertCaseToMarkdownTest($case, ?array $langData = null): array
    {
        return zaiModel::convertCaseToMarkdown($case, $langData);
    }

    /**
     * Test convertIssueToMarkdown static method.
     *
     * @param  object     $issue
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertIssueToMarkdownTest($issue, ?array $langData = null): array
    {
        return zaiModel::convertIssueToMarkdown($issue, $langData);
    }

    /**
     * Test convertRiskToMarkdown static method.
     *
     * @param  object     $risk
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertRiskToMarkdownTest($risk, ?array $langData = null): array
    {
        return zaiModel::convertRiskToMarkdown($risk, $langData);
    }

    /**
     * Test convertPlanToMarkdown static method.
     *
     * @param  object     $plan
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertPlanToMarkdownTest($plan, ?array $langData = null): array
    {
        return zaiModel::convertPlanToMarkdown($plan, $langData);
    }

    /**
     * Test convertOpportunityToMarkdown static method.
     *
     * @param  object     $opportunity
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertOpportunityToMarkdownTest($opportunity, ?array $langData = null): array
    {
        return zaiModel::convertOpportunityToMarkdown($opportunity, $langData);
    }

    /**
     * Test convertReleaseToMarkdown static method.
     *
     * @param  object     $release
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertReleaseToMarkdownTest($release, ?array $langData = null): array
    {
        return zaiModel::convertReleaseToMarkdown($release, $langData);
    }

    /**
     * Test convertTicketToMarkdown static method.
     *
     * @param  object     $ticket
     * @param  array      $langData
     * @access public
     * @return array
     */
    public function convertTicketToMarkdownTest($ticket, ?array $langData = null): array
    {
        return zaiModel::convertTicketToMarkdown($ticket, $langData);
    }

    /**
     * Test searchKnowledgesInCollections method.
     *
     * @param  string $query
     * @param  array $filters
     * @param  string $type
     * @param  int $limit
     * @param  float $minSimilarity
     * @access public
     * @return array
     */
    public function searchKnowledgesInCollectionsTest($query, $filters, $type = 'content', $limit = 20, $minSimilarity = 0.5)
    {
        $result = $this->objectModel->searchKnowledgesInCollections($query, $filters, $type, $limit, $minSimilarity);
        return $result;
    }

    /**
     * Test filterKnowledgesByPriv method.
     *
     * @param  array $knowledges
     * @param  string $type
     * @param  int $limit
     * @access public
     * @return array
     */
    public function filterKnowledgesByPrivTest($knowledges, $type = 'content', $limit = 0)
    {
        $result = $this->objectModel->filterKnowledgesByPriv($knowledges, $type, $limit);
        return $result;
    }

    /**
     * Test appendFieldList static method.
     *
     * @param  array $content
     * @param  array $langData
     * @param  array $fieldPairs
     * @access public
     * @return array
     */
    public function appendFieldListTest(array $content, array $langData, array $fieldPairs): array
    {
        zaiModel::appendFieldList($content, $langData, $fieldPairs);
        return $content;
    }

    /**
     * Test appendDetailSection static method.
     *
     * @param  array  $content
     * @param  array  $langData
     * @param  string $sectionKey
     * @param  mixed  $rawValue
     * @access public
     * @return array
     */
    public function appendDetailSectionTest(array $content, array $langData, string $sectionKey, $rawValue): array
    {
        zaiModel::appendDetailSection($content, $langData, $sectionKey, $rawValue);
        return $content;
    }

    /**
     * Test appendMilestoneSection static method.
     *
     * @param  array $content
     * @param  array $langData
     * @param  mixed $milestones
     * @access public
     * @return array
     */
    public function appendMilestoneSectionTest(array $content, array $langData, $milestones): array
    {
        zaiModel::appendMilestoneSection($content, $langData, $milestones);
        return $content;
    }

    /**
     * Test buildCaseStepsText static method.
     *
     * @param  mixed $steps
     * @param  array $langData
     * @access public
     * @return string
     */
    public function buildCaseStepsTextTest($steps, array $langData): string
    {
        return zaiModel::buildCaseStepsText($steps, $langData);
    }

    /**
     * Test createKnowledgeLib method.
     *
     * @param  string $name
     * @param  string $description
     * @param  array|null $options
     * @access public
     * @return mixed
     */
    public function createKnowledgeLibTest(string $name, string $description = '', ?array $options = null)
    {
        $result = $this->objectModel->createKnowledgeLib($name, $description, $options);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteKnowledgeLib method.
     *
     * @param  string $memoryID
     * @access public
     * @return mixed
     */
    public function deleteKnowledgeLibTest(string $memoryID)
    {
        $result = $this->objectModel->deleteKnowledgeLib($memoryID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateKnowledgeItem method.
     *
     * @param  string $memoryID
     * @param  string $key
     * @param  string $content
     * @param  string $contentType
     * @param  array|null $attrs
     * @access public
     * @return mixed
     */
    public function updateKnowledgeItemTest(string $memoryID, string $key, string $content, string $contentType = 'markdown', ?array $attrs = null)
    {
        $result = $this->objectModel->updateKnowledgeItem($memoryID, $key, $content, $contentType, $attrs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteKnowledgeItem method.
     *
     * @param  string $memoryID
     * @param  string $key
     * @access public
     * @return mixed
     */
    public function deleteKnowledgeItemTest(string $memoryID, string $key)
    {
        $result = $this->objectModel->deleteKnowledgeItem($memoryID, $key);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getKnowledgeChunks method.
     *
     * @param  string $memoryID
     * @param  string $contentID
     * @access public
     * @return mixed
     */
    public function getKnowledgeChunksTest(string $memoryID, string $contentID)
    {
        $result = $this->objectModel->getKnowledgeChunks($memoryID, $contentID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test extractFileContent method.
     *
     * @param  int $fileID
     * @access public
     * @return mixed
     */
    public function extractFileContentTest(int $fileID)
    {
        $result = $this->objectModel->extractFileContent($fileID);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
