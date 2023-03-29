<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->api->confirmDelete);?>
<div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo 'api';?>></div>
<div class="fade main-row split-row" id="mainRow">
  <?php if($libID):?>
  <?php $sideWidth = common::checkNotCN() ? '270' : '238';?>
  <div class="side-col" style="width:<?php echo $sideWidth;?>px" data-min-width="<?php echo $sideWidth;?>">
    <div class="cell" style="min-height: 286px; overflow: visible;">
      <div id='title'>
        <li class='menu-title'>
          <div class="title"><?php echo $this->lang->api->module;?></div>
          <div class="dropdown vision-drop">
            <button data-toggle="dropdown" class="btn btn-link">v-1.43</button>
            <ul class="dropdown-menu">
              <li><a>1.43</a></li>
              <li><a>1.44</a></li>
            </ul>
          </div>
        </li>
        <?php
        $canTreeBrowse   = common::hasPriv('tree', 'browse');
        $canViewReleases = common::hasPriv('api', 'releases');
        $canEditLib      = common::hasPriv('api', 'editLib');
        $canDeleteLib    = common::hasPriv('api', 'deleteLib');
        $haveMoreButton  = ($canTreeBrowse or $canViewReleases or $canEditLib or $canDeleteLib);

        if(!$isRelease and $haveMoreButton)
        {
            echo "<div class='menu-actions'>";
            echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");
            echo "<ul class='dropdown-menu pull-right'>";
            if($canTreeBrowse) echo '<li>' . html::a($this->createLink('tree', 'browse', "rootID=$libID&view=api", '', true), '<i class="icon-cog-outline"></i> ' . $this->lang->api->manageType, '', "class='iframe' data-width='1200px'") . '</li>';
            if($canViewReleases) echo '<li>' . html::a($this->createLink('api', 'releases', "libID=$libID", '', true), '<i class="icon-version"></i> ' . $this->lang->api->managePublish, '', "class='iframe'") . '</li>';
            echo "<li class='divider'></li>";
            if($canEditLib) echo '<li>' . html::a($this->createLink('api', 'editLib', "rootID=$libID"), '<i class="icon-edit"></i> ' . $lang->api->editLib, '', "class='iframe'") . '</li>';
            if($canDeleteLib) echo '<li>' . html::a($this->createLink('api', 'deleteLib', "rootID=$libID"), '<i class="icon-trash"></i> ' . $lang->api->deleteLib, 'hiddenwin') . '</li>';
            echo '</ul></div>';
        }
        ?>
      </div>
      <?php if(!$moduleTree):?>
      <hr class="space">
      <?php if(!$isRelease):?>
      <div class="text-center text-muted tips"><?php echo $lang->api->noModule;?></div>
      <?php endif;?>
      <?php endif;?>
      <?php echo $moduleTree;?>
    </div>
  </div>
  <?php endif;?>
  <?php include 'apilist.html.php';?>
</div>
<?php include '../../common/view/footer.html.php';?>
