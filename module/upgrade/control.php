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

        if(version_compare($this->config->installedVersion, '20', '<')) $this->locate(inlink('to20'));
        if(version_compare($this->config->installedVersion, '6.4', '<=')) $this->locate(inlink('license'));
        $this->locate(inlink('backup'));
    }

    /**
     * Upgrade to 20 version.
     * 
     * @access public
     * @return void
     */
    public function to20()
    {
        $this->view->title = $this->lang->upgrade->to20Tips;
        $this->display();
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
            if(version_compare(str_replace('_', '.', $fromVersion), '20', '<') && !isset($this->config->qcVersion)) $this->locate(inlink('mergeProgram'));
            $this->locate(inlink('afterExec', "fromVersion=$fromVersion"));
        }

        $this->view->result = 'fail';
        $this->view->errors = $this->upgrade->getError();
        $this->display();
    }

    /**
     * Merge program.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function mergeProgram($type = 'productline')
    {
        if($_POST)
        {
            if($type == 'productline')
            {
                $linkedProducts = array();
                $linkedProjects = array();
                $unlinkProducts = array();
                $unlinkProjects = array();
                foreach($this->post->lines as $lineID)
                {
                    if(isset($_POST['products'][$lineID]))
                    {
                        foreach($_POST['products'][$lineID] as $productID)
                        {
                            $linkedProducts[$productID] = $productID;
                            unset($_POST['productIdList'][$lineID][$productID]);

                            if(isset($_POST['projects'][$lineID][$productID]))
                            {
                                foreach($_POST['projects'][$lineID][$productID] as $projectID)
                                {
                                    $linkedProjects[$projectID] = $projectID;
                                    unset($_POST['projectIdList'][$lineID][$productID][$projectID]);
                                }
                                $unlinkProjects += $this->post->projectIdList[$lineID][$productID];
                            }
                        }
                        $unlinkProducts += $this->post->productIdList[$lineID];
                    }
                }

                if(isset($_POST['newProgram']) or !isset($_POST['programs']))
                {
                    if(empty($_POST['PRJadmins'])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->upgrade->PRJadmin)));

                    /* Create Program. */
                    $programID = $this->upgrade->createProgram($linkedProducts, $linkedProjects, $this->post->PRJadmins);
                    if(dao::isError()) die(js::error(dao::getError()));
                }
                else
                {
                    $programID = $this->post->programs;
                    $this->dao->update(TABLE_PROJECT)->set('product')->eq('multiple')->where('id')->eq($programID)->andWhere('product')->eq('single')->exec();
                }

                /* Change program field for product and project. */
                $this->upgrade->setProgram4Product($programID, $linkedProducts);
                if($linkedProjects) $this->upgrade->setProgram4Project($programID, $linkedProjects);

                /* Set program team. */
                $this->upgrade->setProgramTeam($programID, $linkedProducts, $linkedProjects);

                /* Process unlinked product. */
                if($unlinkProducts) $this->dao->update(TABLE_PRODUCT)->set('line')->eq(0)->where('id')->in($unlinkProducts)->exec();
                if($unlinkProjects) $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->in($unlinkProjects)->exec();
            }
            elseif($type == 'product')
            {
                $linkedProducts = array();
                $linkedProjects = array();
                $unlinkProjects = array();
                foreach($_POST['products'] as $productID)
                {
                    $linkedProducts[$productID] = $productID;

                    if(isset($_POST['projects'][$productID]))
                    {
                        foreach($_POST['projects'][$productID] as $projectID)
                        {
                            $linkedProjects[$projectID] = $projectID;
                            unset($_POST['projectIdList'][$productID][$projectID]);
                        }
                        $unlinkProjects += $this->post->projectIdList[$productID];
                    }
                }

                if(isset($_POST['newProgram']) or !isset($_POST['programs']))
                {
                    if(empty($_POST['PRJadmins'])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->upgrade->PRJadmin)));

                    /* Create Program. */
                    $programID = $this->upgrade->createProgram($linkedProducts, $linkedProjects, $this->post->PRJadmins);
                    if(dao::isError()) die(js::error(dao::getError()));
                }
                else
                {
                    $programID = $this->post->programs;
                    $this->dao->update(TABLE_PROJECT)->set('product')->eq('multiple')->where('id')->eq($programID)->andWhere('product')->eq('single')->exec();
                }

                /* Change program field for product and project. */
                $this->upgrade->setProgram4Product($programID, $linkedProducts);
                if($linkedProjects) $this->upgrade->setProgram4Project($programID, $linkedProjects);

                /* Set program team. */
                $this->upgrade->setProgramTeam($programID, $linkedProducts, $linkedProjects);

                /* Process unlinked product. */
                if($unlinkProjects) $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->in($unlinkProjects)->exec();
            }
            elseif($type == 'project')
            {
                $linkedProjects = $this->post->projects;
                if(isset($_POST['newProgram']) or !isset($_POST['programs']))
                {
                    if(empty($_POST['PRJadmins'])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->upgrade->PRJadmin)));

                    /* Create Program. */
                    $programID = $this->upgrade->createProgram(array(), $linkedProjects, $this->post->PRJadmins);
                    if(dao::isError()) die(js::error(dao::getError()));
                }
                else
                {
                    $programID = $this->post->programs;
                    $this->dao->update(TABLE_PROJECT)->set('product')->eq('multiple')->where('id')->eq($programID)->andWhere('product')->eq('single')->exec();
                }

                $productID = $this->upgrade->createProduct4Program($programID);

                /* Change program field for product and project. */
                $this->upgrade->setProgram4Project($programID, $linkedProjects);

                /* Set program team. */
                $this->upgrade->setProgramTeam($programID, array(), $linkedProjects);

                /* Link product. */
                foreach($linkedProjects as $projectID) $this->dao->replace(TABLE_PROJECTPRODUCT)->set('project')->eq($projectID)->set('product')->eq($productID)->exec();
            }
            elseif($type == 'moreLink')
            {
                foreach($this->post->programs as $i => $programID)
                {
                    $projectID = $this->post->projects[$i];

                    /* Change program field for product and project. */
                    $this->upgrade->setProgram4Project($programID, array($projectID));

                    /* Set program team. */
                    $this->upgrade->setProgramTeam($programID, array(), array($projectID));
                }
            }

            die(js::locate($this->createLink('upgrade', 'mergeProgram', "type=$type"), 'parent'));
        }

        /* Get no merged product and project count. */
        $noMergedProductCount = $this->dao->select('count(*) as count')->from(TABLE_PRODUCT)->where('program')->eq(0)->andWhere('deleted')->eq(0)->fetch('count');
        $noMergedProjectCount = $this->dao->select('count(*) as count')->from(TABLE_PROJECT)->where('model')->eq('')->andWhere('deleted')->eq(0)->fetch('count');

        /* When all products and projects merged then finish and locate afterExec page. */
        if(empty($noMergedProductCount) and empty($noMergedProjectCount)) 
        {
            $this->upgrade->initUserView();
            $this->upgrade->setDefaultPriv();
            die(js::locate($this->createLink('upgrade', 'afterExec', "fromVersion=&processed=yes")));
        }

        $this->view->noMergedProductCount = $noMergedProductCount;
        $this->view->noMergedProjectCount = $noMergedProjectCount;

        $this->app->loadLang('program');
        $this->loadModel('project');
        $this->view->type = $type;

        /* Get products and projects group by product line. */
        if($type == 'productline')
        {
            $productlines = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->fetchAll('id');

            $noMergedProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('program')->eq(0)->andWhere('line')->in(array_keys($productlines))->andWhere('deleted')->eq(0)->fetchAll('id');
            if(empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=product'));

            $noMergedProjects = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
                ->where('t1.model')->eq('')
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.product')->in(array_keys($noMergedProducts))
                ->fetchAll('id');

            /* Remove project than linked more than two products */
            $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');
            foreach($projectProducts as $projectID => $products)
            {
                if(count($products) > 1) unset($noMergedProjects[$projectID]);
            }

            /* Group product by product line. */
            $lineGroups = array();
            foreach($noMergedProducts as $product) $lineGroups[$product->line][$product->id] = $product;

            /* Group project by product. */
            $productGroups = array();
            foreach($noMergedProjects as $project)
            {
                $projectProduct = zget($projectProducts, $project->id, array());
                if(empty($projectProduct)) continue;

                $productID = key($projectProduct);
                $productGroups[$productID][$project->id] = $project;
            }

            $this->view->productlines  = $productlines;
            $this->view->lineGroups    = $lineGroups;
            $this->view->productGroups = $productGroups;
        }
        /* Get projects group by product. */
        if($type == 'product')
        {
            $noMergedProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('program')->eq(0)->andWhere('deleted')->eq(0)->fetchAll('id');
            if(empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=project'));

            $noMergedProjects = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
                ->where('t1.model')->eq('')
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.product')->in(array_keys($noMergedProducts))
                ->fetchAll('id');

            /* Remove project than linked more than two products */
            $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');
            foreach($projectProducts as $projectID => $products)
            {
                if(count($products) > 1) unset($noMergedProjects[$projectID]);
            }

            /* Group project by product. */
            $productGroups = array();
            foreach($noMergedProjects as $project)
            {
                $projectProduct = zget($projectProducts, $project->id, array());
                if(empty($projectProduct)) continue;

                $productID = key($projectProduct);
                $productGroups[$productID][$project->id] = $project;
            }
            $this->view->noMergedProducts = $noMergedProducts;
            $this->view->productGroups    = $productGroups;
        }
        /* Get no merged projects than is not linked product. */
        if($type == 'project')
        {
            $noMergedProjects = $this->dao->select('*')->from(TABLE_PROJECT)->where('model')->eq('')->andWhere('deleted')->eq(0)->fetchAll('id');
            $projectProducts  = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');
            foreach($projectProducts as $projectID => $products) unset($noMergedProjects[$projectID]);

            if(empty($noMergedProjects)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=moreLink'));

            $this->view->noMergedProjects = $noMergedProjects;
        }
        /* Get no merged projects that link more then two products. */
        if($type == 'moreLink')
        {
            $noMergedProjects = $this->dao->select('*')->from(TABLE_PROJECT)->where('model')->eq('')->andWhere('deleted')->eq(0)->fetchAll('id');
            $projectProducts  = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');

            $productPairs = array();
            foreach($projectProducts as $projectID => $products)
            {
                foreach($products as $productID => $data) $productPairs[$productID] = $productID;
            }

            $programs = $this->dao->select('t1.*,t2.id as productID')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.id=t2.program')
                ->where('t2.id')->in($productPairs)
                ->fetchAll('productID');

            foreach($noMergedProjects as $projectID => $project)
            {
                $products = zget($projectProducts, $projectID, array());
                foreach($products as $productID => $data)
                {
                    $program = zget($programs, $productID, '');
                    if($program) $project->programs[$program->id] = $program->name;
                }
            }

            $this->view->noMergedProjects = $noMergedProjects;
        }

        $this->view->title = $this->lang->upgrade->mergeProgram;

        $this->view->programs = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq(0)->andWhere('model')->eq('scrum')->fetchPairs('id', 'name');
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|noempty');
        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->display();
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
            $response['type']     = $type;
            $response['count']    = $result['count'];
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
}
