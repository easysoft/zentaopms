<?php
/**
 * The model file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: model.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
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
            $replace = ($key == 'product') ? $selectHtml : $productID;
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
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion((int)$this->post->story))
            ->remove('steps,expects,files,labels')
            ->setDefault('story', 0)
            ->join('stage', ',')
            ->get();

        $result = $this->loadModel('common')->removeDuplicate('case', $case, "product={$case->product}");
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        /* value of story may be showmore. */
        $case->story = (int)$case->story;
        $this->dao->insert(TABLE_CASE)->data($case)->autoCheck()->batchCheck($this->config->testcase->create->requiredFields, 'notempty')->exec();
        if(!$this->dao->isError())
        {
            $caseID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('testcase', $caseID);
            foreach($this->post->steps as $stepID => $stepDesc)
            {
                if(empty($stepDesc)) continue;
                $step          = new stdClass();
                $step->case    = $caseID;
                $step->version = 1;
                $step->desc    = htmlspecialchars($stepDesc);
                $step->expect  = htmlspecialchars($this->post->expects[$stepID]);
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }
            return array('status' => 'created', 'id' => $caseID);
        }
    }
    
    /**
     * Batch create cases.
     * 
     * @param  int    $productID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    function batchCreate($productID, $storyID)
    {
        $now         = helper::now();
        $cases       = fixer::input('post')->get();
        $batchNum    = count(reset($cases));

        $result = $this->loadModel('common')->removeDuplicate('case', $cases, "product=$productID");
        $cases  = $result['data'];

        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($cases->title[$i]) and empty($cases->type[$i])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->testcase->type)));
        }

        $module = 0;
        $story  = 0;
        $type   = '';
        for($i = 0; $i < $batchNum; $i++)
        {
            $module = $cases->module[$i] == 'same' ? $module : $cases->module[$i];
            $story  = $cases->story[$i] == 'same'  ? $story  : $cases->story[$i];
            $type   = $cases->type[$i] == 'same'   ? $type   : $cases->type[$i];
            $cases->module[$i] = (int)$module;
            $cases->story[$i]  = (int)$story;        
            $cases->type[$i]   = $type;
        }

        for($i = 0; $i < $batchNum; $i++)
        {
            if($cases->type[$i] != '' and $cases->title[$i] != '')
            {
                $data[$i] = new stdclass();
                $data[$i]->product    = $productID;
                $data[$i]->module     = $cases->module[$i];
                $data[$i]->type       = $cases->type[$i];
                $data[$i]->story      = $storyID ? $storyID : $cases->story[$i]; 
                $data[$i]->title      = $cases->title[$i];
                $data[$i]->openedBy   = $this->app->user->account;
                $data[$i]->openedDate = $now;
                $data[$i]->status     = 'normal';
                $data[$i]->version    = 1;
                if(!$data[$i]->story) 
                {
                    $data[$i]->story = 0;
                }
                else
                {
                    $data[$i]->storyVersion = $this->loadModel('story')->getVersion($this->post->story);
                }

                $this->dao->insert(TABLE_CASE)->data($data[$i])
                    ->autoCheck()
                    ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
                    ->exec();

                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }

                $caseID   = $this->dao->lastInsertID();
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
        return $this->dao->select('t1.*, t2.title as storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->beginIF($moduleIds)->andWhere('t1.module')->in($moduleIds)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)->page($pager)->fetchAll('id');
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
        if($case->fromBug) $case->fromBugTitle = $this->dao->findById($case->fromBug)->from(TABLE_BUG)->fields('title')->fetch('title'); 

        $case->toBugs = array();
        $toBugs       = $this->dao->select('id, title')->from(TABLE_BUG)->where('`case`')->eq($caseID)->fetchAll();
        foreach($toBugs as $toBug) $case->toBugs[$toBug->id] = $toBug->title;

        if($case->linkCase) $case->linkCaseTitles = $this->dao->select('id,title')->from(TABLE_CASE)->where('id')->in($case->linkCase)->fetchPairs();
        if($version == 0) $version = $case->version;
        $case->steps = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->andWhere('version')->eq($version)->orderBy('id')->fetchAll();
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
                    $step = new stdclass();
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
     * Batch update testcases.
     * 
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $cases      = array();
        $allChanges = array();
        $now        = helper::now();
        $data       = fixer::input('post')->get();
        $caseIDList = $this->post->caseIDList;

        /* Adjust whether the post data is complete, if not, remove the last element of $caseIDList. */
        if($this->session->showSuhosinInfo) array_pop($caseIDList);

        /* Initialize cases from the post data.*/
        foreach($caseIDList as $caseID)
        {
            $case = new stdclass();
            $case->lastEditedBy   = $this->app->user->account;
            $case->lastEditedDate = $now;
            $case->pri            = $data->pris[$caseID];
            $case->status         = $data->statuses[$caseID];
            $case->module         = $data->modules[$caseID];
            $case->title          = $data->titles[$caseID];
            $case->type           = $data->types[$caseID];
            $case->stage          = empty($data->stages[$caseID]) ? '' : implode(',', $data->stages[$caseID]);

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
        $return = '';
        if(empty($steps)) return $return;
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

    /**
     * Adjust the action is clickable.
     * 
     * @param  object $case 
     * @param  string $action 
     * @access public
     * @return void
     */
    public static function isClickable($case, $action)
    {
        $action = strtolower($action);

        if($action == 'createbug') return $case->lastRunResult == 'fail';

        return true;
    }

    /**
     * Create from import 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function createFromImport($productID)
    {
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('file');
        $now  = helper::now();
        $data = fixer::input('post')->get();

        if(!empty($_POST['id']))
        {
            $oldSteps = $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
                ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
                ->where('t1.id')->in(($_POST['id']))
                ->andWhere('t1.product')->eq($productID)
                ->andWhere('t1.version=t2.version')
                ->orderBy('t2.id')
                ->fetchGroup('case');
            $oldCases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($_POST['id'])->fetchAll('id');
        }

        $cases = array();
        foreach($data->product as $key => $product)
        {
            $caseData = new stdclass();

            $caseData->product      = $product;
            $caseData->module       = $data->module[$key];
            $caseData->story        = (int)$data->story[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->status       = $data->status[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
            $caseData->frequency    = 1;
            $caseData->precondition = $data->precondition[$key];

            if(isset($this->config->testcase->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->testcase->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(empty($caseData->$requiredField)) die(js::alert(sprintf($this->lang->testcase->noRequire, $key, $this->lang->testcase->$requiredField)));
                }
            }

            $cases[$key] =$caseData;
        }

        foreach($cases as $key => $caseData)
        {
            if(!empty($_POST['id'][$key]))
            {
                $caseID      = $data->id[$key];
                $stepChanged = false;
                $steps       = array();
                $oldStep     = isset($oldSteps[$caseID]) ? $oldSteps[$caseID] : array();
                $oldCase     = $oldCases[$caseID];

                /* Remove the empty setps in post. */
                $steps = array();
                if(isset($_POST['desc'][$key]))
                {
                    foreach($this->post->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc))continue;
                        $step = new stdclass();
                        $step->desc   = $desc;
                        $step->expect = trim($this->post->expect[$key][$id]);

                        $steps[] = $step;
                    }
                }

                /* If step count changed, case changed. */
                if((!$oldStep != !$steps) or (count($oldStep) != count($steps)))
                {
                    $stepChanged = true;
                }
                else
                {
                    /* Compare every step. */
                    foreach($oldStep as $id => $oldStep)
                    {
                        if(trim($oldStep->desc) != trim($steps[$id]->desc) or trim($oldStep->expect) != $steps[$id]->expect)
                        {
                            $stepChanged = true;
                            break;
                        }
                    }
                }

                $version           = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
                $caseData->version = $version;
                $changes           = common::createChanges($oldCase, $caseData); 
                if(!$changes and !$stepChanged) continue;

                if($changes or $stepChanged)
                {
                    $caseData->lastEditedBy   = $this->app->user->account;
                    $caseData->lastEditedDate = $now;
                    $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->exec();
                    if($stepChanged)
                    {
                        foreach($steps as $id => $step)
                        {
                            $step = (array)$step;
                            if(empty($step['desc'])) continue;
                            $stepData = new stdclass();
                            $stepData->case    = $caseID;
                            $stepData->version = $version;
                            $stepData->desc    = htmlspecialchars($step['desc']);
                            $stepData->expect  = htmlspecialchars($step['expect']);
                            $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                        }
                    }
                    $oldCase->steps  = $this->joinStep($oldStep);
                    $caseData->steps = $this->joinStep($steps);
                    $changes = common::createChanges($oldCase, $caseData);
                    $actionID = $this->action->create('case', $caseID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            else
            {
                $caseData->version    = 1;
                $caseData->openedBy   = $this->app->user->account;
                $caseData->openedDate = $now;
                $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->exec();

                if(!dao::isError())
                {
                    $caseID = $this->dao->lastInsertID();
                    foreach($this->post->desc[$key] as $id => $desc)
                    {
                        $desc = trim($desc);
                        if(empty($desc)) continue;
                        $stepData = new stdclass();
                        $stepData->case    = $caseID;
                        $stepData->version = 1;
                        $stepData->desc    = htmlspecialchars($desc);
                        $stepData->expect  = htmlspecialchars($this->post->expect[$key][$id]);
                        $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                    }
                    $this->action->create('case', $caseID, 'Opened');
                }
            }
        }

        unlink($this->session->importFile);
        unset($_SESSION['importFile']);        
    }
}
