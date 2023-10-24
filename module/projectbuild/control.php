<?php
/**
 * The control file of projectBuild module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectBuild
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class projectBuild extends control
{
    /**
     * Browse builds of a project.
     *
     * @param  int    $projectID
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc')
    {
        echo $this->fetch('project', 'build', "projectID=$projectID&type=$type&param=$param&orderBy=$orderBy");
    }

    /**
     * Create a build for project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        echo $this->fetch('build', 'create', "executionID=0&productID=0&projectID=$projectID");
    }

    /**
     * Edit a build for project.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function edit($buildID)
    {
        echo $this->fetch('build', 'edit', "buildID=$buildID");
    }

    /**
     * View a build for project.
     *
     * @param  int    $buildID
     * @param  string $type
     * @param  string $link
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view($buildID, $type = 'story', $link = 'false', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('build', 'view', "buildID=$buildID&type=$type&link=$link&param=$param&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Delete a build for project.
     *
     * @param  int    $buildID
     * @param  string $confirm  yes|noe
     * @access public
     * @return void
     */
    public function delete($buildID, $confirm = 'no')
    {
        echo $this->fetch('build', 'delete', "buildID=$buildID&confirm=$confirm");
    }

    /**
     * Link stories.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($buildID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('build', 'linkStory', "buildID=$buildID&browseType=$browseType&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Unlink story
     *
     * @param  int    $storyID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkStory($buildID, $storyID)
    {
        echo $this->fetch('build', 'unlinkStory', "buildID=$buildID&storyID=$storyID");
    }

    /**
     * Batch unlink story.
     *
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function batchUnlinkStory($buildID)
    {
        echo $this->fetch('build', 'batchUnlinkStory', "buildID=$buildID");
    }

    /**
     * Link bugs.
     *
     * @param  int    $buildID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug($buildID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('build', 'linkBug', "buildID=$buildID&browseType=$browseType&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Unlink story
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($buildID, $bugID)
    {
        echo $this->fetch('build', 'unlinkBug', "buildID=$buildID&bugID=$bugID");
    }

    /**
     * Batch unlink story.
     *
     * @param  int $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkBug($buildID)
    {
        echo $this->fetch('build', 'batchUnlinkBug', "buildID=$buildID");
    }
}
