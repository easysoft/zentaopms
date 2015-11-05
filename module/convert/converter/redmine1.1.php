<?php
/**
 * The model file of redmine convert of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     convert
 * @version     $Id $
 * @link        http://www.zentao.net
 */
class redmine11ConvertModel extends redmineConvertModel
{
    static $convertGroupCount          = 0;
    static $convertUserCount           = 0;
    static $convertProductCount        = 0;
    static $convertProjectCount        = 0;
    static $convertStoryCount          = 0;
    static $convertTaskCount           = 0;
    static $convertBugCount            = 0;
    static $convertProductPlanCount    = 0;
    static $convertTeamCount           = 0;
    static $convertReleaseCount        = 0;
    static $convertBuildCount          = 0;
    static $convertDocLibCount         = 0;
    static $convertDocCount            = 0;
    static $convertFileCount           = 0;
    public $issueType;

    public function __construct($issueType)
    {
        parent::__construct();
        $this->issueType = $issueType;
    }
    /**
     * Execute the converter.
     * 
     * @access public
     * @return array
     */
    public function execute($version)
    {
        $this->clear();
        $this->setTable();
        $this->convertGroup();
        $this->convertUser();
        $this->convertUserGroup();
        $this->convertProduct();
        $this->convertProject();
        $this->convertBuildAndRelease();
        $this->convertProductPlan();
        $this->convertProjectProduct();
        $this->convertTeam();
        $this->convertDocLib();
        $this->convertDoc();
        $this->convertNews();
        $this->convertIssue();
        $this->convertFile();
        $this->dao->dbh($this->dbh);
        $this->loadModel('tree')->fixModulePath();

        $result['groups']       = self::$convertGroupCount;
        $result['users']        = self::$convertUserCount ;
        $result['products']     = self::$convertProductCount ;
        $result['projects']     = self::$convertProjectCount ;
        $result['stories']      = self::$convertStoryCount;
        $result['tasks']        = self::$convertTaskCount ;
        $result['bugs']         = self::$convertBugCount ;
        $result['productPlans'] = self::$convertProductPlanCount;
        $result['teams']        = self::$convertTeamCount;
        $result['releases']     = self::$convertReleaseCount;
        $result['builds']       = self::$convertBuildCount;
        $result['docLibs']      = self::$convertDocLibCount ;
        $result['docs']         = self::$convertDocCount;
        $result['files']        = self::$convertFileCount;
        return $result;
    }                       
                               
    /**                        
     * Set table names.        
     *                         
     * @access public
     * @return void
     */
    public function setTable()
    {
        //$dbPrefix = $this->post->dbPrefix;
        $dbPrefix = '';
        define('REDMINE_TABLE_ATTACHMENTS',               $dbPrefix . 'attachments');
        define('REDMINE_TABLE_AUTH_SOURCES',              $dbPrefix . 'auth_sources');
        define('REDMINE_TABLE_BOARDS',                    $dbPrefix . 'boards');
        define('REDMINE_TABLE_CHANGES',                   $dbPrefix . 'changes');
        define('REDMINE_TABLE_CHANGESETS',                $dbPrefix . 'changesets');
        define('REDMINE_TABLE_CHANGESETS_ISSUES',         $dbPrefix . 'changesets_issues');
        define('REDMINE_TABLE_COMMENTS',                  $dbPrefix . 'comments');
        define('REDMINE_TABLE_CUSTOM_FIELDS',             $dbPrefix . 'custom_fields');
        define('REDMINE_TABLE_CUSTOM_FIELDS_PROJECTS',    $dbPrefix . 'custom_fields_projects');
        define('REDMINE_TABLE_CUSTOM_FIELDS_TRACKERS',    $dbPrefix . 'custom_fields_trackers');
        define('REDMINE_TABLE_CUSTOM_VALUES',             $dbPrefix . 'custom_values');
        define('REDMINE_TABLE_DOCUMENTS',                 $dbPrefix . 'documents');
        define('REDMINE_TABLE_ENABLED_MODULES',           $dbPrefix . 'enabled_modules');
        define('REDMINE_TABLE_ENUMERATIONS',              $dbPrefix . 'enumerations');
        define('REDMINE_TABLE_GROUPS_USERS',              $dbPrefix . 'groups_users');
        define('REDMINE_TABLE_ISSUES',                    $dbPrefix . 'issues');
        define('REDMINE_TABLE_ISSUE_CATEGORIES',          $dbPrefix . 'issue_categories');
        define('REDMINE_TABLE_ISSUE_RELATIONS',           $dbPrefix . 'issue_relations');
        define('REDMINE_TABLE_ISSUE_STATUSES',            $dbPrefix . 'issue_statuses');
        define('REDMINE_TABLE_JOURNALS',                  $dbPrefix . 'journals');
        define('REDMINE_TABLE_JOURNAL_DETAILS',           $dbPrefix . 'journal_details');
        define('REDMINE_TABLE_MEMBERS',                   $dbPrefix . 'members');
        define('REDMINE_TABLE_MEMBER_ROLES',              $dbPrefix . 'member_roles');
        define('REDMINE_TABLE_MESSAGES',                  $dbPrefix . 'messages');
        define('REDMINE_TABLE_NEWS',                      $dbPrefix . 'news');
        define('REDMINE_TABLE_OPEN_ID_AUTHENTICATION_ASSOCIATIONS',   $dbPrefix . 'open_id_authentication_associations');
        define('REDMINE_TABLE_OPEN_ID_AUTHENTICATION_NONCES',         $dbPrefix . 'open_id_authentication_nonces');
        define('REDMINE_TABLE_PROJECTS',                  $dbPrefix . 'projects');
        define('REDMINE_TABLE_PROJECTS_TRACKERS',         $dbPrefix . 'projects_trackers');
        define('REDMINE_TABLE_QUERIES',                   $dbPrefix . 'queries');
        define('REDMINE_TABLE_REPOSITORIES',              $dbPrefix . 'repositories');
        define('REDMINE_TABLE_ROLES',                     $dbPrefix . 'roles');
        define('REDMINE_TABLE_SCHEMA_MIGRATIONS',         $dbPrefix . 'schema_migrations');
        define('REDMINE_TABLE_SETTINGS',                  $dbPrefix . 'settings');
        define('REDMINE_TABLE_TIME_ENTRIES',              $dbPrefix . 'time_entries');
        define('REDMINE_TABLE_TOKENS',                    $dbPrefix . 'tokens');
        define('REDMINE_TABLE_TRACKERS',                  $dbPrefix . 'trackers');
        define('REDMINE_TABLE_USERS',                     $dbPrefix . 'users');
        define('REDMINE_TABLE_USER_PREFERENCES',          $dbPrefix . 'user_preferences');
        define('REDMINE_TABLE_VERSIONS',                  $dbPrefix . 'versions');
        define('REDMINE_TABLE_WATCHERS',                  $dbPrefix . 'watchers');
        define('REDMINE_TABLE_WIKIS',                     $dbPrefix . 'wikis');
        define('REDMINE_TABLE_WIKI_CONTENTS',             $dbPrefix . 'wiki_contents');
        define('REDMINE_TABLE_WIKI_CONTENT_VERSIONS',     $dbPrefix . 'wiki_content_versions');
        define('REDMINE_TABLE_WIKI_PAGES',                $dbPrefix . 'wiki_pages');
        define('REDMINE_TABLE_WIKI_REDIRECTS',            $dbPrefix . 'wiki_redirects');
        define('REDMINE_TABLE_WORKFLOWS',                 $dbPrefix . 'workflows');
    }

    /**
     * Convert groups.
     * 
     * @access public
     * @return void   
     */
    public function convertGroup()
    {
        /* Get group list */
        $groups = $this->dao->dbh($this->sourceDBH)
            ->select("id, lastName AS name")
            ->from(REDMINE_TABLE_USERS)
            ->where('type')->eq('Group')
            ->fetchAll('id');

        $zentaoGroupNames = $this->dao->dbh($this->dbh)->select('id, name')->from(TABLE_GROUP)->fetchPairs();
        $zentaoGroupIDs = $this->dao->dbh($this->dbh)->select('name, id')->from(TABLE_GROUP)->fetchPairs();

        /* Insert into zentao */
        $convertCount = 0;
        foreach($groups as $groupID =>$group)
        {
            unset($group->id);
            if(in_array("$group->name", $zentaoGroupNames))
            {
                self::$info['groups'][] = sprintf($this->lang->convert->errorGroupExists, $group->name);
                $this->map['groups'][$groupID] = $zentaoGroupIDs[$group->name];
            }
            else
            {
                $this->dao->dbh($this->dbh)->insert(TABLE_GROUP)
                    ->data($group)->exec();
                $this->map['groups'][$groupID] = $this->dao->lastInsertID();
                $convertCount ++;
            }
        }
        self::$convertGroupCount += $convertCount;
    }

    /**
     * Convert users.
     * 
     * @access public
     * @return void 
     */
    public function convertUser()
    {
        /* Get user list. */
        $users = $this->dao->dbh($this->sourceDBH)
            ->select("id, login AS account, firstname, lastname, mail as email")
            ->from(REDMINE_TABLE_USERS)
            ->where('type')->eq('User')
            ->fetchAll('id');

        $zentaoUserNames = $this->dao->dbh($this->dbh)->select('id, account')->from(TABLE_USER)->fetchPairs();
        $zentaoUserIDs = $this->dao->dbh($this->dbh)->select('account, id')->from(TABLE_USER)->fetchPairs();

        /* Insert into zentao. */
        $convertCount = 0;
        foreach($users as $id => $user)
        {
            if(in_array("$user->account", $zentaoUserNames))
            {
                self::$info['users'][] = sprintf($this->lang->convert->errorUserExists, $user->account);
                $this->map['users'][$id] = $zentaoUserIDs[$user->account];
            }
            else
            {
                $user->password = md5('123456');
                $user->realname = $user->lastname . $user->firstname;
                unset($user->id);
                unset($user->lastname);
                unset($user->firstname);
                $this->dao->dbh($this->dbh)->insert(TABLE_USER)->data($user)->exec();
                $this->map['users'][$id] = $this->dao->lastInsertID();
                $convertCount ++;
            }
        }
        self::$convertUserCount += $convertCount;
    }

    /**
     * convert relationship between user and group. 
     * 
     * @access public
     * @return void
     */
    public function convertUserGroup()
    {
        $this->map['groups'][0] = 0;
        /* Get relation between user and group list. */
        $userGroups = $this->dao->dbh($this->sourceDBH)
            ->select("t1.group_id, t2.login as account")
            ->from(REDMINE_TABLE_GROUPS_USERS)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.user_id = t2.id')
            ->fetchAll();

        $zentaoUserGroups = $this->dao->dbh($this->dbh)->select('*')->from(TABLE_USERGROUP)->fetchAll();

        /* Insert into zentao. */
        $userGroupExist = false;
        foreach($userGroups as $userGroup)
        {
            $userGroup->group = $this->map['groups'][$userGroup->group_id];
            unset($userGroup->group_id);
            foreach($zentaoUserGroups as $zentaoUserGroup)
            {
                if($userGroup->group == $zentaoUserGroup->group and $userGroup->account == $zentaoUserGroup->account)
                {
                    $userGroupExist = true;
                }
            }
            if($userGroupExist == false)
            {
                $this->dao->dbh($this->dbh)->insert(TABLE_USERGROUP)->data($userGroup)->exec();
            }
        }
    }

    /**
     * convert products.  
     * 
     * @access public
     * @return void 
     */
    public function convertProduct()
    {
        /* Get product list */
        $products = $this->dao->dbh($this->sourceDBH)
            ->select("id, name, description as `desc`, created_on as createdDate")
            ->from(REDMINE_TABLE_PROJECTS)
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($products as $productID => $product)
        {
            unset($product->id);
            $this->dao->dbh($this->dbh)->insert(TABLE_PRODUCT)->data($product)->exec();
            $this->map['products'][$productID] = $this->dao->lastInsertID();
        }
        self::$convertProductCount += count($products);
    }

    /**
     * Convert projects.
     * 
     * @access public
     * @return void 
     */
    public function convertProject()
    {
        /* Get project list */
        $projects = $this->dao->dbh($this->sourceDBH)
            ->select("id, name, project_id, description as `desc`, effective_date AS end")
            ->from(REDMINE_TABLE_VERSIONS)
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($projects as $projectID => $project)
        {
            $productID = $project->project_id;
            unset($project->id);
            unset($project->project_id);
            $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($project)->exec();
            $this->map['projects'][$productID][$projectID] = $this->dao->lastInsertID();
            $this->map['project'][$projectID]  = $this->map['projects'][$productID][$projectID];
        }

        /* Create a same name project with product */
        foreach($this->map['products'] as $productID)
        {
            $project = $this->dao->dbh($this->dbh)->select('name')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
            $this->dao->dbh($this->dbh)->insert(TABLE_PROJECT)->data($project)->exec();
            $this->map['projectOfProduct'][$productID] = $this->dao->lastinsertID();
        }
        $convertCount = count($projects) + count($this->map['projectOfProduct']);
        self::$convertProjectCount += $convertCount;
    }
    
    /**
     * convert builds and releases 
     * 
     * @access public
     * @return void 
     */
    public function convertBuildAndRelease()
    {
        /* Get build list */
        $buildAndReleases = $this->dao->dbh($this->sourceDBH)
            ->select("id, name, project_id, description as `desc`, effective_date AS date")
            ->from(REDMINE_TABLE_VERSIONS)
            ->fetchAll('id');

        /* Insert into zentao */
        $convertBuildsCount = 0;
        $convertReleasesCount = 0;
        $zentaoBuildNames = $this->dao->dbh($this->dbh)->select('id, name')->from(TABLE_BUILD)->fetchPairs();
        $zentaoBuildIDs = $this->dao->dbh($this->dbh)->select('name, id')->from(TABLE_BUILD)->fetchPairs();
        $zentaoReleaseNames = $this->dao->dbh($this->dbh)->select('id, name')->from(TABLE_RELEASE)->fetchPairs();
        $zentaoReleaseIDs = $this->dao->dbh($this->dbh)->select('name, id')->from(TABLE_RELEASE)->fetchPairs();
        foreach($buildAndReleases as $id => $buildAndRelease)
        {
            $buildAndRelease->project = $this->map['project'][$id];
            $buildAndRelease->product = $this->map['products'][$buildAndRelease->project_id];
            unset($buildAndRelease->id);
            unset($buildAndRelease->project_id);

            if(in_array($buildAndRelease->name, $zentaoBuildNames))
            {
                self::$info['builds'][] = sprintf($this->lang->convert->errorBuildExists, $buildAndRelease->name);
                $buildAndRelease->build = $zentaoBuildIDs[$buildAndRelease->name];
            }
            else
            {
                $this->dao->dbh($this->dbh)->insert(TABLE_BUILD)->data($buildAndRelease)->exec();
                $buildAndRelease->build = $this->dao->lastInsertID();
                $convertBuildsCount ++;
            }

            unset($buildAndRelease->project);
            if(in_array($buildAndRelease->name, $zentaoBuildNames))
            {
                self::$info['releases'][] = sprintf($this->lang->convert->errorReleaseExists, $buildAndRelease->name);
            }
            else
            {
                $this->dao->dbh($this->dbh)->insert(TABLE_RELEASE)->data($buildAndRelease)->exec();
                $convertReleasesCount ++;
            }
        }
        self::$convertBuildCount += $convertBuildsCount;
        self::$convertReleaseCount += $convertReleasesCount;
    }

    /**
     * convert productPlans 
     * 
     * @access public
     * @return void 
     */
    public function convertProductPlan()
    {
        /* Get productPlan list */
        $productPlans = $this->dao->dbh($this->sourceDBH)
            ->select("id, name as title, project_id, description as `desc`, effective_date as end, created_on AS begin")
            ->from(REDMINE_TABLE_VERSIONS)
            ->fetchAll('id');
        /* Insert into zentao */
        foreach($productPlans as $id => $productPlan)
        {
            $productPlan->product = $this->map['products'][$productPlan->project_id];
            unset($productPlan->id);
            unset($productPlan->project_id);
            $this->dao->dbh($this->dbh)->insert(TABLE_PRODUCTPLAN)->data($productPlan)->exec();
        }

        /* Create a same plan with product */
        foreach($this->map['products'] as $productID)
        {
            $productPlan = $this->dao->dbh($this->dbh)->select("name as title, createdDate as begin")->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
            $productPlan->product = $productID;
            $this->dao->dbh($this->dbh)->insert(TABLE_PRODUCTPLAN)->data($productPlan)->exec();
            $this->map['planOfProduct'][$productID] = $this->dao->lastinsertID();
        }
        $convertCount = count($this->map['products']) + count($productPlans);
        self::$convertProductPlanCount += $convertCount;
    } 

    /**
     * convert relationship between project and product. 
     * 
     * @access public
     * @return void
     */
    public function convertProjectProduct()
    {
        foreach($this->map['projects'] as $productID => $projects)
        {
            foreach($projects as $projectID => $project)
            {
                $this->dao->dbh($this->dbh)->insert(TABLE_PROJECTPRODUCT)
                    ->set('project')->eq($project)
                    ->set('product')->eq($this->map['products'][$productID])
                    ->exec();
            }
        }
    }

    /**
     * convert teams. 
     * 
     * @access public
     * @return void
     */
    public function convertTeam()
    {
        /* Get team list */
        $teams = $this->dao->dbh($this->sourceDBH)
            ->select("t2.login as account, t1.project_id, t1.created_on AS joinDate")
            ->from(REDMINE_TABLE_MEMBERS)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.user_id = t2.id')
            ->where('t2.type')->eq('User')
            ->fetchAll();

        /* Insert into zentao */
        foreach($teams as $team)
        {
            $productID = $team->project_id;
            unset($team->project_id);
            foreach($this->map['projects'][$productID] as $projectID)
            {
                $team->project = $projectID;
                $this->dao->dbh($this->dbh)->insert(TABLE_TEAM)->data($team)->exec();
            }
        }
        self::$convertTeamCount += count($teams);
    }

    /**
     * convert docLibs.  
     * 
     * @access public
     * @return void 
     */
    public function convertDocLib()
    {
        /* Get docLib list */
        $docLibs = $this->dao->dbh($this->sourceDBH)
            ->select("id, name")->from(REDMINE_TABLE_ENUMERATIONS)
            ->where('type')->eq('DocumentCategory')
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($docLibs as $docLibID => $docLib)
        {
            unset($docLib->id);
            $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($docLib)->exec();
            $this->map['docLibs'][$docLibID] = $this->dao->lastInsertID();
        }
        self::$convertDocLibCount += count($docLibs);
    }

    /**
     * convert docs.  
     * 
     * @access public
     * @return void 
     */
    public function convertDoc()
    {
        /* Get all docs */
        $docs = $this->dao->dbh($this->sourceDBH)
            ->select("t1.id, t1.project_id AS product, t2.id AS lib, t1.title, t1.description AS content, t1.created_on AS addedDate")
            ->from(REDMINE_TABLE_DOCUMENTS)->alias('t1')
            ->leftjoin(REDMINE_TABLE_ENUMERATIONS)->alias('t2')->on('t1.category_id = t2.id')
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($docs as $docID => $doc)
        {
            unset($doc->id);
            $doc->type = 'text';
            $doc->project = 0;
            $doc->product = $this->map['products'][$doc->product];
            $doc->lib = $this->map['docLibs'][$doc->lib];
            $this->dao->dbh($this->dbh)->insert(TABLE_DOC)->data($doc)->exec();
            $this->map['docs'][$docID] = $this->dao->lastInsertID();
        }
        self::$convertDocCount += count($docs);
    }

    /**
     * convert news. 
     * 
     * @access public
     * @return void 
     */
    public function convertNews()
    {
        /* Get news from redmine */
        $news = $this->dao->dbh($this->sourceDBH)
            ->select("t1.project_id as product, t1.title, t1.summary as digest, t1.description as content, t2.login as addedBy, t1.created_on as addedDate")
            ->from(REDMINE_TABLE_NEWS)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.author_id = t2.id')
            ->fetchAll();

        /* Create a news docLib  */
        $newLib->name = 'news';
        $this->dao->dbh($this->dbh)->insert(TABLE_DOCLIB)->data($newLib)->exec();
        $this->map['news'] = $this->dao->lastInsertID();
        self::$convertDocLibCount += 1;

        /* Insert into zentao */
        foreach($news as $new)
        {
            $new->product = $this->map['products'][$new->product];
            $new->project = 0;
            $new->lib     = $this->map['news'];
            $new->type    = 'text';

            $this->dao->dbh($this->dbh)->insert(TABLE_DOC)->data($new)->exec();
        }
        self::$convertDocCount += count($news);
    }

    /**
     * convert issue  
     * 
     * @param  array  $aimTypes //aimTypes['issueTypeID'] = aimtype  eg. aimTypes[1] = 'bug';
     * @param  array  $statusTypes //statusTypes['task']['statusTypeID'] = statusType  eg. statusTypes['task'][1] = 'wait';
     *                             //statusTypes['bug']['statusTypeID'] = statusType   eg. statusTypes['bug'][1]  = 'active';
     * @param  array  $priTypes //priTypes['task']['priTypeID'] = priType;   eg. priTypes['task'][1] = 1;              
     * @access public
     * @return void
     */
    public function convertIssue()
    {
        $aimTypes    = $this->issueType->aimTypes;
        $statusTypes = $this->issueType->statusTypes;
        $priTypes    = $this->issueType->priTypes;

        foreach($aimTypes as $issueType => $aimType)
        {
            if('story' == $aimType)
            {
                $this->convertStory($issueType, $statusTypes, $priTypes);
            }
            elseif('task' == $aimType)
            {
                $this->convertTask($issueType, $statusTypes, $priTypes);
            }
            else
            {
                $this->convertBug($issueType, $statusTypes, $priTypes);
            }
        }
    }

    /**
     * convert story 
     * 
     * @param  array    $issueType 
     * @param  array    $statusTypes 
     * @param  array    $priTypes 
     * @access public
     * @return void 
     */
    public function convertStory($issueType, $statusTypes, $priTypes)
    {
        /* Get story list*/
        $stories = $this->dao->dbh($this->sourceDBH)
            ->select("t1.id, t1.project_id as product, t1.subject as title, t1.description as spec, t1.status_id as status, t2.login as assignedTo, t1.priority_id as pri, t3.login as openedBy, t1.created_on as openedDate, t1.estimated_hours as estimate, t1.updated_on as lastEditedDate")
            ->from(REDMINE_TABLE_ISSUES)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.assigned_to_id = t2.id')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t3')->on('t1.author_id = t3.id')
            ->where('t1.tracker_id')->eq($issueType)
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($stories as $issueID => $story)
        {
            $storySpec->title = $story->title;
            $storySpec->spec  = $story->spec;
            unset($story->id);
            unset($story->spec);

            /* Insert story into table story */
            $story->product = $this->map['products'][$story->product];
            $story->module  = 0;
            $story->plan    = $this->map['planOfProduct'][$story->product];
            $story->fromBug = 0;
            $story->pri     = $priTypes['story'][$story->pri];
            $story->status  = $statusTypes['story'][$story->status];
            $story->toBug   = 0;
            $story->duplicateStory = 0;
            $this->dao->dbh($this->dbh)->insert(TABLE_STORY)->data($story)->exec();
            $this->map['issueID'][$issueID] = $this->dao->lastInsertID();
            $this->map['issueType'][$issueID] = 'story';

            /* Insert data into table storySpec */
            $storySpec->story = $this->map['issueID'][$issueID];
            $storySpec->version = 1;
            $this->dao->dbh($this->dbh)->insert(TABLE_STORYSPEC)->data($storySpec)->exec();
        }
        self::$convertStoryCount += count($stories);
    }

    /**
     * convert task 
     * 
     * @param  array    $issueType 
     * @param  array    $statusTypes 
     * @param  array    $priTypes 
     * @access public
     * @return void 
     */
    public function convertTask($issueType, $statusTypes, $priTypes)
    {
        /* Get task list */
        $tasks = $this->dao->dbh($this->sourceDBH)
            ->select("t1.id, t1.project_id as product, t1.fixed_version_id as project, t1.subject as name, t1.description as `desc`, t1.due_date as deadline, t1.status_id as status, t2.login as assignedTo, t1.priority_id as pri, t3.login as openedBy, t1.created_on as openedDate, t1.estimated_hours as estimate, t1.updated_on as lastEditedDate")
            ->from(REDMINE_TABLE_ISSUES)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.assigned_to_id = t2.id')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t3')->on('t1.author_id = t3.id')
            ->where('t1.tracker_id')->eq($issueType)
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($tasks as $issueID => $task)
        {
            $task->story        = 0;
            $task->storyVersion = 0;
            $task->type         = 'misc';
            $task->pri          = $priTypes['task'][$task->pri];
            $task->status       = $statusTypes['task'][$task->status];
            if($task->project == 0)
            {  
                $task->project = $this->map['projectOfProduct'][$task->product];
            }
            else
            {
                $task->project      = $this->map['project'][$task->project];
            }
            unset($task->id);
            unset($task->product);
            $this->dao->dbh($this->dbh)->insert(TABLE_TASK)->data($task)->exec();
            $this->map['issueID'][$issueID] = $this->dao->lastInsertID();
            $this->map['issueType'][$issueID] = 'task';
        }
        self::$convertTaskCount += count($tasks);
    }

    /**
     * convert bug 
     * 
     * @param  array    $issueType 
     * @param  array    $statusTypes 
     * @param  array    $priTypes 
     * @access public
     * @return void 
     */
    public function convertBug($issueType, $statusTypes, $priTypes)
    {
        /* Get bug list */
        $bugs = $this->dao->dbh($this->sourceDBH)
            ->select("t1.id, t1.project_id as product, t1.fixed_version_id project, t1.subject as title, t1.description as steps, t1.status_id as status, t2.login as assignedTo, t1.priority_id as pri, t3.login as openedBy, t1.created_on as openedDate, t1.updated_on as lastEditedDate")
            ->from(REDMINE_TABLE_ISSUES)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.assigned_to_id = t2.id')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t3')->on('t1.author_id = t3.id')
            ->where('t1.tracker_id')->eq($issueType)
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($bugs as $issueID => $bug)
        {
            $bug->product = $this->map['products'][$bug->product];
            $bug->module  = 0;
            $bug->story   = 0;
            $bug->storyVersion = 1;
            $bug->task         = 0;
            $bug->severity     = 3;
            $bug->type         = 'others';
            $bug->status       = $statusTypes['bug'][$bug->status];
            $bug->openedBuild  = 'trunk';
            $bug->duplicateBug = 0;
            $bug->case         = 0;
            $bug->caseVersion  = 1;
            $bug->result       = 0;
            if($bug->project == 0)
            {  
                $bug->project = $this->map['projectOfProduct'][$bug->product];
            }
            else
            {
                $bug->project = $this->map['project'][$bug->project];
            }
            unset($bug->id);
            $this->dao->dbh($this->dbh)->insert(TABLE_BUG)->data($bug)->exec(); 
            $this->map['issueID'][$issueID] = $this->dao->lastInsertID();
            $this->map['issueType'][$issueID] = 'bug';
        }
        self::$convertBugCount += count($bugs);
   }

    /**
     * Convert attachments.
     * 
     * @access public
     * @return void 
     */
    public function convertFile()
    {
        $this->setPath();

        /* Get file list */
        $files = $this->dao->dbh($this->sourceDBH)
            ->select('t1.id, t1.container_id as objectID, t1.container_type as objectType, t1.filename as title, t1.disk_filename as pathname, t1.filesize as size, t2.login as addedBy, t1.created_on as addedDate, description')
            ->from(REDMINE_TABLE_ATTACHMENTS)->alias('t1')
            ->leftJoin(REDMINE_TABLE_USERS)->alias('t2')->on('t1.author_id = t2.id')
            ->fetchAll('id');

        /* Insert into zentao */
        foreach($files as $fileID => $file)
        {
            if($file->description != '')
            {
                $file->title = $file->description;
                unset($file->description);
            }
            else
            {
                unset($file->description);
            }

            /* Transform objectType and objectID */
            if($file->objectType == 'Issue')
            {
                $file->objectType = $this->map['issueType'][$file->objectID]; 
                $file->objectID   = $this->map['issueID'][$file->objectID];
            }
            elseif($file->objectType == 'Document')
            {
                $file->objectType = 'doc' ;
                $file->objectID   = $this->map['docs'][$file->objectID];
            }
            elseif($file->objectType == 'WikiPage')
            {
                continue;
            }
            elseif($file->objectType == 'Version')
            {
                $doc->project = $this->map['project'][$file->objectID];
                $doc = $this->dao->dbh($this->dbh)->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($doc->project)->fetch();
                $doc->lib = 'project';
                $doc->module = 0;
                $doc->title  = $file->title;
                $doc->type   = 'file';
                $doc->addedBy   = $file->addedBy;
                $doc->addedDate = $file->addedDate;
                $this->dao->dbh($this->dbh)->insert(TABLE_DOC)->data($doc)->exec();
                self::$convertDocCount += 1;

                $file->objectType = 'doc';
                $file->objectID   = $this->dao->lastInsertID();
            }

            $pathname = pathinfo($file->pathname);
            $file->extension = $pathname["extension"];
            unset($file->id);

            /* Insert into database. */
            $this->dao->dbh($this->dbh)->insert(TABLE_FILE)->data($file)->exec();

            /* Copy file. */
            $soureFile = $this->filePath . $file->pathname;
            if(!file_exists($soureFile))
            {
                self::$info['files'][] = sprintf($this->lang->convert->errorFileNotExits, $soureFile);
                continue;
            }
            $targetFile = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . $file->pathname;
            $targetPath = dirname($targetFile);
            if(!is_dir($targetPath)) mkdir($targetPath, 0777, true);
            if(!copy($soureFile, $targetFile))
            {
                self::$info['files'][] = sprintf($this->lang->convert->errorCopyFailed, $targetFile);
            }
        }
        self::$convertFileCount += count($files);
    }

    /**
     * Clear the converted records.
     * 
     * @access public
     * @return void
     */
    public function clear()
    {
        foreach($this->session->state as $table => $maxID)
        {
            $this->dao->dbh($this->dbh)->delete()->from($table)->where('id')->gt($maxID)->exec();
        }
    }
}
