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
    protected function afterExecute(string $fromVersion, string $rawFromVersion): void
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

        $this->setDefaultProgram();

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
     * @return void
     */
    protected function setDefaultProgram(): void
    {
        $programID = $this->loadModel('program')->createDefaultProgram();
        $this->loadModel('setting')->setItem('system.common.global.defaultProgram', $programID);

        /* Set default program for product and project with no program. */
        $this->upgrade->relateDefaultProgram($programID);
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
     * 获取未合并的产品线。
     * Get unmerged product line.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function assignUnmergedProductLines(string $projectType)
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
     * 获取未合并的产品。
     * Get unmerged products.
     *
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function assignUnmergedProducts(string $projectType)
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
}
