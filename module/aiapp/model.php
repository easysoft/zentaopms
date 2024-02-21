<?php

/**
 * The model file of aiapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
class aiappModel extends model
{

    /**
     * ai model.
     *
     * @var aiModel
     * @access public
     */
    public $ai;

    /**
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('ai');
    }

    /**
     * Get latest mini programs.
     *
     * @param pager $pager
     * @access public
     * @return array
     */
    public function getLatestMiniPrograms($pager = null, $order = 'publishedDate_desc')
    {
        return $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('deleted')->eq('0')
            ->andWhere('published')->eq('1')
            ->andWhere('publishedDate')->ge(date('Y-m-d H:i:s', strtotime('-1 months')))
            ->orderBy($order)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Count latest mini programs.
     *
     * @access private
     * @return int
     */
    private function countLatestMiniPrograms()
    {
        return (int)$this->dao->select('COUNT(*) as count')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('deleted')->eq('0')
            ->andWhere('published')->eq('1')
            ->andWhere('createdDate')->ge(date('Y-m-d H:i:s', strtotime('-1 months')))
            ->fetch('count');
    }

    /**
     * Save mini program message.
     *
     * @param string $appID
     * @param string $type
     * @param string $content
     * @access public
     * @return bool
     */
    public function saveMiniProgramMessage($appID, $type, $content)
    {
        $message = new stdClass();
        $message->appID = $appID;
        $message->user = $this->app->user->id;
        $message->type = $type;
        $message->content = $content;
        $message->createdDate = helper::now();

        $this->dao->insert(TABLE_AI_MESSAGE)
            ->data($message)
            ->exec();
        return !dao::isError();
    }

    /**
     * Delete history messages by id.
     *
     * @param string $appID
     * @param string $userID
     * @param array  $messageIDs
     * @access private
     * @return void
     */
    private function deleteHistoryMessagesByID($appID, $userID, $messageIDs)
    {
        $this->dao->delete()
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->andWhere('user')->eq($userID)
            ->andWhere('id')->notin($messageIDs)
            ->exec();
    }

    /**
     * Get history messages.
     *
     * @param string $appID
     * @param int    $limit
     * @access public
     * @return array
     */
    public function getHistoryMessages($appID, $limit = 20)
    {
        $messages = $this->dao->select('*')
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->andWhere('user')->eq($this->app->user->id)
            ->orderBy('createdDate_desc')
            ->limit($limit)
            ->fetchAll();

        $messageIDs = array();
        foreach($messages as $message)
        {
            $message->createdDate = date('Y/n/j G:i', strtotime($message->createdDate));
            $messageIDs[] = $message->id;
        }
        $this->deleteHistoryMessagesByID($appID, $this->app->user->id, $messageIDs);

        return $messages;
    }

    /**
     * Get collected mini programIDs
     *
     * @param string $userID
     * @param pager $pager
     * @access public
     * @return array
     */
    public function getCollectedMiniProgramIDs($userID, $pager = null)
    {
        $programs = $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAMSTAR)
            ->where('userID')->eq($userID)
            ->orderBy('createdDate_desc')
            ->page($pager)
            ->fetchAll('appID');
        return array_keys($programs);
    }

    /**
     * Get square category array.
     *
     * @param array $collectedIDs
     * @param int   $latestSum
     * @access public
     * @return array
     */
    public function getSquareCategoryArray($collectedIDs = null, $latestSum = null)
    {
        $squareCategoryArray = $this->lang->aiapp->squareCategories;

        if($collectedIDs === null) $collectedIDs = $this->getCollectedMiniProgramIDs($this->app->user->id);
        if($latestSum === null)    $latestSum    = $this->countLatestMiniPrograms();

        if(empty($collectedIDs))   unset($squareCategoryArray['collection']);
        if($latestSum == 0)        unset($squareCategoryArray['latest']);
        return $squareCategoryArray;
    }

    /**
     * Get used category array.
     *
     * @access public
     * @return array
     */
    public function getUsedCategoryArray()
    {
        $usedCustomCategories = $this->ai->getUsedCustomCategories();
        $categoryArray = array_merge($this->lang->aiapp->categoryList, $this->ai->getCustomCategories());

        $usedCategoryArray = array();
        foreach($categoryArray as $key => $value)
        {
            if(in_array($key, $usedCustomCategories)) $usedCategoryArray[$key] = $value;
        }
        return $usedCategoryArray;
    }
}
