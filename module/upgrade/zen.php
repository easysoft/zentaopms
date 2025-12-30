<?php
declare(strict_types=1);
class upgradeZen extends upgrade
{
    /**
     * 获取目标升级版本。
     * Get to upgrade version.
     *
     * @access protected
     * @return string
     */
    protected function getToVersion(): string
    {
        $upgradeVersions = $this->getUpgradeVersions(str_replace('.', '_', $this->config->installedVersion));
        $upgradeVersions = array_keys($upgradeVersions);
        return reset($upgradeVersions);
    }

    /**
     * 获取可升级的版本列表。
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

        /* 如果当前版本和来源版本不一致，则需要将来源版本转换为当前版本对应的版本号。*/
        if($currentEdition != $fromEdition)
        {
            $openVersion = $this->upgrade->getOpenVersion($fromVersion);
            $fromVersion = array_search($openVersion, $this->config->upgrade->{$currentEdition . 'Version'});
            if(empty($fromVersion)) return $upgradeVersions;
        }

        /* 如果当前版本和来源版本不一致，则需要包含来源版本对应的目标版本。比如旗舰版7.5升级到IPD版4.6，则需要包含IPD4.5。*/
        $operator = $currentEdition != $fromEdition ? '>' : '>=';

        foreach($this->lang->upgrade->fromVersions as $version => $label)
        {
            if(version_compare($fromVersion, $version, $operator)) continue;
            if($currentEdition == 'open' && !is_numeric($version[0])) continue;
            if($currentEdition != 'open' && strpos($version, $currentEdition) === false) continue;

            $upgradeVersions[$version] = $label;
        }

        $currentVersion = str_replace('.', '_', $this->config->version);
        $currentLabel   = ucfirst($this->config->version);
        $upgradeVersions[$currentVersion] = $currentLabel;

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
        $openVersion = $this->upgrade->getOpenVersion(str_replace('.', '_', $this->config->installedVersion));

        $changes = [];
        $sqlFile = $this->upgrade->getUpgradeFile(str_replace('_', '.', $openVersion));
        $changes = array_merge($changes, $this->getChangesBySql($sqlFile));
        $changes = array_merge($changes, $this->getChangesByConfig($openVersion));

        $upgraradeVersions = $this->upgrade->getVersionsToUpdate($openVersion, $this->config->edition);
        if(isset($upgraradeVersions[$openVersion]))
        {
            /* Execute charge edition. */
            foreach($upgraradeVersions[$openVersion] as $edition => $chargedVersions)
            {
                foreach($chargedVersions as $chargedVersion)
                {
                    if($edition == 'max') $chargedVersion = array_search($openVersion, $this->config->upgrade->maxVersion);
                    $sqlFile = $this->upgrade->getUpgradeFile(str_replace('_', '.', $chargedVersion));
                    $changes = array_merge($changes, $this->getChangesBySql($sqlFile));
                    $changes = array_merge($changes, $this->getChangesByConfig($chargedVersion));
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
                $changes[] = $this->getChangesByMethod($method);
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
            $changes[] = $this->getChangesByMethod($function);
        }

        if($version == 'pro1_1_1')
        {
            $sqlFile    = $this->ugprade->getUpgradeFile('pro1.1');
            $sqlChanges = $this->getChangesBySql($sqlFile);
            $changes    = array_merge($changes, $sqlChanges);
        }
        if($version == 'pro8_3')
        {
            $sqlFile    = $this->ugprade->getUpgradeFile('pro8.2');
            $sqlChanges = $this->getChangesBySql($sqlFile);
            $changes    = array_merge($changes, $sqlChanges);
        }
        if(!empty($xxsqls))
        {
            foreach(array_filter(explode(',', $xxsqls)) as $sqlFile)
            {
                $sqlChanges = $this->getChangesBySql($sqlFile);
                $changes    = array_merge($changes, $sqlChanges);
            }
        }
        if(!empty($xxfunctions))
        {
            foreach(array_filter(explode(',', $xxfunctions)) as $function)
            {
                $changes[] = $this->getChangesByMethod($function);
            }
        }

        return $changes;
    }

    /**
     * 获取 SQL 文件中的变更内容列表。
     * Get changes by sql file.
     *
     * @param  string $sqlFile
     * @access protected
     * @return array[]
     */
    protected function getChangesBySql(string $sqlFile): array
    {
        if(!is_file($sqlFile)) return [];

        $changes = [];
        $sqls    = $this->upgrade->parseToSqls($sqlFile);
        foreach($sqls as $sql)
        {
            $items = $this->parseSqlToSemantic($sql);
            foreach($items as $item)
            {
                $search  = ['%TABLE%', '%FIELD%', '%INDEX%', '%VIEW%', '%OLD%', '%NEW%'];
                $replace = [$item['table'] ?? '', $item['field'] ?? '', $item['index'] ?? '', $item['view'] ?? '', $item['old'] ?? '', $item['new'] ?? ''];
                $subject = $this->lang->upgrade->changeActions[$item['action']] ?? $this->lang->upgrade->changeActions['other'];
                $content = str_replace($search, $replace, $subject);
                $changes[] = ['type' => 'sql', 'mode' => $item['mode'], 'content' => $content, 'sql' => $sql];
            }
        }
        return $changes;
    }

    /**
     * 获取方法变更内容。
     * Get changes by method.
     *
     * @param  string $rawMethod
     * @access protected
     * @return array
     */
    protected function getChangesByMethod(string $rawMethod): array
    {
        $module = 'upgrade';
        $method = $rawMethod;
        if(strpos($rawMethod, '-') !== false)
        {
            list($module, $method) = explode('-', $rawMethod);
        }
        return ['type' => 'method', 'mode' => 'update', 'content' => str_replace(['%MODULE%', '%METHOD%'], [$module, $method], $this->lang->upgrade->changeActions['method']), 'method' => $rawMethod];
    }

    /**
     * 将 SQL 语句解析为语义化描述数组
     *
     * @param  string $sql 单条 SQL 语句
     * @access protected
     * @return array[]
     */
    protected function parseSqlToSemantic(string $sql): array
    {
        $sql = trim($sql);
        if($sql === '') return [];

        /* 移除末尾分号 */
        $sql      = rtrim($sql, " \t\n\r;");
        $sqlLower = strtolower($sql);

        if(preg_match('/^create\s+(or\s+replace\s+)?view\s+((?:`[^`]*`|\S)+)/i', $sql, $matches))               return [['mode' => 'create', 'action' => 'createView',  'view'  => $this->extractTableName($matches[2])]]; // CREATE VIEW / CREATE OR REPLACE VIEW
        if(preg_match('/^drop\s+view\s+(if\s+exists\s+)?((?:`[^`]*`|\S)+)/i', $sql, $matches))                  return [['mode' => 'delete', 'action' => 'dropView',    'view'  => $this->extractTableName($matches[2])]]; // DROP VIEW
        if(preg_match('/^create\s+(unique\s+)?index\s+`?([\w]+)`?\s+on\s+((?:`[^`]*`|\S)+)/i', $sql, $matches)) return [['mode' => 'create', 'action' => 'addIndex',    'table' => $this->extractTableName($matches[3]), 'index' => $this->extractTableName($matches[2])]]; // CREATE INDEX idx ON table_name
        if(preg_match('/^drop\s+index\s+`?([\w]+)`?\s+on\s+((?:`[^`]*`|\S)+)/i', $sql, $matches))               return [['mode' => 'delete', 'action' => 'dropIndex',   'table' => $this->extractTableName($matches[2]), 'index' => $this->extractTableName($matches[1])]]; // DROP INDEX idx ON table_name
        if(preg_match('/^create\s+table\s+(if\s+not\s+exists\s+)?((?:`[^`]*`|\S)+)/i', $sql, $matches))         return [['mode' => 'create', 'action' => 'createTable', 'table' => $this->extractTableName($matches[2])]]; // CREATE TABLE
        if(preg_match('/^drop\s+table\s+(if\s+exists\s+)?((?:`[^`]*`|\S)+)/i', $sql, $matches))                 return [['mode' => 'delete', 'action' => 'dropTable',   'table' => $this->extractTableName($matches[2])]]; // DROP TABLE
        if(preg_match('/^rename\s+table\s+((?:`[^`]*`|\S)+)\s+to\s+((?:`[^`]*`|\S)+)/i', $sql, $matches))       return [['mode' => 'update', 'action' => 'renameTable', 'old'   => $this->extractTableName($matches[1]), 'new' => $this->extractTableName($matches[2])]]; // RENAME TABLE
        if(preg_match('/^(insert|replace)\s+into\s+((?:`[^`]*`|\S)+)/i', $sql, $matches))                       return [['mode' => 'create', 'action' => 'insertValue', 'table' => $this->extractTableName($matches[2])]]; // INSERT / REPLACE
        if(preg_match('/^update\s+((?:`[^`]*`|\S)+)/i', $sql, $matches))                                        return [['mode' => 'update', 'action' => 'updateValue', 'table' => $this->extractTableName($matches[1])]]; // UPDATE
        if(preg_match('/^delete\s+from\s+((?:`[^`]*`|\S)+)/i', $sql, $matches))                                 return [['mode' => 'delete', 'action' => 'deleteValue', 'table' => $this->extractTableName($matches[1])]]; // DELETE

        /* ALTER TABLE */
        $sql = str_replace("\n", ' ', $sql); // 将换行替换为空格，方便后续处理
        if(preg_match('/^alter\s+table\s+((?:`[^`]*`|\S)+)\s+(.+)/i', $sql, $matches))
        {
            $tableName = $this->extractTableName($matches[1]);
            $alterBody = ltrim($matches[2]);

            /* 先检查是否是整表重命名：ALTER TABLE t RENAME TO new_t */
            if(preg_match('/^\s*rename\s+to\s+((?:`[^`]*`|\S)+)\s*$/i', $alterBody, $m)) return [['mode' => 'update', 'action' => 'renameTable', 'old' => $tableName, 'new' => trim($m[1], '`')]];

            $results = [];
            $clauses = $this->splitAlterClauses($alterBody);
            foreach($clauses as $clause)
            {
                $clause = trim($clause);
                if ($clause === '') continue;

                /* 提取关键词（忽略大小写） */
                $upperClause = preg_replace('/\s+/', ' ', strtoupper($clause));
                $words       = explode(' ', $upperClause);

                if(empty($words)) continue;

                $first = $words[0];

                /* --- ADD [COLUMN/INDEX/KEY] --- */
                if($first === 'ADD')
                {
                    /* 先尝试匹配 ADD INDEX / ADD KEY / ADD UNIQUE，再尝试匹配 ADD COLUMN 或 ADD field_name（即字段） */
                    if(preg_match('/^add\s+(unique\s+)?(index|key)\s+(`[^`]*`|\w+)/i', $clause, $m))
                    {
                        $results[] = ['mode' => 'create', 'action' => 'addIndex', 'table' => $tableName, 'index' => trim($m[3], '`')];
                    }
                    else
                    {
                        /* 跳过 "COLUMN" */
                        $pos = 1;
                        if(isset($words[1]) && $words[1] === 'COLUMN') $pos = 2;

                        /* 提取字段名（支持反引号，可能含空格） */
                        if(isset($words[$pos]) && preg_match('/^add\s+(column\s+)?(`[^`]*`|\w+)/i', $clause, $m)) $results[] = ['mode' => 'create', 'action' => 'addField', 'table' => $tableName, 'field' => trim($m[2], '`')];
                    }
                    continue;
                }

                /* --- DROP [COLUMN/INDEX/KEY] --- */
                if($first === 'DROP')
                {
                    if(isset($words[1]) && in_array($words[1], ['COLUMN', 'INDEX', 'KEY']))
                    {
                        /* 字段或索引 */
                        if(preg_match('/^drop\s+(column|index|key)\s+(`[^`]*`|\w+)/i', $clause, $m))
                        {
                            $type = strtolower($m[1]);

                            if($type === 'column')
                            {
                                $results[] = ['mode' => 'delete', 'action' => 'dropField', 'table' => $tableName, 'field' => trim($m[2], '`')];
                            }
                            else
                            {
                                $results[] = ['mode' => 'delete', 'action' => 'dropIndex', 'table' => $tableName, 'index' => trim($m[2], '`')];
                            }
                        }
                    }
                    else
                    {
                        /* 简写 DROP field_name */
                        if(preg_match('/^drop\s+(`[^`]*`|\w+)/i', $clause, $m)) $results[] = ['mode' => 'delete', 'action' => 'dropField', 'table' => $tableName, 'field' => trim($m[1], '`')];
                    }
                    continue;
                }

                /* --- MODIFY [COLUMN] --- */
                if($first === 'MODIFY')
                {
                    $pos = 1;
                    if(isset($words[1]) && $words[1] === 'COLUMN') $pos = 2;
                    if(isset($words[$pos]) && preg_match('/^modify\s+(column\s+)?(`[^`]*`|\w+)/i', $clause, $m)) $results[] = ['mode' => 'update', 'action' => 'modifyField', 'table' => $tableName, 'field' => trim($m[2], '`')];
                    continue;
                }

                /* --- CHANGE [COLUMN] --- */
                if($first === 'CHANGE')
                {
                    /* CHANGE [COLUMN] old_name new_name ... */
                    $pos = 1;
                    if(isset($words[1]) && $words[1] === 'COLUMN') $pos = 2;

                    /* 使用原始子句提取两个标识符 */
                    if(isset($words[$pos + 1]) && preg_match('/^change\s+(column\s+)?(`[^`]*`|\w+)\s+(`[^`]*`|\w+)/i', $clause, $m))
                    {
                        $old = trim($m[2], '`');
                        $new = trim($m[3], '`');
                        if($old == $new)
                        {
                            $results[] = ['mode' => 'update', 'action' => 'modifyField', 'table' => $tableName, 'field' => $old];
                        }
                        else
                        {
                            $results[] = ['mode' => 'update', 'action' => 'renameField', 'table' => $tableName, 'old' => $old, 'new' => $new];
                        }
                    }
                    continue;
                }

                /* --- RENAME COLUMN old_name TO new_name (MySQL 8.0+) --- */
                if($first === 'RENAME' && isset($words[1]) && $words[1] === 'COLUMN' && preg_match('/^rename\s+column\s+(`[^`]*`|\w+)\s+to\s+(`[^`]*`|\w+)/i', $clause, $m)) $results[] = ['mode' => 'update', 'action' => 'renameField', 'table' => $tableName, 'old' => trim($m[1], '`'), 'new' => trim($m[2], '`')];
            }
            return $results;
        }

        return [];
    }

    /**
     * 从可能带数据库前缀的标识符中提取表名（如从 `db`.`table` 或 db.table 中提取 table）
     *
     * @param  string $full 被反引号或不带反引号的标识符（如 "db.table" 或 "`my db`.`my-table`"）
     * @access public
     * @return string 表名（不含反引号）
     */
    protected function extractTableName(string $full): string
    {
        $full = trim($full); // 去除首尾空白

        /* 按未被反引号包围的点分割 */
        $parts      = [];
        $current    = '';
        $inBacktick = false;
        for($i = 0; $i < strlen($full); $i++)
        {
            $c = $full[$i];
            if($c === '`')
            {
                $inBacktick = !$inBacktick;
                continue; // 反引号本身不存入
            }
            if($c === '.' && !$inBacktick)
            {
                $parts[] = $current;
                $current = '';
            }
            else
            {
                $current .= $c;
            }
        }
        $parts[] = $current;

        $tableName = end($parts); // 最后一个 part 就是表名
        return $tableName !== false ? $tableName : $full;
    }

    /**
     * 安全拆分 ALTER TABLE 子句，跳过字符串和括号内的逗号
     *
     * @param  string $body
     * @access protected
     * @return string[]
     */
    protected function splitAlterClauses(string $body): array
    {
        $clauses    = [];
        $current    = '';
        $len        = strlen($body);
        $inSingle   = false;
        $inDouble   = false;
        $parenLevel = 0;

        for($i = 0; $i < $len; $i++)
        {
            $c = $body[$i];
            $next = ($i + 1 < $len) ? $body[$i + 1] : '';

            /* 处理转义（简化：跳过下一个字符） */
            if($c === '\\' && ($inSingle || $inDouble))
            {
                $current .= $c . $next;
                $i++;
                continue;
            }

            if($c === "'" && !$inDouble)
            {
                $inSingle = !$inSingle;
            }
            elseif($c === '"' && !$inSingle)
            {
                $inDouble = !$inDouble;
            }
            elseif($c === '(' && !$inSingle && !$inDouble)
            {
                $parenLevel++;
            }
            elseif($c === ')' && !$inSingle && !$inDouble)
            {
                $parenLevel--;
            }
            elseif($c === ',' && !$inSingle && !$inDouble && $parenLevel === 0)
            {
                $clauses[] = $current;
                $current = '';
                continue;
            }

            $current .= $c;
        }

        if($current !== '') $clauses[] = $current;

        return $clauses;
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
        if($systemMode == 'classic')
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
        $this->setting->setItem('system.common.closedFeatures', '');
        $this->setting->setItem('system.common.disabledFeatures', '');
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
     * @access protected
     * @return void
     */
    protected function displayCommand(string $command): void
    {
        $this->view->title   = $this->lang->upgrade->common;
        $this->view->result  = 'fail';
        $this->view->command = $command;

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
        $this->upgrade->recordExecutedChanges(true);
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
