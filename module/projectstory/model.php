<?php
declare(strict_types=1);
/**
 * The model file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectStory
 * @version     $Id
 * @link        https://www.zentao.net
 */
class projectstoryModel extends model
{
    /**
     * Get the stories for execution linked.
     *
     * @param  int    $projectID
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getExecutionStories($projectID, $storyIdList = array())
    {
        $stories     = array();
        $storyIdList = (array)$storyIdList;

        if(empty($storyIdList)) return $stories;

        return $this->dao->select('t2.id as id, t2.title as title, t3.id as executionID, t3.name as execution')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.story')->in($storyIdList)
            ->andWhere('t3.type')->in('sprint,stage,kanban')
            ->andWhere('t3.project')->eq($projectID)
            ->andWhere('t3.deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Build search config for project story list.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function buildSearchConfig(int $projectID): array
    {
        $this->loadModel('product');

        $searchConfig = $this->config->product->search;

        $this->lang->story->title = $this->lang->story->name;

        $project = $this->loadModel('project')->fetchById($projectID);

        unset($searchConfig['params']['product']);
        unset($searchConfig['fields']['product']);
        unset($searchConfig['fields']['branch']);
        unset($searchConfig['params']['branch']);
        unset($searchConfig['params']['project']);
        unset($searchConfig['fields']['project']);

        $products = $this->product->getProducts($projectID, 'all', '', false);
        $searchConfig['params']['module']['values'] = $this->product->getModulesForSearchForm(0, $products, 'all', (int)$projectID);

        $gradePairs = array();
        $gradeList  = $this->loadModel('story')->getGradeList('');
        $storyTypes = isset($project->storyType) ? $project->storyType : 'epic,story,requirement';
        foreach($gradeList as $grade)
        {
            if(strpos($storyTypes, $grade->type) === false) continue;
            $key = (string)$grade->type . (string)$grade->grade;
            $gradePairs[$key] = $grade->name;
        }
        asort($gradePairs);

        $searchConfig['params']['grade']['values'] = $gradePairs;

        if($this->config->edition == 'ipd') $searchConfig['params']['roadmap']['values'] = $this->loadModel('roadmap')->getPairs();

        $searchConfig['params']['plan']['values'] = $this->loadModel('productplan')->getPairs(array_keys($products));

        return $searchConfig;
    }
}
