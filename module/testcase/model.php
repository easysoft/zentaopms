<?php
/**
 * The model file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class testcaseModel extends model
{
    /**
     * Set menu.
     * 
     * @param  array $products 
     * @param  int   $productID 
     * @access public
     * @return void
     */
    public function setMenu($products, $productID)
    {
        $this->loadModel('product')->setMenu($products, $productID);
        $selectHtml = $this->product->select($products, $productID, 'testcase', 'browse');
        foreach($this->lang->testcase->menu as $key => $menu)
        {
            $replace = ($key == 'product') ? $selectHtml . $this->lang->arrow : $productID;
            common::setMenuVars($this->lang->testcase->menu, $key, $replace);
        }
    }

    /**
     * Create a case.
     * 
     * @param int $bugID
     * @access public
     * @return void
     */
    function create($bugID)
    {
        $now  = helper::now();
        $case = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('status', 'normal')
            ->add('version', 1)
            ->add('fromBug', $bugID)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->remove('steps,expects,files,labels')
            ->setDefault('story', 0)
            ->specialChars('title')
            ->join('stage', ',')
            ->get();
        $this->dao->insert(TABLE_CASE)->data($case)->autoCheck()->batchCheck($this->config->testcase->create->requiredFields, 'notempty')->exec();
        if(!$this->dao->isError())
        {
            $caseID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('testcase', $caseID);
            foreach($this->post->steps as $stepID => $stepDesc)
            {
                if(empty($stepDesc)) continue;
                $step->case    = $caseID;
                $step->version = 1;
                $step->desc    = htmlspecialchars($stepDesc);
                $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }
            return $caseID;
        }
    }
    
    /**
     * Create a batch case.
     * 
     * @access public
     * @return void
     */
    function batchCreate($productID)
    {
        $now   = helper::now();
        $cases = fixer::input('post')->get();
        for($i = 0; $i < $this->config->testcase->batchCreate; $i++)
        {
            if($cases->type[$i] != '' and $cases->title[$i] != '')
            {
                $data[$i]->product    = $productID;
                $data[$i]->module     = $cases->module[$i] == 'same' ? ($i == 0 ? 0 : $data[$i-1]->module) : $cases->module[$i];
                $data[$i]->type       = $cases->type[$i] == 'same' ? ($i == 0 ? '' : $data[$i-1]->type) : $cases->type[$i]; 
                $data[$i]->story      = $cases->story[$i] == 'same' ? ($i == 0 ? 0 : $data[$i-1]->story) : $cases->story[$i]; 
                $data[$i]->title      = $cases->title[$i];
                $data[$i]->openedBy   = $this->app->user->account;
                $data[$i]->openedDate = $now;
                $data[$i]->status     = 'normal';
                $data[$i]->version    = 1;
                if($data[$i]->story != 0) $data[$i]->storyVersion = $this->loadModel('story')->getVersion($this->post->story);

                $this->dao->insert(TABLE_CASE)->data($data[$i])
                    ->autoCheck()
                    ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
                    ->exec();

                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }

                $caseID = $this->dao->lastInsertID();
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Opened');
            }
            else
            {
                unset($cases->module[$i]);
                unset($cases->type[$i]);
                unset($cases->story[$i]);
                unset($cases->title[$i]);
            }
        }
    }

    /**
     * Get cases of a module.
     * 
     * @param  int    $productID 
     * @param  int    $moduleIds 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getModuleCases($productID, $moduleIds = 0, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_CASE)
            ->where('product')->eq((int)$productID)
            ->beginIF($moduleIds)->andWhere('module')->in($moduleIds)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)->fetchAll();
    }

    /**
     * Get case info by ID.
     * 
     * @param  int    $caseID 
     * @param  int    $version 
     * @access public
     * @return object|bool
     */
    public function getById($caseID, $version = 0)
    {
        $case = $this->dao->findById($caseID)->from(TABLE_CASE)->fetch();
        if(!$case) return false;
        foreach($case as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $case->$key = '';
        if($case->story)
        {
            $story = $this->dao->findById($case->story)->from(TABLE_STORY)->fields('title, status, version')->fetch();
            $case->storyTitle         = $story->title;
            $case->storyStatus        = $story->status;
            $case->latestStoryVersion = $story->version;
        }
        if($case->linkCase) $case->linkCaseTitles = $this->dao->select('id,title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->fetchPairs();
        if($version == 0) $version = $case->version;
        $case->steps = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->andWhere('version')->eq($version)->fetchAll();
        $case->files = $this->loadModel('file')->getByObject('testcase', $caseID);
        $case->currentVersion = $version ? $version : $case->version;
        return $case;
    }

    /**
     * Update a case.
     * 
     * @param  int    $caseID 
     * @access public
     * @return void
     */
    public function update($caseID)
    {
        $oldCase     = $this->getById($caseID);
        $now         = helper::now();
        $stepChanged = false;
        $steps       = array();

        //---------------- Judge steps changed or not.-------------------- */
        
        /* Remove the empty setps in post. */
        foreach($this->post->steps as $key => $desc)
        {
            $desc = trim($desc);
            if(!empty($desc)) $steps[] = array('desc' => $desc, 'expect' => trim($this->post->expects[$key]));
        }

        /* If step count changed, case changed. */
        if(count($oldCase->steps) != count($steps))
        {
            $stepChanged = true;
        }
        else
        {
            /* Compare every step. */
            foreach($oldCase->steps as $key => $oldStep)
            {
                if(trim($oldStep->desc) != trim($steps[$key]['desc']) or trim($oldStep->expect) != $steps[$key]['expect']) 
                {
                    $stepChanged = true;
                    break;
                }
            }
        }
        $version = $stepChanged ? $oldCase->version + 1 : $oldCase->version;

        $case = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->add('version', $version)
            ->setIF($this->post->story != false and $this->post->story != $oldCase->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setDefault('story', 0)
            ->specialChars('title')
            ->join('stage', ',')
            ->remove('comment,steps,expects,files,labels')
            ->get();
        $this->dao->update(TABLE_CASE)->data($case)->autoCheck()->batchCheck($this->config->testcase->edit->requiredFields, 'notempty')->where('id')->eq((int)$caseID)->exec();
        if(!$this->dao->isError())
        {
            if($stepChanged)
            {
                foreach($this->post->steps as $stepID => $stepDesc)
                {
                    if(empty($stepDesc)) continue;
                    $step->case    = $caseID;
                    $step->version = $version;
                    $step->desc    = htmlspecialchars($stepDesc);
                    $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                    $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
                }
            }

            /* Join the steps to diff. */
            if($stepChanged)
            {
                $oldCase->steps = $this->joinStep($oldCase->steps);
                $case->steps    = $this->joinStep($this->getById($caseID, $version)->steps);
            }
            else
            {
                unset($oldCase->steps);
            }
            return common::createChanges($oldCase, $case);
        }
    }

    /**
     * Batch update case.
     * 
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $cases      = array();
        $allChanges = array();
        $now        = helper::now();
        $caseIDList = $this->post->caseIDList ? $this->post->caseIDList : array();
        
        /* Adjust whether the post data is complete, if not, remove the last element of $caseIDList. */
        if($this->session->showSuhosinInfo) array_pop($caseIDList);

        if(!empty($caseIDList))
        {
            /* Initialize cases from the post data.*/
            foreach($caseIDList as $caseID)
            {
                $oldCase = $this->getByID($caseID);

                $case->lastEditedBy   = $this->app->user->account;
                $caee->lastEditedDate = $now;
                $case->pri            = $this->post->pris[$caseID];
                $case->status         = $this->post->statuses[$caseID];
                $case->module         = $this->post->modules[$caseID];
                $case->title          = htmlspecialchars($this->post->titles[$caseID]);
                $case->type           = $this->post->types[$caseID];
                $case->stage          = implode(',', $this->post->stages[$caseID]);

                $cases[$caseID] = $case;
                unset($case);
            }

            /* Update cases. */
            foreach($cases as $caseID => $case)
            {
                $oldCase = $this->getByID($caseID);
                $this->dao->update(TABLE_CASE)->data($case)
                    ->autoCheck()
                    ->batchCheck($this->config->testcase->edit->requiredFields, 'notempty')
                    ->where('id')->eq($caseID)
                    ->exec();

                if(!dao::isError())
                {
                    unset($oldCase->steps);
                    $allChanges[$caseID] = common::createChanges($oldCase, $case);
                }
                else
                {
                    die(js::error('case#' . $caseID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
    }

    /**
     * Join steps to a string, thus can diff them.
     * 
     * @param  array   $steps 
     * @access public
     * @return string
     */
    public function joinStep($steps)
    {
        $retrun = '';
        foreach($steps as $step) $return .= $step->desc . ' EXPECT:' . $step->expect . "\n";
        return $return;
    }

    /**
     * Create case steps from a bug's step.
     * 
     * @param  string    $steps 
     * @access public
     * @return array
     */
    function createStepsFromBug($steps)
    {
        $steps        = strip_tags($steps);
        $caseSteps    = array((object)array('desc' => $steps, 'expect' => ''));   // the default steps before parse.
        $lblStep      = strip_tags($this->lang->bug->tplStep);
        $lblResult    = strip_tags($this->lang->bug->tplResult);
        $lblExpect    = strip_tags($this->lang->bug->tplExpect);
        $lblStepPos   = strpos($steps, $lblStep);
        $lblResultPos = strpos($steps, $lblResult);
        $lblExpectPos = strpos($steps, $lblExpect);

        if($lblStepPos === false or $lblResultPos === false or $lblExpectPos === false) return $caseSteps;

        $caseSteps  = substr($steps, $lblStepPos + strlen($lblStep), $lblResultPos - strlen($lblStep));
        $caseExpect = substr($steps, $lblExpectPos + strlen($lblExpect)); 
        $caseSteps  = trim($caseSteps);
        $caseExpect = trim($caseExpect);

        $caseSteps = explode("\n", trim($caseSteps));
        $stepCount = count($caseSteps);
        foreach($caseSteps as $key => $caseStep)
        {
            $expect = $key + 1 == $stepCount ? $caseExpect : '';
            $caseSteps[$key] = (object)array('desc' => trim($caseStep), 'expect' => $expect);
        }
        return $caseSteps;
    }
}
