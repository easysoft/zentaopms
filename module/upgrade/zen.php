<?php
declare(strict_types=1);
class upgradeZen extends upgrade
{
    /**
     * 升级 sql 成功执行后的操作。
     * Operations after successful execution.
     *
     * @param  string    $fromVersion
     * @param  string    $rawFromVersion
     * @access protected
     * @return void
     */
    protected function afterExecuteSql(string $fromVersion, string $rawFromVersion): void
    {
        $this->loadModel('setting')->updateVersion($this->config->version);

        /* Delete all patch actions if upgrade success. */
        $this->loadModel('action')->deleteByType('patch');

        $selectMode = true;
        $systemMode = $this->setting->getItem('owner=system&module=common&section=global&key=mode');
        /* 如果经典管理模式。*/
        /* If the system mode is classic. */
        if($systemMode == 'classic')
        {
            $this->upgradeFromClassicMode();
            $selectMode = false;
        }

        /* 从15 版本以后升级。*/
        /* when upgrade from the vesion is more than 15. */
        $openVersion = $this->upgrade->getOpenVersion(str_replace('.', '_', $rawFromVersion));
        if(version_compare($openVersion, '15_0_rc1', '>=') && $systemMode == 'new')
        {
            $this->setting->setItem('system.common.global.mode', 'ALM');
            if(empty($this->config->URAndSR)) $this->setting->setItem('system.common.closedFeatures', 'productUR');
            $selectMode = false;
        }
        if(version_compare($openVersion, '18_0_beta1', '>=')) $selectMode = false;

        /* 如果是 ipd 版本，设置相关的配置。*/
        /* When the edition is ipd. */
        if($this->config->edition == 'ipd' && strpos($fromVersion, 'ipd') === false) $this->SetIpdItems();

        if($selectMode)
        {
            if($this->config->edition == 'ipd') $this->locate(inlink('to18Guide', "fromVersion={$fromVersion}&mode=ALM"));
            $this->locate(inlink('to18Guide', "fromVersion={$fromVersion}"));
        }

        $this->locate(inlink('afterExec', "fromVersion={$fromVersion}"));
    }

    /**
     * 从经典模式升级后的处理。
     * Process after upgrade from classic mode.
     *
     * @access private
     * @return void
     */
    private function upgradeFromClassicMode(): void
    {
        $this->loadModel('setting')->setItem('system.common.global.mode', 'light');

        $programID = $this->setDefaultProgram();

        $_POST['projectType'] = 'execution';
        $this->upgrade->upgradeInProjectMode($programID, 'classic');

        $this->upgrade->computeObjectMembers();
        $this->upgrade->initUserView();
        $this->upgrade->setDefaultPriv();
        $this->dao->update(TABLE_CONFIG)->set('value')->eq('0_0')->where('`key`')->eq('productProject')->exec();

        $hourPoint = $this->setting->getItem('owner=system&module=custom&key=hourPoint');
        if(empty($hourPoint)) $this->setting->setItem('system.custom.hourPoint', 0);

        $sprints = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('sprint')->fetchAll('id');
        $this->dao->update(TABLE_ACTION)->set('objectType')->eq('execution')->where('objectID')->in(array_keys($sprints))->andWhere('objectType')->eq('project')->exec();

        $this->loadModel('custom')->disableFeaturesByMode('light');
    }

    /**
     * Ipd 版本升级后的处理。
     * Set ipd items.
     *
     * @access private
     * @return void
     */
    private function setIpdItems(): void
    {
        $this->loadModel('setting')->setItem('system.common.global.mode', 'PLM');
        $this->setting->setItem('system.custom.URAndSR', '1');
        $this->setting->setItem('system.common.closedFeatures', '');
        $this->setting->setItem('system.common.disabledFeatures', '');
        $this->upgrade->addORPriv();
    }

    /**
     * 设置迭代的概念。
     * Set sprint concept.
     *
     * @access protected
     * @return void
     */
    protected function setSprintConcept(): void
    {
        $sprintConcept = 0;
        if(isset($this->config->custom->sprintConcept))
        {
            if($this->config->custom->sprintConcept == 2) $sprintConcept = 1;
        }
        elseif(isset($this->config->custom->productProject))
        {
            $projectConcept = substr($this->config->custom->productProject, strpos($this->config->custom->productProject, '_'));
            if($projectConcept == 2) $sprintConcept = 1;
        }
        $this->loadModel('setting')->setItem('system.custom.sprintConcept', $sprintConcept);
    }

    /**
     * 创建默认项目集，并且将项目关联到默认项目集。
     * Set default program.
     *
     * @access protected
     * @return int
     */
    protected function setDefaultProgram(): int
    {
        $programID = $this->loadModel('program')->createDefaultProgram();
        $this->loadModel('setting')->setItem('system.common.global.defaultProgram', $programID);

        /* Set default program for product and project with no program. */
        $this->upgrade->relateDefaultProgram($programID);

        return $programID;
    }

    /**
     * 合并后的升级操作。
     * Upgrade after merged.
     *
     * @access protected
     * @return void
     */
    protected function upgradeAfterMerged()
    {
        $this->upgrade->computeObjectMembers();
        $this->upgrade->initUserView();
        $this->upgrade->setDefaultPriv();
        $this->dao->update(TABLE_CONFIG)->set('value')->eq('0_0')->where('`key`')->eq('productProject')->exec();

        /* Set defult hourPoint. */
        $hourPoint = $this->loadModel('setting')->getItem('owner=system&module=custom&key=hourPoint');
        if(empty($hourPoint)) $this->setting->setItem('system.custom.hourPoint', 0);

        /* Update sprints history. */
        $sprints = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('sprint')->fetchAll('id');
        $this->dao->update(TABLE_ACTION)->set('objectType')->eq('execution')->where('objectID')->in(array_keys($sprints))->andWhere('objectType')->eq('project')->exec();
        $this->locate($this->createLink('upgrade', 'mergeRepo'));
    }

    /**
     * 获取产品线下的产品和项目。
     * Get products and projects group by product line.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function assignProductsAndProjectsGroupByProductline(string $projectType)
    {
        $productlines = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('root')->eq(0)->orderBy('id_desc')->fetchAll('id');

        $noMergedProducts = $this->dao->select('*')->from(TABLE_PRODUCT)->where('line')->in(array_keys($productlines))->andWhere('vision')->eq('rnd')->orderBy('id_desc')->fetchAll('id');
        if(empty($productlines) || empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', "type=product&programID=0&projectType=$projectType"));

        /* Group product by product line. */
        $lineGroups = array();
        foreach($noMergedProducts as $product) $lineGroups[$product->line][$product->id] = $product;

        foreach($productlines as $line)
        {
            if(!isset($lineGroups[$line->id])) unset($productlines[$line->id]);
        }

        $noMergedSprints = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t1.project')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.vision')->eq('rnd')
            ->andWhere('t1.type')->eq('sprint')
            ->andWhere('t2.product')->in(array_keys($noMergedProducts))
            ->orderBy('t1.id_desc')
            ->fetchAll('id');

        /* Remove sprint that linked more than two products */
        $sprintProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');
        foreach($sprintProducts as $sprintID => $products)
        {
            if(count($products) > 1) unset($noMergedSprints[$sprintID]);
        }

        /* Group sprint by product. */
        $productGroups = array();
        foreach($noMergedSprints as $sprint)
        {
            $sprintProduct = zget($sprintProducts, $sprint->id, array());
            if(empty($sprintProduct)) continue;

            $productID = key($sprintProduct);
            $productGroups[$productID][$sprint->id] = $sprint;
        }

        $this->view->productlines  = $productlines;
        $this->view->lineGroups    = $lineGroups;
        $this->view->productGroups = $productGroups;
    }

    /**
     * 获取产品下的项目。
     * Get projects group by product.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function assignProjectsGroupByProduct(string $projectType)
    {
        $noMergedSprints = $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t2.model')->eq('')
            ->andWhere('t2.project')->eq(0)
            ->andWhere('t2.vision')->eq('rnd')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.type')->eq('sprint')
            ->fetchAll('id');

        /* Remove project that linked more than two products */
        $sprintProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');
        foreach($sprintProducts as $sprintID => $products)
        {
            if(count($products) > 1) unset($noMergedSprints[$sprintID]);
        }

        /* Get products that are not merged by sprints. */
        $noMergedProducts = array();
        if($noMergedSprints)
        {
            $noMergedProducts = $this->dao->select('t1.*')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.product')
                ->where('t2.project')->in(array_keys($noMergedSprints))
                ->andWhere('t1.vision')->eq('rnd')
                ->fetchAll('id');
        }

        /* Add products without sprints. */
        $noMergedProducts += $this->dao->select('*')->from(TABLE_PRODUCT)->where('program')->eq(0)->andWhere('vision')->eq('rnd')->fetchAll('id');

        if(empty($noMergedProducts)) $this->locate($this->createLink('upgrade', 'mergeProgram', "type=sprint&programID=0&projectType=$projectType"));

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

    /**
     * 获取未关联产品的迭代。
     * Get sprints without product.
     *
     * @access protected
     * @return void
     */
    protected function assignSprintsWithoutProduct()
    {
        $noMergedSprints = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('project')->eq(0)
            ->andWhere('vision')->eq('rnd')
            ->andWhere('type')->eq('sprint')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchAll('id');

        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');
        foreach(array_keys($projectProducts) as $sprintID) unset($noMergedSprints[$sprintID]);

        if(empty($noMergedSprints)) $this->locate($this->createLink('upgrade', 'mergeProgram', "type=moreLink"));

        $this->view->noMergedSprints = $noMergedSprints;
    }

    /**
     * 获取关联了多个产品项目。
     * Get no merged projects that link more than two products.
     *
     * @access protected
     * @return void
     */
    protected function assignSprintsWithMoreProducts()
    {
        $noMergedSprints = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('project')->eq(0)
            ->andWhere('vision')->eq('rnd')
            ->andWhere('type')->eq('sprint')
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchAll('id');

        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($noMergedSprints))->fetchGroup('project', 'product');

        $productPairs = array();
        foreach($projectProducts as $sprintID => $products)
        {
            foreach(array_keys($products) as $productID) $productPairs[$productID] = $productID;
        }

        $projects = $this->dao->select('t1.*, t2.product AS productID')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.product')->in($productPairs)
            ->andWhere('t1.vision')->eq('rnd')
            ->andWhere('t1.type')->eq('project')
            ->fetchAll('productID');

        foreach($noMergedSprints as $sprintID => $sprint)
        {
            $products = zget($projectProducts, $sprintID, array());
            foreach(array_keys($products) as $productID)
            {
                $project = zget($projects, $productID, '');
                if($project) $sprint->projects[$project->id] = $project->name;
            }

            if(!isset($sprint->projects)) $sprint->projects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->andWhere('vision')->eq('rnd')->fetchPairs();
        }

        $this->view->noMergedSprints = $noMergedSprints;
    }

    /**
     * 合并按产品线分组的产品和迭代。
     * Merge products and projects group by productline.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function mergeByProductline(string $projectType)
    {
        /* Compute checked products and sprints, unchecked products and sprints. */
        $linkedProducts = array();
        $linkedSprints  = array();
        $unlinkSprints  = array();
        $sprintProducts = array();
        foreach($_POST['products'] as $lineID => $products)
        {
            foreach($products as $productID)
            {
                $linkedProducts[$productID] = $productID;

                if(!isset($_POST['sprints'][$lineID][$productID])) continue;

                foreach($_POST['sprints'][$lineID][$productID] as $sprintID)
                {
                    $linkedSprints[$sprintID]  = $sprintID;
                    $sprintProducts[$sprintID] = $productID;
                    unset($_POST['sprintIdList'][$lineID][$productID][$sprintID]);
                }
                $unlinkSprints[$productID] = $this->post->sprintIdList[$lineID][$productID];
            }
        }

        /* Create Program. */
        $result = $this->upgrade->createProgram($linkedProducts, $linkedSprints);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(isset($result['result']) && $result['result'] == 'fail') return $this->send($result);

        list($programID, $projectList, $lineID) = $result;

        /* Process merged products and projects. */
        if($projectType == 'execution')
        {
            /* Use historical projects as execution upgrades. */
            $this->upgrade->processMergedData($programID, $projectList, $lineID, $linkedProducts, $linkedSprints);
        }
        else
        {
            /* Use historical projects as project upgrades. */
            foreach($linkedSprints as $sprint) $this->upgrade->processMergedData($programID, zget($projectList, $sprint, array()), $lineID, array($sprintProducts[$sprint] => $sprintProducts[$sprint]), array($sprint => $sprint));

            /* When upgrading historical data as a project, handle products that are not linked with the project. */
            $singleProducts = array_diff($linkedProducts, $sprintProducts);
            if(!empty($singleProducts)) $this->upgrade->computeProductAcl($singleProducts, $programID, $lineID);
        }

        /* Process unlinked sprint and product. */
        foreach(array_keys($linkedProducts) as $productID)
        {
            if((isset($unlinkSprints[$productID]) && empty($unlinkSprints[$productID])) || !isset($unlinkSprints[$productID])) $this->dao->update(TABLE_PRODUCT)->set('line')->eq($lineID)->where('id')->eq($productID)->exec();
        }
    }

    /**
     * 合并按产品分组的产品和迭代。
     * Merge products and projects group by product.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function mergeByProduct(string $projectType)
    {
        $linkedProducts = array();
        $linkedSprints  = array();
        $unlinkSprints  = array();
        $sprintProducts = array();
        foreach($_POST['products'] as $productID)
        {
            $linkedProducts[$productID] = $productID;

            if(isset($_POST['sprints'][$productID]))
            {
                foreach($_POST['sprints'][$productID] as $sprintID)
                {
                    $linkedSprints[$sprintID]  = $sprintID;
                    $sprintProducts[$sprintID] = $productID;
                    unset($_POST['sprintIdList'][$productID][$sprintID]);
                }
                $unlinkSprints += $this->post->sprintIdList[$productID];
            }
        }

        /* Create Program. */
        $result = $this->upgrade->createProgram($linkedProducts, $linkedSprints);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(isset($result['result']) && $result['result'] == 'fail') return $this->send($result);

        list($programID, $projectList, $lineID) = $result;

        /* Process productline. */
        $this->dao->delete()->from(TABLE_MODULE)->where('`root`')->eq(0)->andWhere('`type`')->eq('line')->exec();

        /* Process merged products and projects. */
        if($projectType == 'execution')
        {
            /* Use historical projects as execution upgrades. */
            $this->upgrade->processMergedData($programID, $projectList, $lineID, $linkedProducts, $linkedSprints);
        }
        else
        {
            /* Use historical projects as project upgrades. */
            foreach($linkedSprints as $sprint) $this->upgrade->processMergedData($programID, $projectList[$sprint], $lineID, array($sprintProducts[$sprint] => $sprintProducts[$sprint]), array($sprint => $sprint));

            /* When upgrading historical data as a project, handle products that are not linked with the project. */
            $singleProducts = array_diff($linkedProducts, $sprintProducts);
            if(!empty($singleProducts)) $this->upgrade->computeProductAcl($singleProducts, $programID, $lineID);
        }
    }

    /**
     * 合并没有关联产品的迭代。
     * Merge sprints without product.
     *
     * @access protected
     * @return void
     */
    protected function mergeBySprint()
    {
        $linkedSprints = $this->post->sprints;

        /* Create Program. */
        $result = $this->upgrade->createProgram(array(), $linkedSprints);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(isset($result['result']) && $result['result'] == 'fail') return $this->send($result);

        list($programID, $projectList, $lineID) = $result;

        if($projectType == 'execution')
        {
            /* Use historical projects as execution upgrades. */
            $this->upgrade->processMergedData($programID, $projectList, $lineID, array(), $linkedSprints);
        }
        else
        {
            /* Use historical projects as project upgrades. */
            foreach($linkedSprints as $sprint) $this->upgrade->processMergedData($programID, $projectList[$sprint], $lineID, array(), array($sprint => $sprint));
        }
    }

    /**
     * 合并关联多个产品的迭代。
     * Merge sprints with more than one product.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function mergeByMoreLink(string $projectType)
    {
        $linkedSprints = $this->post->sprints;

        /* Create Program. */
        list($programID, $projectList, $lineID) = $this->upgrade->createProgram(array(), $linkedSprints);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if($projectType == 'execution')
        {
            /* Use historical projects as execution upgrades. */
            $this->upgrade->processMergedData($programID, $projectList, $lineID, array(), $linkedSprints);
        }
        else
        {
            /* Use historical projects as project upgrades. */
            foreach($linkedSprints as $sprint) $this->upgrade->processMergedData($programID, $projectList[$sprint], $lineID, array(), array($sprint => $sprint));
        }

        /* If is more-link sprints, and as project upgrade, set old relation into new project. */
        $projectProducts = $this->dao->select('product,project,branch,plan')->from(TABLE_PROJECTPRODUCT)->where('project')->in($linkedSprints)->fetchAll();

        foreach($projectProducts as $projectProduct)
        {
            $data = new stdclass();
            $data->project = $projectType == 'execution' ? $projectList : $projectList[$projectProduct->project];
            $data->product = $projectProduct->product;
            $data->plan    = $projectProduct->plan;
            $data->branch  = $projectProduct->branch;

            $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
        }
    }

    /**
     * 显示更改冲突的 sql。
     * Display consistency.
     * 
     * @param  string $alterSQL 
     * @access protected
     * @return void
     */
    protected function displayConsistency(string $alterSQL): void
    {
        $logFile  = $this->upgrade->getConsistencyLogFile();
        if(file_exists($logFile)) unlink($logFile);

        $this->view->title    = $this->lang->upgrade->consistency;
        $this->view->hasError = $this->upgrade->hasConsistencyError();
        $this->view->alterSQL = $alterSQL;
        $this->view->version  = $this->config->version;

        $this->display('upgrade', 'consistency');
    }

    /**
     * 显示需要移除的文件。
     * Display execute Error.
     *
     * @param  string    $command
     * @access protected
     * @return void
     */
    protected function displayExecuteError(array $commands): void
    {
        $this->view->title  = $this->lang->upgrade->common;
        $this->view->errors = $commands;
        $this->view->result = 'fail';

        $this->display('upgrade', 'execute');
    }

    /**
     * 显示待处理的提示。
     * Display execute process.
     *
     * @param  string    $fromVersion
     * @param  array     $needProcess
     * @access protected
     * @return void
     */
    protected function displayExecuteProcess(string $fromVersion, array $needProcess): void
    {
        $this->view->title       = $this->lang->upgrade->result;
        $this->view->needProcess = $needProcess;
        $this->view->fromVersion = $fromVersion;

        $this->display();
    }

    /**
     * 升级 sql 执行成功后的操作。
     * Process after execute sql successfully.
     *
     * @access protected
     * @return void
     */
    protected function processAfterExecSuccessfully(): void
    {
        $this->loadModel('setting')->updateVersion($this->config->version);

        $zfile = $this->app->loadClass('zfile');
        $zfile->removeDir($this->app->getTmpRoot() . 'model/');

        $installFile = $this->app->getAppRoot() . 'www/install.php';
        $upgradeFile = $this->app->getAppRoot() . 'www/upgrade.php';
        if(file_exists($installFile)) @unlink($installFile);
        if(file_exists($upgradeFile)) @unlink($upgradeFile);
        unset($_SESSION['upgrading']);
    }
}
