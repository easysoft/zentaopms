<?php
/**
 * The model file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: model.php 881 2010-06-22 06:50:32Z chencongzhi520 $
 * @link        http://www.zentao.net
 */
?>
<?php
class docModel extends model
{
    /**
     * Set menus
     *
     * @param  array  $libs
     * @param  int    $libID
     * @param  string $moduleID
     * @access public
     * @return void
     */
    public function setMenu($type = '', $libID = 0, $moduleID = 0, $productID = 0, $projectID = 0, $crumb = '')
    {
        $selectHtml  = "<div class='btn-group angle-btn'>";
        $selectHtml .= html::a(helper::createLink('doc', 'index'), $this->lang->doc->index, '', "class='btn'");
        $selectHtml .= '</div>';

        if($type)
        {
            $fastLib = in_array($type, array_keys($this->lang->doc->fastMenuList)) ? $this->lang->doc->fastMenuList[$type] : $this->lang->doc->fast;

            if($libID)
            {
                $lib = $this->getLibById($libID);
                if(!$this->checkPrivLib($lib))
                {
                    echo(js::alert($this->lang->doc->accessDenied));
                    $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
                    if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
                    die(js::locate('back'));
                }

                $type = $lib->type;
            }

            if(isset($this->lang->doc->libTypeList[$type]))
            {
                $mainLib     = $this->lang->doc->libTypeList[$type];
                $selectHtml .= "<div class='btn-group angle-btn'>";
                $selectHtml .= "<div class='btn-group'>";
                $selectHtml .= "<a data-toggle='dropdown' class='btn btn-limit' title=$mainLib>" . $mainLib . " <span class='caret'></span></a>";
                $selectHtml .= "<ul class='dropdown-menu'>";
                foreach($this->lang->doc->libTypeList as $libType => $libName)
                {
                    $selectHtml .= '<li>' . html::a(helper::createLink('doc', 'allLibs', "type=$libType"), "<i class='icon {$this->lang->doc->libIconList[$libType]}'></i> {$this->lang->doc->libTypeList[$libType]}") . '</li>';
                }
                $selectHtml .='</ul></div></div>';
            }

            $currentLib = 0;
            if(in_array($type, array_keys($this->lang->doc->libTypeList)))
            {
                if($type == 'product') $currentLib = $productID;
                if($type == 'project') $currentLib = $projectID;
                if($type != 'product' and $type != 'project') $currentLib = $libID;

                if($currentLib)
                {
                    $allLibGroups  = $this->getAllLibGroups($libID);
                    $currentGroups = $allLibGroups[$type];
                    /* Append closed project. */
                    if(!isset($currentGroups[$currentLib]) and $type == 'project')
                    {
                        $project = $this->dao->select('id,name,status')->from(TABLE_PROJECT)->where('id')->eq($currentLib)->fetch();
                        $currentGroups[$currentLib] = (array)$project;
                    }
                    $currentLibName = is_array($currentGroups[$currentLib]) ? $currentGroups[$currentLib]['name'] : $currentGroups[$currentLib];

                    $selectHtml .= "<div class='btn-group angle-btn'>";
                    $selectHtml .= "<div class='btn-group'>";
                    $selectHtml .= "<a data-toggle='dropdown' class='btn btn-limit' title=$currentLibName>" . $currentLibName . ' <span class="caret"></span></a>';
                    $selectHtml .='<ul class="dropdown-menu">';
                    if($type == 'product' or $type == 'project')
                    {
                        foreach($currentGroups as $groupID => $libGroups)
                        {
                            $link = helper::createLink('doc', 'objectLibs', "type=$type&objectID=$groupID");
                            $icon = $this->lang->doc->libIconList[$type];

                            $active = $currentLib == $groupID ? "class='active'" : '';
                            $selectHtml .= "<li $active>" . html::a($link, "<i class='icon {$icon}'></i> {$libGroups['name']}") . '</li>';
                        }
                    }
                    else
                    {
                        foreach($currentGroups as $groupID => $groupName)
                        {
                            $active = $currentLib == $groupID ? "class='active'" : '';
                            $selectHtml .= "<li $active>" . html::a(helper::createLink('doc', 'browse', "libID=$groupID"), "<i class='icon {$this->lang->doc->libIconList[$type]}'></i> {$groupName}") . '</li>';
                        }
                    }

                    $selectHtml .= '</ul></div></div>';
                }
            }

            $actions  = $this->setFastMenu($fastLib);
            $actions .= common::hasPriv('doc', 'createLib') ? html::a(helper::createLink('doc', 'createLib', "type={$type}&objectID={$currentLib}"), "<i class='icon icon-plus'></i> " . $this->lang->doc->createLib, '', "class='btn btn-secondary iframe'") : '';

            $this->lang->modulePageActions = $actions;
        }

        //$selectHtml .= $crumb ? $crumb : $this->getCrumbs($libID, $moduleID);
        $this->lang->modulePageNav     = $selectHtml;
    }

    /**
     * Get library by id.
     *
     * @param  int    $libID
     * @access public
     * @return object
     */
    public function getLibById($libID)
    {
        return $this->dao->findByID($libID)->from(TABLE_DOCLIB)->fetch();
    }

    /**
     * Get libraries.
     *
     * @param  string $type
     * @param  string $extra
     * @param  string $appendLibs
     * @access public
     * @return void
     */
    public function getLibs($type = '', $extra = '', $appendLibs = '')
    {
        if($type == 'product' or $type == 'project')
        {
            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $stmt  = $this->dao->select('t1.*')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin($table)->alias('t2')->on("t1.$type=t2.id")
                ->where("t1.$type")->ne(0)
                ->beginIF($type == 'project' and strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy("t2.order desc, t1.order, t1.id")
                ->query();
        }
        elseif($type == 'all')
        {
            /* If extra have unclosedProject then ignore unclosed project libs. */
            $unclosedProjects = array();
            if(strpos($extra, 'unclosedProject') !== false) $unclosedProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('deleted')->eq(0)->andWhere('status')->notin('done,closed')->fetchPairs('id', 'id');

            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->beginIF(strpos($extra, 'unclosedProject') !== false)
                ->andWhere('project', true)->eq('0')
                ->orWhere('project')->in($unclosedProjects)
                ->markRight(1)
                ->fi()
                ->orderBy('`order`,id desc')
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)
                ->where('deleted')->eq(0)
                ->beginIF($type)->andWhere('type')->eq($type)->fi()
                ->orderBy('`order`, id desc')->query();
        }

        if(strpos($extra, 'withObject') !== false)
        {
            $products = $this->loadModel('product')->getPairs();
            $projects = $this->loadModel('project')->getPairs();
        }

        $libPairs = array();
        while($lib = $stmt->fetch())
        {
            if($this->checkPrivLib($lib, $extra))
            {
                if(strpos($extra, 'withObject') !== false)
                {
                    if($lib->product != 0) $lib->name = zget($products, $lib->product, '') . '/' . $lib->name;
                    if($lib->project != 0) $lib->name = zget($projects, $lib->project, '') . '/' . $lib->name;
                }

                $libPairs[$lib->id] = '/' . $lib->name;
            }
        }

        if(!empty($appendLibs))
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($appendLibs)->orderBy('`order`, id desc')->query();
            while($lib = $stmt->fetch())
            {
                if(!isset($libPairs[$lib->id]) and $this->checkPrivLib($lib, $extra)) $libPairs[$lib->id] = '/' . $lib->name;
            }
        }

        return $libPairs;
    }

    /**
     * Get grant libs by doc.
     *
     * @access public
     * @return array
     */
    public function getPrivLibsByDoc()
    {
        static $libs;
        if($libs === null)
        {
            $libs = array();
            $stmt = $this->dao->select('lib,`groups`,users')->from(TABLE_DOC)->where('acl')->ne('open')->andWhere("(`groups` != '' or users != '')")->query();

            $account    = ",{$this->app->user->account},";
            $userGroups = $this->app->user->groups;
            while($lib = $stmt->fetch())
            {
                if(strpos(",$lib->users,", $account) !== false)
                {
                    $libs[$lib->lib] = $lib->lib;
                }
                else
                {
                    foreach($userGroups as $groupID)
                    {
                        if(strpos(",$lib->groups,", ",$groupID,") !== false)
                        {
                            $libs[$lib->lib] = $lib->lib;
                            break;
                        }
                    }
                }
            }
        }
        return $libs;
    }

    /**
     * Create a library.
     *
     * @access public
     * @return void
     */
    public function createLib()
    {
        $lib = fixer::input('post')
            ->setForce('product', $this->post->type == 'product' ? $this->post->product : 0)
            ->setForce('project', $this->post->type == 'project' ? $this->post->project : 0)
            ->join('groups', ',')
            ->join('users', ',')
            ->get();

        if($lib->acl == 'private') $lib->users = $this->app->user->account;
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->createlib->requiredFields, 'notempty')
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * Update a library.
     *
     * @param  int    $libID
     * @access public
     * @return void
     */
    public function updateLib($libID)
    {
        $libID  = (int)$libID;
        $oldLib = $this->getLibById($libID);
        $lib = fixer::input('post')
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->join('groups', ',')
            ->join('users', ',')
            ->get();
        if($lib->acl == 'private')
        {
            $libCreatedBy = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq($libID)->andWhere('action')->eq('created')->fetch('actor');
            $lib->users   = $libCreatedBy ? $libCreatedBy : $this->app->user->account;
        }
        $this->dao->update(TABLE_DOCLIB)->data($lib)->autoCheck()
            ->batchCheck($this->config->doc->editlib->requiredFields, 'notempty')
            ->where('id')->eq($libID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldLib, $lib);
    }

    /**
     * Get docs by browse type.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $libID
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDocsByBrowseType($libID, $browseType, $queryID, $moduleID, $sort, $pager)
    {
        $allLibs   = array_keys($this->getLibs('all'));
        $docIdList = $this->getPrivDocs($libID, $moduleID);

        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('objectType')->eq('doc')
            ->andWhere('objectID')->in($docIdList)
            ->fetchGroup('objectID');

        if($browseType == "all")
        {
            $docs = $this->getDocs($libID, 0, $sort, $pager);
        }
        elseif($browseType == "openedbyme")
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->beginIF($this->config->global->flow == 'onlyTask')->andWhere('product')->eq(0)->fi()
                ->beginIF($this->config->global->flow == 'onlyStory' || $this->config->global->flow == 'onlyTest')->andWhere('project')->eq(0)->fi()
                ->beginIF($libID)->andWhere('lib')->in($libID)->fi()
                ->andWhere('lib')->in($allLibs)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('addedBy')->eq($this->app->user->account)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == 'byediteddate')
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->beginIF($this->config->global->flow == 'onlyTask')->andWhere('product')->eq(0)->fi()
                ->beginIF($this->config->global->flow == 'onlyStory' || $this->config->global->flow == 'onlyTest')->andWhere('project')->eq(0)->fi()
                ->andWhere('id')->in($docIdList)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('lib')->in($allLibs)
                ->orderBy('editedDate_desc')
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == "collectedbyme")
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('deleted')->eq(0)
                ->beginIF($this->config->global->flow == 'onlyTask')->andWhere('product')->eq(0)->fi()
                ->beginIF($this->config->global->flow == 'onlyStory' || $this->config->global->flow == 'onlyTest')->andWhere('project')->eq(0)->fi()
                ->beginIF($libID)->andWhere('lib')->in($libID)->fi()
                ->andWhere('lib')->in($allLibs)
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('collector')->like("%,{$this->app->user->account},%")
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == "bymodule")
        {
            $modules = 0;
            if($moduleID)
            {
                $modules = array($moduleID => $moduleID);
                if(strpos($this->config->doc->custom->showLibs, 'children') !== false) $modules = $this->loadModel('tree')->getAllChildId($moduleID);
            }
            $docs = $this->getDocs($libID, $modules, $sort, $pager);
        }
        elseif($browseType == "bygrid")
        {
            $docs = $this->getDocs($libID, 0, $sort, $pager);
        }
        elseif($browseType == "bysearch")
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('docQuery', $query->sql);
                    $this->session->set('docForm', $query->form);
                }
                else
                {
                    $this->session->set('docQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->docQuery == false) $this->session->set('docQuery', ' 1 = 1');
            }

            $libCond    = strpos($this->session->docQuery, "`lib` = ") !== false;
            $allLibCond = strpos($this->session->docQuery, "`lib` = 'all'") !== false;

            $docQuery  = str_replace("`product` = 'all'", '1', $this->session->docQuery); // Search all product.
            $docQuery  = str_replace("`project` = 'all'", '1', $docQuery);                // Search all project.
            $docQuery  = str_replace("`lib` = 'all'", '1', $docQuery);                // Search all lib.

            $docs = $this->dao->select('*')->from(TABLE_DOC)->where($docQuery)
                ->beginIF(!$libCond and $libID != 0)->andWhere("lib")->eq($libID)->fi()
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchAll('id');
            foreach($docs as $docID => $doc)
            {
                if(!$this->checkPrivDoc($doc)) unset($docs[$docID]);
            }
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('id')->in(array_keys($docs))
                ->andWhere('lib')->in($allLibs)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($browseType == 'fastsearch')
        {
            if($this->session->searchDoc == false) return array();
            $docIdList = $this->getPrivDocs($libID, $moduleID);
            $docs = $this->dao->select('t1.*')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t2.doc = t1.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF($this->config->global->flow == 'onlyTask')->andWhere('product')->eq(0)->fi()
                ->beginIF($this->config->global->flow == 'onlyStory' || $this->config->global->flow == 'onlyTest')->andWhere('project')->eq(0)->fi()
                ->beginIF(!empty($docIdList))->andWhere('t1.id')->in($docIdList)->fi()
                ->andWhere('t1.title', true)->like("%{$this->session->searchDoc}%")
                ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
                ->orWhere('t2.content')->like("%{$this->session->searchDoc}%")->markRight(1)
                ->andWhere('t1.lib')->in($allLibs)
                ->orderBy($sort)
                ->page($pager)
                ->fetchAll('id');
            foreach($docs as $doc) $doc->title = str_replace($this->session->searchDoc, "<span style='color:red'>{$this->session->searchDoc}</span>", $doc->title);
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'doc', false);
        if(!$docs) return array();

        $docContents = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->in(array_keys($docs))->orderBy('version,doc')->fetchAll('doc');
        foreach($docs as $index => $doc)
        {
            $docs[$index]->fileSize = 0;
            if(isset($files[$index]))
            {
                $docContent = $docContents[$index];
                $fileSize   = 0;
                foreach($files[$index] as $file)
                {
                    if(strpos(",{$docContent->files},", ",{$file->id},") === false) continue;
                    $fileSize += $file->size;
                }

                if($fileSize < 1024)
                {
                    $fileSize .= 'B';
                }
                elseif($fileSize < 1024 * 1024)
                {
                    $fileSize = round($fileSize / 1024, 2) . 'KB';
                }
                elseif($fileSize < 1024 * 1024 * 1024)
                {
                    $fileSize = round($fileSize / 1024 / 1024, 2) . 'MB';
                }
                else
                {
                    $fileSize = round($fileSize / 1024 / 1024 /1024, 2) . 'G';
                }

                $docs[$index]->fileSize = $fileSize;
            }
        }

        return $docs;
    }

    /**
     * Get docs.
     *
     * @param  int|string   $libID
     * @param  int          $productID
     * @param  int          $projectID
     * @param  int          $module
     * @param  string       $orderBy
     * @param  object       $pager
     * @access public
     * @return void
     */
    public function getDocs($libID, $module, $orderBy, $pager = null)
    {
        $docIdList = $this->getPrivDocs($libID, $module);
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($docIdList)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get priv docs.
     *
     * @param  int    $libID
     * @param  int    $module
     * @access public
     * @return void
     */
    public function getPrivDocs($libID = 0, $module = 0)
    {
        $stmt = $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->beginIF($libID)->andWhere('lib')->in($libID)->fi()
            ->beginIF(strpos($this->config->doc->custom->showLibs, 'children') === false)->andWhere('module')->in($module)->fi()
            ->beginIF(!empty($module) and strpos($this->config->doc->custom->showLibs, 'children') !== false)->andWhere('module')->in($module)->fi()
            ->query();

        $docIdList = array();
        while($doc = $stmt->fetch())
        {
            if($this->checkPrivDoc($doc)) $docIdList[$doc->id] = $doc->id;
        }
        return $docIdList;
    }

    /**
     * Get doc info by id.
     *
     * @param  int    $docID
     * @param  bool   $setImgSize
     * @access public
     * @return void
     */
    public function getById($docID, $version = 0, $setImgSize = false)
    {
        $doc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!$doc) return false;
        if(!$this->checkPrivDoc($doc))
        {
            echo(js::alert($this->lang->doc->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
            die(js::locate('back'));
        }
        $version = $version ? $version : $doc->version;
        $docContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($version)->fetch();

        /* When file change then version add one. */
        $files = $this->loadModel('file')->getByObject('doc', $docID);
        $docFiles = array();
        foreach($files as $file)
        {
            $pathName = $this->file->getRealPathName($file->pathname);
            $file->webPath  = $this->file->webPath . $pathName;
            $file->realPath = $this->file->savePath . $pathName;
            if(strpos(",{$docContent->files},", ",{$file->id},") !== false) $docFiles[$file->id] = $file;
        }

        /* Check file change. */
        if($version == $doc->version and ((empty($docContent->files) and $docFiles) OR ($docContent->files and count(explode(',', trim($docContent->files, ','))) != count($docFiles))))
        {
            unset($docContent->id);
            $doc->version        += 1;
            $docContent->version  = $doc->version;
            $docContent->files    = join(',', array_keys($docFiles));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->dao->update(TABLE_DOC)->set('version')->eq($doc->version)->where('id')->eq($doc->id)->exec();
        }

        $doc->title       = isset($docContent->title)   ? $docContent->title  : '';
        $doc->digest      = isset($docContent->digest)  ? $docContent->digest  : '';
        $doc->content     = isset($docContent->content) ? $docContent->content : '';
        $doc->contentType = isset($docContent->type)    ? $docContent->type : '';

        if($doc->type != 'url' and $doc->contentType != 'markdown') $doc  = $this->loadModel('file')->replaceImgURL($doc, 'content');
        if($setImgSize) $doc->content = $this->file->setImgSize($doc->content);
        $doc->files = $docFiles;

        $doc->productName = '';
        $doc->projectName = '';
        $doc->moduleName  = '';
        if($doc->product) $doc->productName = $this->dao->findByID($doc->product)->from(TABLE_PRODUCT)->fetch('name');
        if($doc->project) $doc->projectName = $this->dao->findByID($doc->project)->from(TABLE_PROJECT)->fetch('name');
        if($doc->module)  $doc->moduleName  = $this->dao->findByID($doc->module)->from(TABLE_MODULE)->fetch('name');
        return $doc;
    }

    /**
     * Create a doc.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $now = helper::now();
        $doc = fixer::input('post')
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', $now)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('version', 1)
            ->setDefault('product,project,module', 0)
            ->stripTags($this->config->doc->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product,project,module,lib')
            ->join('groups', ',')
            ->join('users', ',')
            ->remove('files,labels,uid')
            ->get();

        /* Fix bug #2929. strip_tags($this->post->contentMarkdown, $this->config->allowedTags)*/
        $doc->contentMarkdown = $this->post->contentMarkdown;
        if($doc->acl == 'private') $doc->users = $this->app->user->account;
        $condition = "lib = '$doc->lib' AND module = $doc->module";

        $result = $this->loadModel('common')->removeDuplicate('doc', $doc, $condition);
        if($result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->create['id'], $this->post->uid);
        $doc->product = $lib->product;
        $doc->project = $lib->project;
        if($doc->type == 'url')
        {
            $doc->content     = $doc->url;
            $doc->contentType = 'html';
        }

        $docContent = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = $doc->contentType == 'html' ? $doc->content : $doc->contentMarkdown;
        $docContent->type    = $doc->contentType;
        $docContent->version = 1;
        if($doc->contentType == 'markdown') $docContent->content = str_replace('&gt;', '>', $docContent->content);
        unset($doc->contentMarkdown);
        unset($doc->contentType);
        unset($doc->url);

        $this->dao->insert(TABLE_DOC)->data($doc, 'content')->autoCheck()
            ->batchCheck($this->config->doc->create->requiredFields, 'notempty')
            ->exec();
        if(!dao::isError())
        {
            $docID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            $files = $this->file->saveUpload('doc', $docID);

            $docContent->doc   = $docID;
            $docContent->files = join(',', array_keys($files));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
            $this->loadModel('score')->create('doc', 'create', $docID);
            return array('status' => 'new', 'id' => $docID, 'files' => $files);
        }
        return false;
    }

    /**
     * Update a doc.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function update($docID)
    {
        $oldDoc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!empty($_POST['editedDate']) and $oldDoc->editedDate != $this->post->editedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now = helper::now();
        $doc = fixer::input('post')->setDefault('module', 0)
            ->stripTags($this->config->doc->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('users', '')
            ->setDefault('groups', '')
            ->add('editedBy',   $this->app->user->account)
            ->add('editedDate', $now)
            ->cleanInt('module')
            ->join('groups', ',')
            ->join('users', ',')
            ->remove('comment,files,labels,uid')
            ->get();
        if($doc->contentType == 'markdown') $doc->content = $this->post->content;
        if($doc->acl == 'private') $doc->users = $oldDoc->addedBy;

        $oldDocContent = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($docID)->andWhere('version')->eq($oldDoc->version)->fetch();
        if($oldDocContent)
        {
            $oldDoc->title       = $oldDocContent->title;
            $oldDoc->digest      = $oldDocContent->digest;
            $oldDoc->content     = $oldDocContent->content;
            $oldDoc->contentType = $oldDocContent->type;

            if($oldDocContent->type == 'markdown') $doc->content = str_replace('&gt;', '>', $doc->content);
        }

        $lib = $this->getLibByID($doc->lib);
        $doc = $this->loadModel('file')->processImgURL($doc, $this->config->doc->editor->edit['id'], $this->post->uid);
        $doc->product = $lib->product;
        $doc->project = $lib->project;
        if(isset($doc->type) and $doc->type == 'url') $doc->content = $doc->url;
        unset($doc->url);

        $files   = $this->file->saveUpload('doc', $docID);
        $changes = common::createChanges($oldDoc, $doc);
        $changed = false;
        if($files) $changed = true;
        foreach($changes as $change)
        {
            if($change['field'] == 'content' or $change['field'] == 'title') $changed = true;
        }

        if($changed)
        {
            $doc->version  = $oldDoc->version + 1;
            $docContent = new stdclass();
            $docContent->doc     = $docID;
            $docContent->title   = $doc->title;
            $docContent->content = isset($doc->content) ? $doc->content : '';
            $docContent->version = $doc->version;
            $docContent->type    = $oldDocContent->type;
            $docContent->files   = $oldDocContent->files;
            if(isset($doc->digest)) $docContent->digest  = $doc->digest;
            if($files) $docContent->files .= ',' . join(',', array_keys($files));
            $docContent->files   = trim($docContent->files, ',');
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        unset($doc->contentType);

        $this->dao->update(TABLE_DOC)->data($doc, 'content')
            ->autoCheck()
            ->batchCheck($this->config->doc->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$docID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $docID, 'doc');
            return array('changes' => $changes, 'files' => $files);
        }
    }

    /**
     * Build search form.
     *
     * @param  string $libID
     * @param  array  $libs
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($libID, $libs, $queryID, $actionURL, $type)
    {
        $this->config->doc->search['actionURL'] = $actionURL;
        $this->config->doc->search['queryID']   = $queryID;
        $this->config->doc->search['params']['product']['values'] = array(''=>'') + $this->loadModel('product')->getPairs('nocode') + array('all'=>$this->lang->doc->allProduct);
        $this->config->doc->search['params']['project']['values'] = array(''=>'') + $this->loadModel('project')->getPairs('nocode') + array('all'=>$this->lang->doc->allProject);
        $this->config->doc->search['params']['lib']['values']     = array(''=>'', $libID => ($libID ? $libs[$libID] : 0), 'all' => $this->lang->doclib->all);

        /* Get the modules. */
        $moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($libID, 'doc', $startModuleID = 0);
        $this->config->doc->search['params']['module']['values'] = $moduleOptionMenu;

        if($type == 'index' || $type == 'objectLibs' || $libID == 0)
        {
            unset($this->config->doc->search['fields']['module']);
            unset($this->config->doc->search['fields']['lib']);
        }

        $this->loadModel('search')->setSearchParams($this->config->doc->search);
    }

    /**
     * Get pairs of project modules.
     *
     * @access public
     * @return array
     */
    public function getProjectModulePairs()
    {
        return $this->dao->select('t1.id,t1.name')->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on('t1.root = t2.id')
            ->andWhere('t1.type')->eq('doc')
            ->andWhere('t2.project')->ne('0')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get doc menu.
     *
     * @param  int    $libID
     * @param  int    $parent
     * @access public
     * @return array
     */
    public function getDocMenu($libID, $parent, $orderBy = 'name_asc', $browseType = '')
    {
        if($libID == 0 and $browseType != 'collectedbyme') return array();

        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where(1)
            ->beginIF($browseType != "collectedbyme")->andWhere('root')->eq($libID)->fi()
            ->beginIF($browseType == "collectedbyme")->andWhere('collector')->like("%,{$this->app->user->account},%")->fi()
            ->andWhere('type')->eq('doc')
            ->andWhere('parent')->eq($parent)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('id');

        $docCounts= $this->dao->select("module, count(id) as docCount")->from(TABLE_DOC)
            ->where('module')->in(array_keys($modules))
            ->andWhere('deleted')->eq(0)
            ->groupBy('module')
            ->fetchPairs();

        foreach($modules as $moduleID => $module) $modules[$moduleID]->docCount = isset($docCounts[$moduleID]) ? $docCounts[$moduleID] : 0;

        return $modules;
    }

    /**
     * Extract css styles for tables created in kindeditor.
     *
     * Like this: <table class="ke-table1" style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
     *
     * @param  string    $content
     * @access public
     * @return void
     */
    public function extractKETableCSS($content)
    {
        $css = '';
        $rule = '/<table class="ke(.*)" .*/';
        if(preg_match_all($rule, $content, $results))
        {
            foreach($results[0] as $tableLine)
            {
                $attributes = explode(' ', str_replace('"', '', $tableLine));
                foreach($attributes as $attribute)
                {
                    if(strpos($attribute, '=') === false) continue;
                    list($attributeName, $attributeValue) = explode('=', $attribute);
                    $$attributeName = trim(str_replace('>', '', $attributeValue));
                }

                if(!isset($class)) continue;
                $className   = $class;
                $borderSize  = isset($border)      ? $border . 'px' : '1px';
                $borderColor = isset($bordercolor) ? $bordercolor : 'gray';
                $borderStyle = "{border:$borderSize $borderColor solid}\n";
                $css .= ".$className$borderStyle";
                $css .= ".$className td$borderStyle";
            }
        }
        return $css;
    }

    /**
     * Check priv for lib.
     *
     * @param  object $object
     * @param  string $extra
     * @access public
     * @return bool
     */
    public function checkPrivLib($object, $extra = '')
    {
        if($this->app->user->admin) return true;

        if($object->acl == 'open') return true;

        $account = ',' . $this->app->user->account . ',';
        if(isset($object->addedBy) and $object->addedBy == $this->app->user->account) return true;
        if(isset($object->users) and strpos(",{$object->users},", $account) !== false) return true;
        if($object->acl == 'custom')
        {
            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
        }

        if(strpos($extra, 'notdoc') === false)
        {
            static $extraDocLibs;
            if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();
            if(isset($extraDocLibs[$object->id])) return true;
        }

        if(!empty($object->product) or !empty($object->project))
        {
            $acls = $this->app->user->rights['acls'];
            if(!empty($object->product) and !empty($acls['products']) and !in_array($object->product, $acls['products'])) return false;
            if(!empty($object->project) and !empty($acls['projects']) and !in_array($object->project, $acls['projects'])) return false;
            if(!empty($object->project)) return $this->loadModel('project')->checkPriv($object->project);
            if(!empty($object->product)) return $this->loadModel('product')->checkPriv($object->product);
        }

        return false;
    }

    /**
     * Check priv for doc.
     *
     * @param  object    $object
     * @access public
     * @return bool
     */
    public function checkPrivDoc($object)
    {
        if($this->app->user->admin) return true;

        static $extraDocLibs;
        if($extraDocLibs === null) $extraDocLibs = $this->getPrivLibsByDoc();

        static $libs;
        if($libs === null) $libs = $this->getLibs('all', 'notdoc');
        if(isset($libs[$object->lib]) and isset($extraDocLibs[$object->lib])) unset($extraDocLibs[$object->lib]);
        if(!isset($libs[$object->lib]) and !isset($extraDocLibs[$object->lib])) return false;

        if($object->acl == 'open' and !isset($extraDocLibs[$object->lib])) return true;
        if($object->acl == 'public' and !isset($extraDocLibs[$object->lib])) return true;

        $account = ',' . $this->app->user->account . ',';
        if(isset($object->addedBy) and $object->addedBy == $this->app->user->account) return true;
        if(strpos(",$object->users,", $account) !== false) return true;
        if($object->acl == 'custom')
        {
            $userGroups = $this->app->user->groups;
            foreach($userGroups as $groupID)
            {
                if(strpos(",$object->groups,", ",$groupID,") !== false) return true;
            }
        }

        return false;
    }

    /**
     * Get all libs by type.
     *
     * @param  string $type
     * @param  int    $pager
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getAllLibsByType($type, $pager = null, $product = '')
    {
        if($product and $type == 'project') $projects = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($product)->fetchPairs('project', 'project');

        $libs = $this->getLibs($type == 'collector' ? 'all' : $type);
        $key  = ($type == 'product' or $type == 'project') ? $type : 'id';
        $stmt = $this->dao->select("DISTINCT $key")->from(TABLE_DOCLIB)->where('deleted')->eq(0);
        if($type == 'product' or $type == 'project')
        {
            $stmt = $stmt->andWhere($type)->ne(0);
        }
        elseif($type == 'collector')
        {
            $stmt = $stmt->andWhere('collector')->like("%,{$this->app->user->account},%");
        }
        else
        {
            $stmt = $stmt->andWhere('type')->eq($type);
        }
        if(isset($projects)) $stmt = $stmt->andWhere('project')->in($projects);

        $idList = $stmt->andWhere('id')->in(array_keys($libs))->orderBy("{$key}_desc")->fetchPairs($key, $key);

        if($type == 'product' or $type == 'project')
        {
            $table  = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $fields = $type == 'product' ? "createdBy, createdDate" : "openedBy AS createdBy, openedDate AS createdDate";
            $libs   = $this->dao->select("id, name, `order`, {$fields}")->from($table)
                ->where('id')->in($idList)
                ->beginIF($type == 'project' and strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('status')->notin('done,closed')->fi()
                ->orderBy('`order` desc, id desc')
                ->page($pager, 'id')
                ->fetchAll('id');
        }
        else
        {
            $libs = $this->dao->select('id, name, collector')->from(TABLE_DOCLIB)->where('id')->in($idList)->orderBy('`order`, id desc')->page($pager, 'id')->fetchAll('id');
        }

        return $libs;
    }

    /**
     * Get all lib groups.
     *
     * @param  string $appendLibs
     * @access public
     * @return array
     */
    public function getAllLibGroups($appendLibs = '')
    {
        $libs = $this->getLibs('all', '', $appendLibs);
        $stmt = $this->dao->select("id,type,product,project,name")->from(TABLE_DOCLIB)
            ->where('deleted')->eq(0)
            ->andWhere("id")->in(array_keys($libs))
            ->orderBy("product desc,project desc, `order` asc, id asc")
            ->query();

        $customLibs  = array();
        $productLibs = array();
        $projectLibs = array();

        $otherLibs = array();
        while($lib = $stmt->fetch())
        {
            if($lib->type == 'product')
            {
                $productLibs[$lib->product][$lib->id] = $lib->name;
            }
            elseif($lib->type == 'project')
            {
                $projectLibs[$lib->project][$lib->id] = $lib->name;
            }
            else
            {
                $otherLibs[$lib->type][$lib->id] = $lib->name;
            }
        }

        $productIdList = array_keys($productLibs);
        $products      = $this->dao->select('id,name,status')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->andWhere('deleted')->eq('0')->orderBy('`order`_desc')->fetchAll();
        if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')
        {
            $hasProject = array();
        }
        else
        {
            $hasProject = $this->dao->select('DISTINCT t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->in($productIdList)
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs('product', 'product');
        }

        $hasLibsPriv  = common::hasPriv('doc', 'allLibs');
        $hasFilesPriv = common::hasPriv('doc', 'showFiles');
        $productOrderLibs = array();
        foreach($products as $product)
        {
            $productID   = $product->id;
            $productName = $product->name;
            if(isset($productLibs[$productID]))
            {
                $productOrderLibs[$productID]['id']     = $productID;
                $productOrderLibs[$productID]['name']   = $productName;
                $productOrderLibs[$productID]['status'] = $product->status;
                foreach($productLibs[$productID] as $libID => $libName) $productOrderLibs[$productID]['libs'][$libID] = $libName;
                if(isset($hasProject[$productID]) and $hasLibsPriv) $productOrderLibs[$productID]['libs']['project'] = $this->lang->doclib->project;
                if($hasFilesPriv) $productOrderLibs[$productID]['libs']['files'] = $this->lang->doclib->files;
            }
        }

        $projects = $this->dao->select('id,name,status')->from(TABLE_PROJECT)
            ->where('id')->in(array_keys($projectLibs))
            ->andWhere('deleted')->eq('0')
            ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('status')->notin('done,closed')->fi()
            ->orderBy('`order`_desc')
            ->fetchAll();
        $projectOrderLibs = array();
        foreach($projects as $project)
        {
            $projectID   = $project->id;
            $projectName = $project->name;
            if(isset($projectLibs[$projectID]))
            {
                $projectOrderLibs[$projectID]['id']     = $projectID;
                $projectOrderLibs[$projectID]['name']   = $projectName;
                $projectOrderLibs[$projectID]['status'] = $project->status;
                foreach($projectLibs[$projectID] as $libID => $libName) $projectOrderLibs[$projectID]['libs'][$libID] = $libName;
                if($hasFilesPriv) $projectOrderLibs[$projectID]['libs']['files'] = $this->lang->doclib->files;
            }
        }

        return array('product' => $productOrderLibs, 'project' => $projectOrderLibs) + $otherLibs;
    }

    /**
     * Get limit libs.
     *
     * @param  string $type
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getLimitLibs($type, $limit = 0)
    {
        if($type == 'project' and ($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')) return array();
        if($type == 'product' and $this->config->global->flow == 'onlyTask')  return array();

        if($type == 'product' or $type == 'project')
        {
            $nonzeroLibs = array();
            if(strpos($this->config->doc->custom->showLibs, 'zero') === false)
            {
                $nonzeroLibs = $this->dao->select('lib,count(*) as count')->from(TABLE_DOC)->where('deleted')->eq('0')->groupBy('lib')->having('count')->ne(0)->fetchPairs('lib', 'lib');
            }

            $table = $type == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
            $stmt  = $this->dao->select('t1.*')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin($table)->alias('t2')->on("t1.$type=t2.id")
                ->where('t1.deleted')->eq(0)->andWhere("t1.$type")->ne(0)
                ->beginIF($type == 'project' and strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
                ->beginIF(strpos($this->config->doc->custom->showLibs, 'zero') === false)->andWhere('t1.id')->in($nonzeroLibs)->fi()
                ->orderBy("t2.`order` desc, t1.`order` asc, id desc")
                ->query();
        }
        else
        {
            $stmt = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('type')->eq($type)->orderBy('`order`, id desc')->query();
        }

        $i    = 1;
        $libs = array();
        while($docLib = $stmt->fetch())
        {
            if($limit && $i > $limit) break;
            $key = ($type == 'product' or $type == 'project') ? $type : 'id';
            if($this->checkPrivLib($docLib) and !isset($libs[$docLib->$key]))
            {
                $libs[$docLib->$key] = $docLib->name;
                $i++;
            }
        }

        if($type == 'product' or $type == 'project') $libs = $this->dao->select('*')->from($table)->where('id')->in(array_keys($libs))->orderBy('`order` desc')->fetchAll('id');

        return $libs;
    }

    /**
     * Get project or product libs groups.
     *
     * @param  string $type
     * @param  array  $idList
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getSubLibGroups($type, $idList)
    {
        if($type != 'product' and $type != 'project') return false;
        $libGroups = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->in($idList)->orderBy('`order`, id')->fetchGroup($type, 'id');
        if($type == 'product')
        {
            if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')
            {
                $hasProject = array();
            }
            else
            {
                $hasProject = $this->dao->select('DISTINCT t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.product')->in($idList)
                    ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetchPairs('product', 'product');
            }
        }
        $buildGroups = array();
        foreach($libGroups as $objectID => $libs)
        {
            foreach($libs as $lib)
            {
                if($this->checkPrivLib($lib)) $buildGroups[$objectID][$lib->id] = $lib->name;
            }
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs')) $buildGroups[$objectID]['project'] = $this->lang->doclib->project;
            if(common::hasPriv('doc', 'showFiles')) $buildGroups[$objectID]['files'] = $this->lang->doclib->files;
        }

        return $buildGroups;
    }

    /**
     * Get libs by object.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getLibsByObject($type, $objectID, $mode = '')
    {
        if($type != 'product' and $type != 'project') return false;
        $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere($type)->eq($objectID)->orderBy('`order`, id')->fetchAll('id');
        if($type == 'product')
        {
            if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest')
            {
                $hasProject = array();
            }
            else
            {
                $hasProject  = $this->dao->select('DISTINCT t1.product, count(project) as projectCount')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.product')->eq($objectID)
                    ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t2.status')->notin('done,closed')->fi()
                    ->andWhere('t2.deleted')->eq(0)
                    ->groupBy('product')
                    ->fetchPairs('product', 'projectCount');
            }
        }

        $libs = array();
        foreach($objectLibs as $lib)
        {
            if($this->checkPrivLib($lib)) $libs[$lib->id] = $lib;
        }

        $itemCounts = $this->statLibCounts(array_keys($libs));
        foreach($libs as $libID => $lib) $libs[$libID]->allCount = $itemCounts[$libID];

        if(strpos($mode, 'onlylib') === false)
        {
            if($type == 'product' and isset($hasProject[$objectID]) and common::hasPriv('doc', 'allLibs'))
            {
                $libs['project'] = new stdclass();
                $libs['project']->name = $this->lang->doclib->project;
                $libs['project']->allCount = $hasProject[$objectID];
            }
            if(common::hasPriv('doc', 'showFiles'))
            {
                $libs['files'] = new stdclass();
                $libs['files']->name = $this->lang->doclib->files;
                $libs['files']->allCount = count($this->getLibFiles($type, $objectID, 'id_desc'));
            }
        }

        return $libs;
    }

    /**
     * Stat module and document counts of lib.
     *
     * @param  array    $idList
     * @access public
     * @return array
     */
    public function statLibCounts($idList)
    {
        $moduleCounts = $this->dao->select("`root`, count(id) as moduleCount")->from(TABLE_MODULE)
            ->where('type')->eq('doc')
            ->andWhere('root')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('root')
            ->fetchPairs();

        $docs = $this->dao->select("`id`,`addedBy`,`lib`,`acl`,`users`,`groups`")->from(TABLE_DOC)
            ->where('lib')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('module')->eq(0)
            ->fetchAll();

        $docCounts = array();
        foreach($docs as $doc)
        {
            if(!$this->checkPrivDoc($doc)) continue;
            if(!isset($docCounts[$doc->lib])) $docCounts[$doc->lib] = 0;
            $docCounts[$doc->lib] ++;
        }

        $itemCounts = array();
        foreach($idList as $libID)
        {
            $docCount    = isset($docCounts[$libID]) ? $docCounts[$libID] : 0;
            $moduleCount = isset($moduleCounts[$libID]) ? $moduleCounts[$libID] : 0;
            $itemCounts[$libID] = $docCount + $moduleCount;
        }

        return $itemCounts;
    }

    /**
     * Get lib files.
     *
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getLibFiles($type, $objectID, $orderBy, $pager = null)
    {
        if($type != 'project' and $type != 'product') return true;
        $this->loadModel('file');
        $docs = $this->dao->select('*')->from(TABLE_DOC)->where($type)->eq($objectID)->fetchAll('id');
        foreach($docs as $id => $doc)
        {
            if(!$this->checkPrivDoc($doc)) unset($docs[$id]);
        }

        $idList    = array_keys($docs);
        $docIdList = $this->dao->select('id')->from(TABLE_DOC)->where($type)->eq($objectID)->andWhere('id')->in($idList)->get();
        $searchTitle = $this->get->title;
        if($type == 'product')
        {
            $storyIdList      = $this->dao->select('id')->from(TABLE_STORY)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->andWhere('type')->eq('story')->get();
            $bugIdList        = $this->dao->select('id')->from(TABLE_BUG)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->get();
            $releaseIdList    = $this->dao->select('id')->from(TABLE_RELEASE)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->get();
            $planIdList       = $this->dao->select('id')->from(TABLE_PRODUCTPLAN)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->get();
            $testReportIdList = $this->dao->select('id')->from(TABLE_TESTREPORT)->where('product')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('product')->in($this->app->user->view->products)->get();
            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'product' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'story' and objectID in ($storyIdList))")
                ->orWhere("(objectType = 'bug' and objectID in ($bugIdList))")
                ->orWhere("(objectType = 'release' and objectID in ($releaseIdList))")
                ->orWhere("(objectType = 'productplan' and objectID in ($planIdList))")
                ->orWhere("(objectType = 'testreport' and objectID in ($testReportIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        elseif($type == 'project')
        {
            $taskIdList  = $this->dao->select('id')->from(TABLE_TASK)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('project')->in($this->app->user->view->projects)->get();
            $buildIdList = $this->dao->select('id')->from(TABLE_BUILD)->where('project')->eq($objectID)->andWhere('deleted')->eq('0')->andWhere('project')->in($this->app->user->view->projects)->get();
            $files = $this->dao->select('*')->from(TABLE_FILE)->alias('t1')
                ->where('size')->gt('0')
                ->andWhere("(objectType = 'project' and objectID = $objectID)", true)
                ->orWhere("(objectType = 'doc' and objectID in ($docIdList))")
                ->orWhere("(objectType = 'task' and objectID in ($taskIdList))")
                ->orWhere("(objectType = 'build' and objectID in ($buildIdList))")
                ->markRight(1)
                ->beginIF($searchTitle)->andWhere('title')->like("%{$searchTitle}%")->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        foreach($files as $fileID => $file)
        {
            $pathName = $this->file->getRealPathName($file->pathname);
            $file->realPath = $this->file->savePath . $pathName;
            $file->webPath  = $this->file->webPath . $pathName;
        }

        return $files;
    }

    /**
     * Get doc tree.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getDocTree($libID)
    {
        $fullTrees = $this->loadModel('tree')->getTreeStructure($libID, 'doc');
        array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'doc', 'actions' => false, 'root' => $libID));
        foreach($fullTrees as $i => $tree)
        {
            $tree = (object)$tree;
            $fullTrees[$i] = $this->fillDocsInTree($tree, $libID);
        }
        if(empty($fullTrees[0]->children)) array_shift($fullTrees);
        return $fullTrees;
    }

    /**
     * Fill docs in tree.
     *
     * @param  object $node
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function fillDocsInTree($node, $libID)
    {
        $node = (object)$node;
        static $docGroups;
        if(empty($docGroups))
        {
            $docs = $this->dao->select('*')->from(TABLE_DOC)
                ->where('lib')->eq((int)$libID)
                ->andWhere('deleted')->eq(0)
                ->fetchAll();
            $docGroups = array();
            foreach($docs as $doc)
            {
                if($this->checkPrivDoc($doc)) $docGroups[$doc->module][$doc->id] = $doc;
            }
        }

        if(!empty($node->children)) foreach($node->children as $i => $child) $node->children[$i] = $this->fillDocsInTree($child, $libID);
        if(!isset($node->id))$node->id = 0;

        $node->type = 'module';
        $docs = isset($docGroups[$node->id]) ? $docGroups[$node->id] : array();
        $menu = !empty($node->children) ? $node->children : array();
        if(!empty($docs))
        {
            $docItems = array();
            foreach($docs as $doc)
            {
                $docItem = new stdclass();
                $docItem->type         = 'doc';
                $docItem->id           = $doc->id;
                $docItem->title        = $doc->title;
                $docItem->url          = helper::createLink('doc', 'view', "doc=$doc->id");

                $buttons  = '';
                $buttons .= common::buildIconButton('doc', 'edit',    "docID=$doc->id", '', 'list');
                if(common::hasPriv('doc', 'delete'))$buttons .= html::a(helper::createLink('doc', 'delete', "docID=$doc->id"), "<i class='icon icon-remove'></i>", 'hiddenwin', "class='btn-icon' title='{$this->lang->doc->delete}'");
                $docItem->buttons = $buttons;
                $docItem->actions = false;
                $docItems[]       = $docItem;
            }

            /* Reorder children. The doc is top of menu. */
            if($menu) $docItems = array_merge($docItems, $menu);

            $node->children = $docItems;
        }

        $node->docsCount = isset($node->children) ? count($node->children) : 0;
        $node->actions   = false;
        return $node;
    }

    /**
     * Get product crumb.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function getProductCrumb($productID, $projectID = 0)
    {
        if(empty($productID)) return '';
        if($projectID)
        {
            $projectProduct = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->andWhere('project')->eq($projectID)->fetch();
            if(empty($projectProduct))
            {
                setcookie('product', 0, $this->config->cookieLife, $this->config->webRoot, '', false, true);
                return html::a(helper::createLink('doc', 'allLibs', "type=project"), $this->lang->projectCommon) . $this->lang->doc->separator;
            }
        }
        $object = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
        if(empty($object)) return '';

        $crumb  = '';
        $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=product"), $this->lang->productCommon) . $this->lang->doc->separator;
        $crumb .= html::a(helper::createLink('doc', 'objectLibs', "type=product&objectID=$productID"), $object->name) . $this->lang->doc->separator;
        $crumb .= html::a(helper::createLink('doc', 'allLibs', "type=project&product=$productID"), $this->lang->doclib->project);
        if($projectID) $crumb .= $this->lang->doc->separator;
        return $crumb;
    }

    /**
     * Set lib users.
     *
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function setLibUsers($type, $objectID)
    {
        if($type != 'project' and $type != 'product') return array();
        if($type == 'product')
        {
            $teams = $this->dao->select('t1.account')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.root=t2.project')
                ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.root=t3.id')
                ->where('t2.product')->eq($objectID)
                ->beginIF(strpos($this->config->doc->custom->showLibs, 'unclosed') !== false)->andWhere('t3.status')->notin('done,closed')->fi()
                ->andWhere('t1.type')->eq('project')
                ->andWhere('t3.deleted')->eq('0')
                ->fetchPairs('account', 'account');
        }
        elseif($type == 'project')
        {
            $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('root')->eq($objectID)->andWhere('type')->eq('project')->fetchPairs('account', 'account');
        }

        return $teams;
    }

    /**
     * Get statistic information.
     *
     * @access public
     * @return object
     */
    public function getStatisticInfo()
    {
        $docIdList = $this->getPrivDocs();

        $today  = date('Y-m-d');
        $lately = date('Y-m-d', strtotime('-3 day'));
        $statisticInfo = $this->dao->select("count(id) as totalDocs, count(editedDate like '{$today}%' or null) as todayEditedDocs,
            count(editedDate > '{$lately}' or null) as lastEditedDocs, count(addedDate > '{$lately}' or null) as lastAddedDocs, 
            count(collector like '%,{$this->app->user->account},%' or null) as myCollection, count(addedBy = '{$this->app->user->account}' or null) as myDocs")->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($docIdList)
            ->fetch();

        $statisticInfo->pastEditedDocs       = $statisticInfo->totalDocs - $statisticInfo->todayEditedDocs;
        $statisticInfo->lastEditedProgress   = $statisticInfo->totalDocs ? round($statisticInfo->lastEditedDocs / $statisticInfo->totalDocs, 2) * 100 : 0;
        $statisticInfo->lastAddedProgress    = $statisticInfo->totalDocs ? round($statisticInfo->lastAddedDocs / $statisticInfo->totalDocs, 2) * 100 : 0;
        $statisticInfo->myCollectionProgress = $statisticInfo->totalDocs ? round($statisticInfo->myCollection / $statisticInfo->totalDocs, 2) * 100 : 0;
        $statisticInfo->myDocsProgress       = $statisticInfo->totalDocs ? round($statisticInfo->myDocs / $statisticInfo->totalDocs, 2) * 100 : 0;

        return $statisticInfo;
    }

    /**
     * Print doc child module.
     *
     * @access public
     */
    public function printChildModule($module, $libID, $methodName, $browseType, $moduleID)
    {
        if(isset($module->children))
        {
            foreach($module->children as $childModule)
            {
                $active = '';
                if($methodName == 'browse' && $browseType == 'bymodule' && $moduleID == $childModule->id) $active = "class='active'";
                echo '<ul>';
                echo "<li $active>";
                echo html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$childModule->id}"), "<i class='icon icon-folder-outline'></i> " . $childModule->name, '', "class='text-ellipsis' title='{$childModule->name}'");
                if(isset($childModule->children)) $this->printChildModule($childModule, $libID, $methodName, $browseType, $moduleID);
                echo '</li>';
                echo '</ul>';
            }
        }
    }

    /**
     * Build doc bread title.
     *
     * @access public
     * @return string
     */
    public function buildCrumbTitle($libID = 0, $param = 0, $title = '')
    {
        $path = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq($param)->fetch('path');

        $parantMoudles = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('id')->in($path)
            ->andWhere('deleted')->eq(0)
            ->orderBy('`grade`')
            ->fetchAll('id');

        foreach($parantMoudles as $parentID => $moduleName)
        {
            $title .= html::a(helper::createLink('doc', 'browse', "libID=$libID&browseType=byModule&param={$parentID}"), " <i class='icon icon-chevron-right'></i> " . $moduleName->name , '');
        }

        return $title;
    }

    /**
     * Build document module index page create document button.
     *
     * @access public
     * @return string
     */
    public function buildCreateButton4Doc()
    {
        $libs  = $this->getLibs('all', strpos($this->config->doc->custom->showLibs, 'unclosed') !== false ? 'unclosedProject' : '');
        $html  = "";
        if($libs)
        {
            $libID = key($libs);
            $html .= "<div class='dropdown' id='createDropdown'>";
            $html .= "<button class='btn btn-primary color-darkblue' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i>" . $this->lang->doc->create . " <span class='caret'></span></button>";
            $html .= "<ul class='dropdown-menu' style='left:0px'>";
            foreach($this->lang->doc->typeList as $typeKey => $typeName)
            {
                $class = strpos($this->config->doc->officeTypes, $typeKey) !== false ? 'iframe' : '';
                $html .= "<li>";
                $html .= html::a(helper::createLink('doc', 'create', "libID=$libID&moduleID=0&type=$typeKey"), $typeName, '', "class='$class'");
                $html .= "</li>";
            }
            $html .="</ul></div>";
        }
        return $html;
    }

    public function setFastMenu($fastLib)
    {
        $actions  = '';
        $actions .= '<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i>' . $this->lang->doc->search . '</a>';
        $actions .= "<a data-toggle='dropdown' class='btn btn-link' title=$fastLib>" . $fastLib . " <span class='caret'></span></a>";
        $actions .= "<ul class='dropdown-menu'>";
        foreach($this->lang->doc->fastMenuList as $key => $fastMenu)
        {
            $link     = helper::createLink('doc', 'browse', "libID=0&browseTyp={$key}");
            $actions .= '<li>' . html::a($link, "<i class='icon {$this->lang->doc->fastMenuIconList[$key]}'></i> {$fastMenu}") . '</li>';
        }
        $actions .='</ul>';

        return $actions;
    }
}
