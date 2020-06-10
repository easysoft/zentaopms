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

        if(version_compare($this->config->installedVersion, '15', '<')) $this->locate(inlink('to15'));
        if(version_compare($this->config->installedVersion, '6.4', '<=')) $this->locate(inlink('license'));
        $this->locate(inlink('backup'));
    }

    /**
     * Upgrade to 15 version.
     * 
     * @access public
     * @return void
     */
    public function to15()
    {
        $this->view->title = $this->lang->upgrade->to15Tips;
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
            if(version_compare(str_replace('_', '.', $fromVersion), '15', '<')) $this->locate(inlink('mergeProgram'));
            $this->locate(inlink('afterExec', "fromVersion=$fromVersion"));
        }

        $this->view->result = 'fail';
        $this->view->errors = $this->upgrade->getError();
        $this->display();
    }

    public function mergeProgram($type = 'productline')
    {
        if($_POST)
        {
        }

        $this->view->type = $type;
        if($type == 'productline')
        {
            $productlines = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->fetchAll('id');
            $noMergedProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('program')->eq(0)->andWhere('line')->in(array_keys($productlines))->andWhere('deleted')->eq(0)->fetchAll('id');
            if(empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=product'));

            $noMergedProjects = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
                ->where('t1.program')->eq(0)
                ->andWhere('t1.template')->eq('')
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.product')->in(array_keys($noMergedProducts))
                ->fetchAll('id');

            $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');
            foreach($projectProducts as $projectID => $products)
            {
                if(count($products) > 1) unset($noMergedProjects[$projectID]);
            }

            $lineGroups = array();
            foreach($noMergedProducts as $product) $lineGroups[$product->line][$product->id] = $product;

            $productGroups = array();
            foreach($noMergedProjects as $project)
            {
                $projectProduct = zget($projectProducts, $project->id, array());
                if(empty($projectProduct)) continue;

                $productID = key($projectProduct);
                $productGroups[$productID][$project->id] = $project;
            }

            $productlines;
            $this->view->productlines  = $productlines;
            $this->view->lineGroups    = $lineGroups;
            $this->view->productGroups = $productGroups;
        }
        if($type == 'product')
        {
            $noMergedProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('program')->eq(0)->andWhere('deleted')->eq(0)->fetchAll('id');
            if(empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', 'type=project'));

            $noMergedProjects = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
                ->where('t1.program')->eq(0)
                ->andWhere('t1.template')->eq('')
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.product')->in(array_keys($noMergedProducts))
                ->fetchAll('id');

            $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');
            foreach($projectProducts as $projectID => $products)
            {
                if(count($products) > 1) unset($noMergedProjects[$projectID]);
            }

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
        if($type == 'project')
        {
            $noMergedProjects = $this->dao->select('*')->from(TABLE_PROJECT)->where('program')->eq(0)->andWhere('template')->eq('')->andWhere('deleted')->eq(0)->fetchAll('id');
            $projectProducts  = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');
            foreach($projectProducts as $projectID => $products) unset($noMergedProjects[$projectID]);

            $this->view->noMergedProjects = $noMergedProjects;
        }
        if($type == 'moreLink')
        {
            $noMergedProjects = $this->dao->select('*')->from(TABLE_PROJECT)->where('program')->eq(0)->andWhere('template')->eq('')->andWhere('deleted')->eq(0)->fetchAll('id');
            $projectProducts  = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedProjects))->fetchGroup('project', 'product');

            $productPairs = array();
            foreach($projectProducts as $projectID => $products)
            {
                foreach($products as $productID => $data) $productPairs[$productID] = $productID;
            }

            $programes = $this->dao->select('t1.*,t2.id as productID')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.id=t2.program')
                ->where('t2.id')->in($productPairs)
                ->fetchAll('productID');

            foreach($noMergedProjects as $projectID => $project)
            {
                $products = zget($projectProducts, $projectID, array());
                foreach($projects as $productID => $data)
                {
                    $program = zget($programs, $productID, '');
                    if($program) $project->programs[$program->id] = $program->name;
                }
            }

            $this->view->noMergedProjects = $noMergedProjects;
        }

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
