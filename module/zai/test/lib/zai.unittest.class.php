<?php
declare(strict_types = 1);
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
}
