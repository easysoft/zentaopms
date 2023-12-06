<?php
/**
 * The index view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('confirmDelete', $lang->api->confirmDelete);?>
<?php js::set('treeData', $libTree);?>
<?php js::set('apiID', $apiID);?>
<style>.panel-body{min-height: 180px}</style>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <?php $gobackLink = $this->session->structList ? $this->session->structList : inlink('index', "libID=$libID&moduleID=$moduleID");?>
    <?php echo html::a($gobackLink, "<i class='icon-back'></i> " . $lang->goback, '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
    if($libTree and common::hasPriv('api', 'struct'))        echo html::a($this->createLink('api', 'struct',        "libID=$libID"), "<i class='icon-treemap muted'> </i>" . $lang->api->struct, '', "class='btn btn-link'");
    if($libTree and common::hasPriv('api', 'releases'))      echo html::a($this->createLink('api', 'releases',      "libID=$libID", 'html', true), "<i class='icon-version muted'> </i>" . $lang->api->releases, '', "class='btn btn-link iframe' data-width='800px'");
    if($libTree and common::hasPriv('api', 'createRelease')) echo html::a($this->createLink('api', 'createRelease', "libID=$libID"), "<i class='icon-publish muted'> </i>" . $lang->api->createRelease, '', "class='btn btn-link iframe' data-width='800px'");
    if($objectType == 'api' and common::hasPriv('api', 'createLib')) echo html::a($this->createLink('api', 'createLib',     "type=" . ($objectType ? $objectType : 'nolink') . "&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->api->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
    if($objectType != 'api' and common::hasPriv('doc', 'createLib')) echo html::a($this->createLink('doc', 'createLib', "type=" . ($objectType ? $objectType : 'nolink') . "&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->api->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
    if($libTree and common::hasPriv('api', 'create')) echo html::a($this->createLink('api', 'create',        "libID=$libID&moduleID=$moduleID"), '<i class="icon icon-plus"></i> ' . $lang->api->createApi, '', 'class="btn btn-primary"');
  ?>
  </div>
</div>
<div id='mainContent' class="fade flex">
  <div id='sideBar' class="panel side side-col col overflow-auto" data-min-width="150">
    <?php include '../../doc/view/lefttree.html.php';?>
  </div>
  <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
  <div class="main-col flex-full overflow-visible flex-auto overflow-visible" data-min-width="500">
    <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" style="min-width: 400px" id="queryBox" data-module='api'></div>
    <?php include 'apilist.html.php';?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
