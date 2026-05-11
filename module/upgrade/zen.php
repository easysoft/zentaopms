<?php
declare(strict_types=1);
class upgradeZen extends upgrade
{
    /**
     * 升级变更内容缓存。
     * Upgrade changes cache.
     *
     * @var array
     * @access private
     */
    private $upgradeChanges = [];

    /**
     * 获取要升级到的版本列表。
     * Get upgrade versions.
     *
     * @param  string $fromVersion
     * @access protected
     * @return string[]
     */
    protected function getUpgradeVersions(string $fromVersion): array
    {
        $upgradeVersions = [];
        $currentEdition  = $this->config->edition;
        $fromEdition     = $this->upgrade->getEditionByVersion($fromVersion);
        $fromOpenVersion = $this->upgrade->getOpenVersion(str_replace('.', '_', $this->config->installedVersion));

        if($currentEdition == 'open')
        {
            /**
             * 如果当前版本是开源版，则列出所有大于 fromVersion 的开源版版本。
             * 比如 fromVersion 为 21_7_6，当前版本为 21_7_8，则列出 21_7_7 和 21_7_8。
             * If the current edition is open, list all versions greater than fromVersion.
             * For example, if fromVersion is 21_7_6 and the current version is 21_7_8, list 21_7_7 and 21_7_8.
             */
            foreach($this->lang->upgrade->fromVersions as $version => $label)
            {
                if(!is_numeric($version[0])) continue;
                if(version_compare($version, $fromOpenVersion, '<=')) continue;

                $upgradeVersions[$version] = $label;
            }
            return $upgradeVersions;
        }

        if($currentEdition == $fromEdition)
        {
            /**
             * 如果当前版本和 fromVersion 版本属于同一付费版，则列出所有大于 fromVersion 的该付费版版本。
             * 比如 fromVersion 为 biz12_6，当前版本为 biz12_8，则列出 biz12_7 和 biz12_8。
             * If the current edition is the same as fromEdition, list all versions greater than fromVersion of the same edition.
             * For example, if fromVersion is biz12_6 and the current version is biz12_8, list biz12_7 and biz12_8.
             */
            foreach($this->lang->upgrade->fromVersions as $version => $label)
            {
                if(strpos($version, $currentEdition) !== 0) continue;
                if(version_compare($version, $fromVersion, '<=')) continue;

                $upgradeVersions[$version] = $label;
            }
        }
        else
        {
            $currentMapVersions = $this->config->upgrade->{$currentEdition . 'Version'};
            if(array_search($fromOpenVersion, $currentMapVersions))
            {
                /**
                 * 如果当前版本和 fromVersion 版本属于不同付费版，但 fromVersion 可以映射到当前付费版，则列出所有大于等于 fromVersion 映射版本的该付费版版本。
                 * 比如 fromVersion 为 biz12_6，当前版本为 max12_8，则列出 max12_6、max12_7 和 max12_8。
                 * If the current edition is different from fromEdition, but fromVersion can be mapped to the current edition, list all versions greater than or equal to the mapped version of the current edition.
                 */
                foreach($currentMapVersions as $version => $openVersion)
                {
                    if(version_compare($openVersion, $fromOpenVersion, '<')) continue;

                    $upgradeVersions[$version] = $this->lang->upgrade->fromVersions[$version] ?? '';
                }
            }
            else
            {
                /**
                 * 查找距离 fromVersion 最近的当前付费版版本和开源版版本。
                 * 比如 fromVersion 为 18_1，当前版本为 ipd4_7，则 currentMappedVersion 为 ipd1_0_beta1, currentMappedOpenVersion 为 18_4_alpha1
                 * Find the nearest current edition version and open version from fromVersion.
                 * For example, if fromVersion is 18_1 and the current version is ipd4_7, then currentMappedVersion is ipd1_0_beta1 and currentMappedOpenVersion is 18_4_alpha1.
                 */
                $currentMappedVersion     = '';
                $currentMappedOpenVersion = '';
                foreach($currentMapVersions as $version => $openVersion)
                {
                    if(version_compare($openVersion, $fromOpenVersion, '>'))
                    {
                        $currentMappedVersion     = $version;
                        $currentMappedOpenVersion = $openVersion;
                        break;
                    }
                }

                if($fromEdition == 'open')
                {
                    /**
                     * 如果 fromVersion 是开源版，则列出所有大于 fromVersion 且小于距离 fromVersion 最近的当前付费版映射的开源版版本。
                     * 比如 fromVersion 为 18_1，当 前版本为 ipd4_7，则列出大于 18_1 且小于 18_4_alpha1 的所有开源版版本，即 18_2 和 18_3。
                     * If fromEdition is open, list all versions greater than fromVersion and less than the open version mapped by the current edition nearest to fromVersion.
                     * For example, if fromVersion is 18_1 and the current version is ipd4_7, list all open versions greater than 18_1 and less than 18_4_alpha1, i.e., 18_2 and 18_3.
                     */
                    foreach($this->lang->upgrade->fromVersions as $version => $label)
                    {
                        if(!is_numeric($version[0])) continue;
                        if(version_compare($version, $fromOpenVersion, '<=')) continue;
                        if(!empty($currentMappedOpenVersion) && version_compare($version, $currentMappedOpenVersion, '>=')) break;

                        $upgradeVersions[$version] = $label;
                    }
                }
                else
                {
                    /**
                     * 如果 fromVersion 是付费版，则列出所有大于 fromVersion 映射的开源版版本且小于距离 fromVersion 最近的当前付费版映射的开源版版本对应的付费版版本。
                     * 比如 fromVersion 为 biz8_1，当前版本为 ipd4_7，则列出大于 18_1 且小于 18_4_alpha1 的所有开源版版本对应的付费版版本，即 biz8_2 和 biz8_3。
                     * If fromEdition is charged, list all open versions greater than the open version mapped by fromVersion and less than the open version mapped by the current edition nearest to fromVersion.
                     * For example, if fromVersion is biz8_1 and the current version is ipd4_7, list all open versions greater than 18_1 and less than 18_4_alpha1, i.e., 18_2 and 18_3.
                     */
                    $fromMapVersions = $this->config->upgrade->{$fromEdition . 'Version'};
                    foreach($fromMapVersions as $version => $openVersion)
                    {
                        if(version_compare($openVersion, $fromOpenVersion, '<=')) continue;
                        if(!empty($currentMappedOpenVersion) && version_compare($openVersion, $currentMappedOpenVersion, '>=')) break;

                        $upgradeVersions[$version] = $this->lang->upgrade->fromVersions[$version] ?? '';
                    }
                }

                /**
                 * 列出所有大于等于距离 fromVersion 最近的当前付费版版本。
                 * 比如 fromVersion 为 biz8_1，当前版本为 ipd4_7，则列出 ipd1_0_beta1 及之后的所有版本。
                 * List all versions greater than or equal to the current edition version nearest to fromVersion.
                 * For example, if fromVersion is biz8_1 and the current version is ipd4_7, list ipd1_0_beta1 and all subsequent versions.
                 */
                foreach(array_keys($currentMapVersions) as $version)
                {
                    if(!empty($currentMappedVersion) && version_compare($version, $currentMappedVersion, '<')) continue;

                    $upgradeVersions[$version] = $this->lang->upgrade->fromVersions[$version] ?? '';
                }
            }
        }

        $currentVersion = str_replace('.', '_', $this->config->version);
        if($currentEdition == 'ipd' && empty($upgradeVersions[$currentVersion])) $upgradeVersions[$currentVersion] = ucfirst($this->config->version);

        return $upgradeVersions;
    }

    /**
     * 获取升级变更内容列表。
     * Get upgrade changes.
     *
     * @param  string $fromVersion
     * @param  string $toVersion
     * @access protected
     * @return array[]
     */
    protected function getUpgradeChanges(string $fromVersion, string $toVersion): array
    {
        $this->upgradeChanges = [];

        $currentVersion  = str_replace('.', '_', $this->config->installedVersion);
        $fromEdition     = $this->upgrade->getEditionByVersion($fromVersion);
        $fromOpenVersion = $this->upgrade->getOpenVersion($currentVersion);
        $toOpenVersion   = $this->upgrade->getOpenVersion($toVersion);
        $upgradeVersions = $this->upgrade->getVersionsToUpdate($fromOpenVersion, $fromEdition);

        $changes = [];
        foreach($upgradeVersions as $openVersion => $chargedVersions)
        {
            if(version_compare($openVersion, $fromOpenVersion, '<')) continue;
            if(version_compare($openVersion, $toOpenVersion, '>='))  continue;

            $sqlFile = $this->upgrade->getUpgradeFile(str_replace('_', '.', $openVersion));
            $changes = array_merge($changes, $this->getChangesBySql($openVersion, $sqlFile));
            $changes = array_merge($changes, $this->getChangesByConfig($openVersion));

            foreach($chargedVersions as $chargedVersion)
            {
                foreach($chargedVersion as $version)
                {
                    $sqlFile = $this->upgrade->getUpgradeFile(str_replace('_', '.', $version));
                    $changes = array_merge($changes, $this->getChangesBySql($version, $sqlFile));
                    $changes = array_merge($changes, $this->getChangesByConfig($version));
                }
            }
        }

        /* 如果此次升级到最终版本，则执行额外的数据处理流程。*/
        if(version_compare($toVersion, $this->config->version, '='))
        {
            $edition = $this->upgrade->getEditionByVersion($fromVersion);
            $methods = $this->upgrade->getOtherMethods($edition);
            foreach(array_keys($methods) as $method)
            {
                $changes[] = $this->getChangesByMethod($currentVersion, $method);
            }
        }

        return $changes;
    }

    /**
     * 获取配置文件中的变更内容列表。
     * Get changes by config.
     *
     * @param  string $version
     * @access protected
     * @return array[]
     */
    protected function getChangesByConfig(string $version): array
    {
        $changes     = [];
        $functions   = $this->config->upgrade->execFlow[$version]['functions']   ?? '';
        $xxsqls      = $this->config->upgrade->execFlow[$version]['xxsqls']      ?? '';
        $xxfunctions = $this->config->upgrade->execFlow[$version]['xxfunctions'] ?? '';

        foreach(array_filter(explode(',', $functions)) as $function)
        {
            $changes[] = $this->getChangesByMethod($version, $function);
        }

        if($version == 'pro1_1_1')
        {
            $sqlFile    = $this->upgrade->getUpgradeFile('pro1.1');
            $sqlChanges = $this->getChangesBySql($version, $sqlFile);
            $changes    = array_merge($changes, $sqlChanges);
        }
        if($version == 'pro8_3')
        {
            $sqlFile    = $this->ugprade->getUpgradeFile('pro8.2');
            $sqlChanges = $this->getChangesBySql($version, $sqlFile);
            $changes    = array_merge($changes, $sqlChanges);
        }
        if(!empty($xxsqls))
        {
            foreach(array_filter(explode(',', $xxsqls)) as $sqlFile)
            {
                $sqlChanges = $this->getChangesBySql($version, $sqlFile);
                $changes    = array_merge($changes, $sqlChanges);
            }
        }
        if(!empty($xxfunctions))
        {
            foreach(array_filter(explode(',', $xxfunctions)) as $function)
            {
                $changes[] = $this->getChangesByMethod($version, $function);
            }
        }

        return $changes;
    }

    /**
     * 获取 SQL 文件中的变更内容列表。
     * Get changes by sql file.
     *
     * @param  string $version
     * @param  string $sqlFile
     * @access protected
     * @return array[]
     */
    protected function getChangesBySql(string $version, string $sqlFile): array
    {
        if(!is_file($sqlFile)) return [];

        $changes = [];
        $sqls    = $this->upgrade->parseToSqls($sqlFile);
        foreach($sqls as $sql)
        {
            $sqlMd5  = md5($sql);
            $fileMd5 = md5($sqlFile);
            if(isset($this->upgradeChanges[$version]['sqls'][$fileMd5][$sqlMd5])) continue;

            $this->upgradeChanges[$version]['sqls'][$fileMd5][$sqlMd5] = true;

            $items = helper::parseSqlToSemantic($sql);
            foreach($items as $item)
            {
                $search  = ['%TABLE%', '%FIELD%', '%INDEX%', '%VIEW%', '%OLD%', '%NEW%'];
                $replace = [$item['table'] ?? '', $item['field'] ?? '', $item['index'] ?? '', $item['view'] ?? '', $item['old'] ?? '', $item['new'] ?? ''];
                $subject = $this->lang->upgrade->changeActions[$item['action']] ?? $this->lang->upgrade->changeActions['other'];
                $content = str_replace($search, $replace, $subject);
                $changes[] = ['version' => $version, 'type' => 'sql', 'mode' => $item['mode'], 'content' => $content, 'sql' => $sql, 'fileMd5' => $fileMd5, 'sqlMd5' => $sqlMd5];
            }
        }
        return $changes;
    }

    /**
     * 获取方法变更内容。
     * Get changes by method.
     *
     * @param  string $version
     * @param  string $rawMethod
     * @access protected
     * @return array
     */
    protected function getChangesByMethod(string $version, string $rawMethod): array
    {
        if(isset($this->upgradeChanges[$version]['methods'][$rawMethod])) return [];

        $this->upgradeChanges[$version]['methods'][$rawMethod] = true;

        $module = 'upgrade';
        $method = $rawMethod;
        if(strpos($rawMethod, '-') !== false)
        {
            list($module, $method) = explode('-', $rawMethod);
        }
        $content = str_replace(['%MODULE%', '%METHOD%'], [$module, $method], $this->lang->upgrade->changeActions['method']);
        return ['version' => $version, 'type' => 'method', 'mode' => 'update', 'content' => $content, 'method' => $rawMethod];
    }

    /**
     * 升级 sql 成功执行后的操作。
     * Operations after successful execution.
     *
     * @param  string    $fromVersion
     * @access protected
     * @return string
     */
    protected function getRedirectUrlAfterExecute(string $fromVersion): string
    {
        /* Delete all patch actions if upgrade success. */
        $this->loadModel('action')->deleteByType('patch');

        $selectMode = true;
        $systemMode = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=mode');
        /* 如果经典管理模式。*/
        /* If the system mode is classic. */
        if($systemMode == 'classic' && $this->config->edition != 'ipd')
        {
            $this->upgradeFromClassicMode();
            $selectMode = false;
        }

        /* 从15 版本以后升级。*/
        /* when upgrade from the vesion is more than 15. */
        $rawFromVersion = $fromVersion;
        if(strpos($rawFromVersion, 'lite') !== false) $rawFromVersion = $this->config->upgrade->liteVersion[$fromVersion];
        $openVersion = $this->upgrade->getOpenVersion($rawFromVersion);
        if(version_compare($openVersion, '15_0_rc1', '>=') && $systemMode == 'new')
        {
            $this->setting->setItem('system.common.global.mode', 'ALM');
            $selectMode = false;
        }
        if(version_compare($openVersion, '18_0_beta1', '>=')) $selectMode = false;

        /* 如果是 ipd 版本，设置相关的配置。*/
        /* When the edition is ipd. */
        if($this->config->edition == 'ipd') $this->setIpdItems($openVersion);

        $this->setting->setItem('system.common.userview.relatedTablesUpdateTime', time());

        if($selectMode)
        {
            if($this->config->edition == 'ipd') return inlink('to18Guide', "fromVersion={$fromVersion}&mode=ALM");
            return inlink('to18Guide', "fromVersion={$fromVersion}");
        }

        return inlink('afterExec', "fromVersion={$fromVersion}");
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
     * @param  string  $openVersion
     * @access private
     * @return void
     */
    private function setIpdItems($openVersion = ''): void
    {
        $this->loadModel('setting')->setItem('system.common.global.mode', 'PLM');
        $this->setting->setItem('system.custom.URAndSR', '1');
        $this->upgrade->addORPriv($openVersion);
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
        $result = $this->upgrade->createProgram($linkedSprints);
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
        $result = $this->upgrade->createProgram($linkedSprints);
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
     * @param  string    $projectType
     * @access protected
     * @return void
     */
    protected function mergeBySprint(string $projectType)
    {
        $linkedSprints = $this->post->sprints;

        /* Create Program. */
        $result = $this->upgrade->createProgram($linkedSprints);
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
        list($programID, $projectList, $lineID) = $this->upgrade->createProgram($linkedSprints);
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
     * 显示需要执行的命令。
     * Display command.
     *
     * @param  string    $command
     * @param  string    $tips
     * @access protected
     * @return void
     */
    protected function displayCommand(string $command, string $tips = ''): void
    {
        $this->view->title   = $this->lang->upgrade->common;
        $this->view->result  = 'fail';
        $this->view->command = $command;
        $this->view->tips    = $tips ?: $this->lang->upgrade->execCommand;
        $this->display('upgrade', 'command');
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
        $showPrivTips = false;
        if(is_numeric($fromVersion[0]) and version_compare($fromVersion, '18.9', '<='))               $showPrivTips = true;
        if(strpos($fromVersion, 'pro') !== false)                                                     $showPrivTips = true;
        if(strpos($fromVersion, 'biz') !== false and version_compare($fromVersion, 'biz8.9',   '<=')) $showPrivTips = true;
        if(strpos($fromVersion, 'max') !== false and version_compare($fromVersion, 'max4.9',   '<=')) $showPrivTips = true;
        if(strpos($fromVersion, 'ipd') !== false and version_compare($fromVersion, 'ipd1.1.1', '<=')) $showPrivTips = true;
        if($showPrivTips and $this->config->edition == 'open') $showPrivTips = false;

        $this->view->title        = $this->lang->upgrade->result;
        $this->view->needProcess  = $needProcess;
        $this->view->fromVersion  = $fromVersion;
        $this->view->showPrivTips = $showPrivTips;

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
        $zfile = $this->app->loadClass('zfile');
        $zfile->removeDir($this->app->getTmpRoot() . 'model/');

        $installFile = $this->app->getAppRoot() . 'www/install.php';
        $upgradeFile = $this->app->getAppRoot() . 'www/upgrade.php';
        if(file_exists($installFile)) @unlink($installFile);
        if(file_exists($upgradeFile)) @unlink($upgradeFile);
        unset($_SESSION['upgrading']);
    }
}
