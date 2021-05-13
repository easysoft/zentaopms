<?php
/**
 * The control file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: control.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class upgrade extends control
{
    /**
     * The index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        /* Locate to index page of my module, if upgrade.php does not exist. */
        $upgradeFile = $this->app->wwwRoot . 'upgrade.php';
        if(!file_exists($upgradeFile)) $this->locate($this->createLink('my', 'index'));

        $systemMode = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=mode');
        if(empty($systemMode) && !isset($this->config->qcVersion))
        {
            /* Judge upgrade step. */
            $upgradeStep = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=upgradeStep');
            if($upgradeStep == 'mergeProgram') $this->locate(inlink('mergeProgram'));
        }
        if(version_compare($this->config->installedVersion, '6.4', '<=')) $this->locate(inlink('license'));
        $this->locate(inlink('backup'));
    }

    /**
     * Check agree license.
     *
     * @access public
     * @return void
     */
    public function license()
    {
        if($this->get->agree == true) $this->locate(inlink('backup'));

        $clientLang = $this->app->getClientLang();
        $licenseCN  = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.CN');
        $licenseEN  = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.EN');

        $license = $licenseEN . $licenseCN;
        if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') $license = $licenseCN . $licenseEN;

        $this->view->title   = $this->lang->upgrade->common;
        $this->view->license = $license;
        $this->display();
    }

    /**
     * Backup.
     *
     * @access public
     * @return void
     */
    public function backup()
    {
        $this->session->set('upgrading', true);

        $this->view->title = $this->lang->upgrade->common;
        $this->display();
    }

    /**
     * Select the version of old zentao.
     *
     * @access public
     * @return void
     */
    public function selectVersion()
    {
        $version = str_replace(array(' ', '.'), array('', '_'), $this->config->installedVersion);
        $version = strtolower($version);
        $this->view->title      = $this->lang->upgrade->common . $this->lang->colon . $this->lang->upgrade->selectVersion;
        $this->view->position[] = $this->lang->upgrade->common;
        $this->view->version    = $version;
        $this->display();
    }

    /**
     * Confirm the version.
     *
     * @access public
     * @return void
     */
    public function confirm()
    {
        $this->session->set('step', '');
        $this->view->title       = $this->lang->upgrade->confirm;
        $this->view->position[]  = $this->lang->upgrade->common;
        $this->view->confirm     = $this->upgrade->getConfirm($this->post->fromVersion);
        $this->view->fromVersion = $this->post->fromVersion;

        /* When sql is empty then skip it. */
        if(empty($this->view->confirm)) $this->locate(inlink('execute', "fromVersion={$this->post->fromVersion}"));

        $this->display();
    }

    /**
     * Execute the upgrading.
     *
     * @access public
     * @return void
     */
    public function execute($fromVersion = '')
    {
        $this->session->set('step', '');

        $this->view->title      = $this->lang->upgrade->result;
        $this->view->position[] = $this->lang->upgrade->common;

        $result = $this->upgrade->deleteFiles();
        if($result)
        {
            $result[] = $this->lang->upgrade->afterDeleted;

            $this->view->result = 'fail';
            $this->view->errors  = $result;

            die($this->display());
        }

        $fromVersion = isset($_POST['fromVersion']) ? $this->post->fromVersion : $fromVersion;
        $this->upgrade->execute($fromVersion);

        if(!$this->upgrade->isError())
        {
            $systemMode = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=mode');
            if((empty($systemMode) && !isset($this->config->qcVersion) && strpos($fromVersion, 'max') === false) or
               ($systemMode != 'new' && strpos($fromVersion, 'max') === false && strpos($this->config->version, 'max') !== false))
            {
                $this->locate(inlink('to15Guide', "fromVersion=$fromVersion"));
            }
            $this->locate(inlink('afterExec', "fromVersion=$fromVersion"));
        }

        $this->view->result = 'fail';
        $this->view->errors = $this->upgrade->getError();
        $this->display();
    }

    /**
     * Guide to 15 version.
     *
     * @param  string    $fromVersion
     * @access public
     * @return void
     */
    public function to15Guide($fromVersion)
    {
        if($_POST)
        {
            $mode = fixer::input('post')->get('mode');
            $this->loadModel('setting')->setItem('system.common.global.mode', $mode);

            if($mode == 'classic') $this->locate(inlink('afterExec', "fromVersion=$fromVersion"));
            if($mode == 'new')     $this->locate(inlink('mergeTips'));
        }

        if(isset($this->config->maxVersion))
        {
            $this->lang->upgrade->to15Desc = str_replace('15', 'Max', $this->lang->upgrade->to15Desc);
            $title = $this->lang->upgrade->toMAXGuide;
        }
        elseif(isset($this->config->bizVersion))
        {
            $this->lang->upgrade->to15Desc = str_replace('15', 'Biz5', $this->lang->upgrade->to15Desc);
            $title = $this->lang->upgrade->toBIZ5Guide;
        }
        elseif(isset($this->config->proVersion))
        {
            $this->lang->upgrade->to15Desc = str_replace('15', 'Pro10', $this->lang->upgrade->to15Desc);
            $title = $this->lang->upgrade->toPRO10Guide;
        }
        else
        {
            $title = $this->lang->upgrade->toPMS15Guide;
        }

        $this->view->title = $title;
        $this->display();
    }

    /**
     * Merge program tips.
     *
     * @access public
     * @return void
     */
    public function mergeTips()
    {
        $this->loadModel('setting')->setItem('system.common.global.upgradeStep', 'mergeProgram');
        $this->view->title = $this->lang->upgrade->mergeTips;
        $this->display();
    }

    /**
     * Merge program.
     *
     * @param  string $type
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function mergeProgram($type = 'productline', $programID = 0)
    {
        $this->session->set('upgrading', true);
        $this->app->loadLang('program');
        $this->app->loadLang('project');
        $this->app->loadLang('product');
        $this->app->loadConfig('execution');

        if($_POST)
        {
            if($type == 'productline')
            {
                $linkedProducts = array();
                $linkedSprints  = array();
                $unlinkSprints  = array();

                /* Compute checked products and sprints, unchecked products and sprints. */
                foreach($_POST['products'] as $lineID => $products)
                {
                    foreach($products as $productID)
                    {
                        $linkedProducts[$productID] = $productID;

                        if(isset($_POST['sprints'][$lineID][$productID]))
                        {
                            foreach($_POST['sprints'][$lineID][$productID] as $sprintID)
                            {
                                $linkedSprints[$sprintID] = $sprintID;
                                unset($_POST['sprintIdList'][$lineID][$productID][$sprintID]);
                            }
                            $unlinkSprints[$productID] = $this->post->sprintIdList[$lineID][$productID];
                        }
                    }
                }

                /* Create Program. */
                list($programID, $projectID, $lineID) = $this->upgrade->createProgram($linkedProducts, $linkedSprints);
                if(dao::isError()) die(js::error(dao::getError()));

                /* Process merged products and projects. */
                $this->upgrade->processMergedData($programID, $projectID, $lineID, $linkedProducts, $linkedSprints);

                /* Process unlinked sprint and product. */
                foreach($linkedProducts as $productID => $product)
                {
                    if((isset($unlinkSprints[$productID]) and empty($unlinkSprints[$productID])) || !isset($unlinkSprints[$productID])) $this->dao->update(TABLE_PRODUCT)->set('line')->eq($lineID)->where('id')->eq($productID)->exec();
                }
            }
            elseif($type == 'product')
            {
                $linkedProducts = array();
                $linkedSprints  = array();
                $unlinkSprints  = array();
                foreach($_POST['products'] as $productID)
                {
                    $linkedProducts[$productID] = $productID;

                    if(isset($_POST['sprints'][$productID]))
                    {
                        foreach($_POST['sprints'][$productID] as $sprintID)
                        {
                            $linkedSprints[$sprintID] = $sprintID;
                            unset($_POST['sprintIdList'][$productID][$sprintID]);
                        }
                        $unlinkSprints += $this->post->sprintIdList[$productID];
                    }
                }

                /* Create Program. */
                list($programID, $projectID, $lineID) = $this->upgrade->createProgram($linkedProducts, $linkedSprints);
                if(dao::isError()) die(js::error(dao::getError()));

                /* Process productline. */
                $this->dao->delete()->from(TABLE_MODULE)->where('`root`')->eq(0)->andWhere('`type`')->eq('line')->exec();

                /* Process merged products and projects. */
                $this->upgrade->processMergedData($programID, $projectID, $lineID, $linkedProducts, $linkedSprints);
            }
            elseif($type == 'sprint')
            {
                $linkedSprints = $this->post->sprints;

                /* Create Program. */
                list($programID, $projectID, $lineID) = $this->upgrade->createProgram(array(), $linkedSprints);
                if(dao::isError()) die(js::error(dao::getError()));

                $this->upgrade->processMergedData($programID, $projectID, $lineID, array(), $linkedSprints);
            }
            elseif($type == 'moreLink')
            {
                foreach($this->post->projects as $i => $projectID)
                {
                    $sprintID = $this->post->sprints[$i];

                    /* Change program field for product and project. */
                    $this->upgrade->processMergedData(0, $projectID, 0, array(), array($sprintID));
                }
            }

            die(js::locate($this->createLink('upgrade', 'mergeProgram', "type=$type&programID=$programID"), 'parent'));
        }

        /* Get no merged product and project count. */
        $noMergedProductCount = $this->dao->select('count(*) as count')->from(TABLE_PRODUCT)->where('program')->eq(0)->fetch('count');
        $noMergedSprintCount  = $this->dao->select('count(*) as count')->from(TABLE_PROJECT)->where('grade')->eq(0)->andWhere('path')->eq('')->andWhere('type')->ne('program')->fetch('count');

        /* When all products and projects merged then finish and locate afterExec page. */
        if(empty($noMergedProductCount) and empty($noMergedSprintCount))
        {
            $this->upgrade->initUserView();
            $this->upgrade->setDefaultPriv();
            $this->dao->update(TABLE_CONFIG)->set('value')->eq('0_0')->where('`key`')->eq('productProject')->exec();

            /* Set defult hourPoint. */
            $hourPoint = $this->loadModel('setting')->getItem('owner=system&module=custom&key=hourPoint');
            if(empty($hourPoint)) $this->setting->setItem('system.custom.hourPoint', 0);

            /* Update sprints history. */
            $sprints = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('sprint')->fetchAll('id');
            $this->dao->update(TABLE_ACTION)->set('objectType')->eq('execution')->where('objectID')->in(array_keys($sprints))->andWhere('objectType')->eq('project')->exec();
            die(js::locate($this->createLink('upgrade', 'mergeRepo')));
        }

        $this->view->noMergedProductCount = $noMergedProductCount;
        $this->view->noMergedSprintCount  = $noMergedSprintCount;

        $this->loadModel('project');
        $this->view->type = $type;

        /* Get products and projects group by product line. */
        if($type == 'productline')
        {
            $productlines = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('root')->eq(0)->orderBy('id_desc')->fetchAll('id');

            $noMergedProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('line')->in(array_keys($productlines))->orderBy('id_desc')->fetchAll('id');
            if(empty($productlines) || empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=product'));

            $noMergedSprints = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
                ->where('t1.project')->eq(0)
                ->andWhere('t1.type')->eq('sprint')
                ->andWhere('t2.product')->in(array_keys($noMergedProducts))
                ->orderBy('t1.id_desc')
                ->fetchAll('id');

            /* Remove sprint than linked more than two products */
            $sprintProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');
            foreach($sprintProducts as $sprintID => $products)
            {
                if(count($products) > 1) unset($noMergedSprints[$sprintID]);
            }

            /* Group product by product line. */
            $lineGroups = array();
            foreach($noMergedProducts as $product) $lineGroups[$product->line][$product->id] = $product;

            /* Group sprint by product. */
            $productGroups = array();
            foreach($noMergedSprints as $sprint)
            {
                $sprintProduct = zget($sprintProducts, $sprint->id, array());
                if(empty($sprintProduct)) continue;

                $productID = key($sprintProduct);
                $productGroups[$productID][$sprint->id] = $sprint;
            }

            foreach($productlines as $line)
            {
                if(!isset($lineGroups[$line->id])) unset($productlines[$line->id]);
            }

            $this->view->productlines  = $productlines;
            $this->view->lineGroups    = $lineGroups;
            $this->view->productGroups = $productGroups;
        }
        /* Get projects group by product. */
        if($type == 'product')
        {
            $noMergedSprints = $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t2.model')->eq('')
                ->andWhere('t2.project')->eq(0)
                ->andWhere('t2.type')->eq('sprint')
                ->fetchAll('id');

            /* Remove project than linked more than two products */
            $sprintProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');
            $productGroup   = array();
            foreach($sprintProducts as $sprintID => $products)
            {
                if(count($products) > 1)
                {
                    unset($noMergedSprints[$sprintID]);
                    $productGroup[] = array_keys($products);
                }
            }

            /* Get products that are not merged by sprints. */
            $noMergedProducts = $this->dao->select('t1.*')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.product')
                ->where('t2.project')->in(array_keys($noMergedSprints))
                ->fetchAll('id');

            /* Add products without sprints. */
            $noMergedProducts += $this->dao->select('*')->from(TABLE_PRODUCT)->where('program')->eq(0)->fetchAll('id');

            if(empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=sprint'));


            /* Group project by product. */
            $productGroups = array();
            foreach($noMergedSprints as $sprint)
            {
                $sprintProduct = zget($sprintProducts, $sprint->id, array());
                if(empty($sprintProduct)) continue;

                $productID = key($sprintProduct);
                $productGroups[$productID][$sprint->id] = $sprint;
            }

            $this->view->noMergedProducts = $noMergedProducts;
            $this->view->productGroups    = $productGroups;
        }
        /* Get no merged projects than is not linked product. */
        if($type == 'sprint')
        {
            $noMergedSprints = $this->dao->select('*')->from(TABLE_PROJECT)->where('parent')->eq(0)->andWhere('path')->eq('')->orderBy('id_desc')->fetchAll('id');
            $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');
            foreach($projectProducts as $sprintID => $products) unset($noMergedSprints[$sprintID]);

            if(empty($noMergedSprints)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=moreLink'));

            $this->view->noMergedSprints = $noMergedSprints;
        }
        /* Get no merged projects that link more then two products. */
        if($type == 'moreLink')
        {
            $noMergedSprints = $this->dao->select('*')->from(TABLE_PROJECT)->where('parent')->eq(0)->andWhere('path')->eq('')->orderBy('id_desc')->fetchAll('id');
            $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');

            $productPairs = array();
            foreach($projectProducts as $sprintID => $products)
            {
                foreach($products as $productID => $data) $productPairs[$productID] = $productID;
            }

            $projects = $this->dao->select('t1.*,t2.product as productID')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
                ->where('t2.product')->in($productPairs)
                ->andWhere('t1.type')->eq('project')
                ->fetchAll('productID');

            foreach($noMergedSprints as $sprintID => $sprint)
            {
                $products = zget($projectProducts, $sprintID, array());
                foreach($products as $productID => $data)
                {
                    $project = zget($projects, $productID, '');
                    if($project) $sprint->projects[$project->id] = $project->name;
                }

                if(!isset($sprint->projects)) $sprint->projects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchPairs();
            }

            $this->view->noMergedSprints = $noMergedSprints;
        }

        $programs = $this->upgrade->getProgramPairs();
        $currentProgramID = $programID ? $programID : key($programs);

        $this->view->title     = $this->lang->upgrade->mergeProgram;
        $this->view->programs  = $programs;
        $this->view->programID = $programID;
        $this->view->projects  = array('' => '') + $this->upgrade->getProjectPairsByProgram($currentProgramID);
        $this->view->lines     = $currentProgramID ? array('' => '') + $this->loadModel('product')->getLinePairs($currentProgramID) : array('' => '');
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noempty');
        $this->view->groups    = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * Merge Repos.
     *
     * @access public
     * @return void
     */
    public function mergeRepo()
    {
        if($_POST)
        {
            $this->upgrade->mergeRepo();
            die(js::locate($this->createLink('upgrade', 'mergeRepo'), 'parent'));
        }

        $repos = $this->dao->select('id, name')->from(TABLE_REPO)->where('deleted')->eq(0)->andWhere('product')->eq('')->fetchPairs();

        if(empty($repos))
        {
            $this->loadModel('setting')->deleteItems('owner=system&module=common&section=global&key=upgradeStep');
            die(js::locate($this->createLink('upgrade', 'afterExec', "fromVersion=&processed=no")));
        }

        $this->view->repos    = $repos;
        $this->view->products = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchPairs();
        $this->view->programs = $this->dao->select('id, name')->from(TABLE_PROGRAM)->where('deleted')->eq(0)->andWhere('type')->eq('program')->fetchPairs();
        $this->display();
    }

    /**
     * Get the project of the program it belongs to.
     *
     * @param  int   $programID
     * @access public
     * @return void
     */
    public function ajaxGetProjectPairsByProgram($programID = 0)
    {
        $projects = array('' => '') + $this->upgrade->getProjectPairsByProgram($programID);
        die(html::select('projects', $projects, '', 'class="form-control prj-exist" onchange="getProgramStatus(\'project\', this.value)"'));
    }

    /**
     * Get the lines of the program it belongs to.
     *
     * @param  int   $programID
     * @access public
     * @return void
     */
    public function ajaxGetLinesPairsByProgram($programID = 0)
    {
        $lines = array('' => '');
        if((int)$programID) $lines += $this->loadModel('product')->getLinePairs($programID);
        die(html::select('lines', $lines, '', 'class="form-control line-exist"'));
    }

    /**
     * After execute.
     *
     * @param  string $fromVersion
     * @param  string $processed
     * @access public
     * @return void
     */
    public function afterExec($fromVersion, $processed = 'no')
    {
        $alterSQL = $this->upgrade->checkConsistency($this->config->version);
        if(!empty($alterSQL))
        {
            $this->view->title    = $this->lang->upgrade->consistency;
            $this->view->alterSQL = $alterSQL;
            die($this->display('upgrade', 'consistency'));
        }

        unset($_SESSION['user']);

        if($processed == 'no')
        {
            $this->app->loadLang('install');
            $this->view->title      = $this->lang->upgrade->result;
            $this->view->position[] = $this->lang->upgrade->common;

            $needProcess = $this->upgrade->checkProcess($fromVersion);
            $this->view->needProcess = $needProcess;
            $this->view->fromVersion = $fromVersion;
            $this->display();
        }
        if(empty($needProcess) or $processed == 'yes')
        {
            $this->loadModel('setting')->updateVersion($this->config->version);

            @unlink($this->app->getAppRoot() . 'www/install.php');
            @unlink($this->app->getAppRoot() . 'www/upgrade.php');
            unset($_SESSION['upgrading']);
        }
    }

    /**
     * Consistency.
     *
     * @access public
     * @return void
     */
    public function consistency($netConnect = true)
    {
        set_time_limit(0);
        $alterSQL = $this->upgrade->checkConsistency();
        if(empty($alterSQL))
        {
            if(!$netConnect) $this->locate(inlink('selectVersion'));
            $this->locate(inlink('checkExtension'));
        }

        $this->view->title    = $this->lang->upgrade->consistency;
        $this->view->alterSQL = $alterSQL;
        $this->display();
    }

    /**
     * Check extension.
     *
     * @access public
     * @return void
     */
    public function checkExtension()
    {
        $this->loadModel('extension');
        $extensions = $this->extension->getLocalExtensions('installed');
        if(empty($extensions)) $this->locate(inlink('selectVersion'));

        $versions = array();
        foreach($extensions as $code => $extension) $versions[$code] = $extension->version;

        $incompatibleExts = $this->extension->checkIncompatible($versions);
        $extensionsName   = array();
        if(empty($incompatibleExts)) $this->locate(inlink('selectVersion'));

        $removeCommands = array();
        foreach($incompatibleExts as $extension)
        {
            $this->extension->updateExtension($extension, array('status' => 'deactivated'));
            $removeCommands[$extension] = $this->extension->removePackage($extension);
            $extensionsName[$extension] = $extensions[$extension]->name;
        }

        $data = '';
        if($extensionsName)
        {
            $data .= "<h3>{$this->lang->upgrade->forbiddenExt}</h3>";
            $data .= '<ul>';
            foreach($extensionsName as $extension => $extensionName)
            {
                $data .= "<li>$extensionName";
                if($removeCommands[$extension]) $data .= '<p>'. $this->lang->extension->unremovedFiles . '</p> <p>' . join('<br />', $removeCommands[$extension]) . '</p>';
                $data .= '</li>';
            }
            $data .= '</ul>';
        }

        $this->view->title = $this->lang->upgrade->checkExtension;
        $this->view->data  = $data;
        $this->display();
    }

    /**
     * Ajax update file.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return void
     */
    public function ajaxUpdateFile($type = '', $lastID = 0)
    {
        set_time_limit(0);
        $result = $this->upgrade->updateFileObjectID($type, $lastID);
        $response = array();
        if($result['type'] == 'finish')
        {
            $response['result']  = 'finished';
            $response['type']    = $type;
            $response['count']   = $result['count'];
            $response['message'] = 'Finished';
        }
        else
        {
            $response['result']   = 'continue';
            $response['next']     = inlink('ajaxUpdateFile', "type={$result['type']}&lastID={$result['lastID']}");
            $response['count']    = $result['count'];
            $response['type']     = $type;
            $response['nextType'] = $result['type'];
            $response['message']  = strtoupper($result['type']) . " <span class='{$result['type']}-num'>0</span>";
        }
        die(json_encode($response));
    }

    /**
     * Ajax get product name.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetProductName($productID)
    {
        die($this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('name'));
    }

    /**
     * Ajax get program status.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProgramStatus($programID)
    {
        die($this->dao->select('status')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch('status'));
    }
}
