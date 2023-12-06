<?php
/**
 * The view view file of doc module of ZenTaoPMS.
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
<?php js::set('treeData', $libTree);?>
<?php js::set('docID', $docID);?>
<?php js::set('linkParams', "objectID=$objectID&%s");?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('exportMethod', $exportMethod);?>
<?php js::set('libID', $libID);?>
<?php if($app->tab == 'execution'):;?>
<style>.panel-body{min-height: 180px}</style>
<?php endif;?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <?php $gobackLink = $this->session->docList ? $this->session->docList : inlink('teamSpace', "objectID=0&libID=$libID");?>
    <?php echo html::a($gobackLink, "<i class='icon-back'></i> " . $lang->goback, '', "class='btn btn-link'");?>
  </div>
  <?php if(empty($object->deleted)):?>
  <div class="btn-toolbar pull-right">
    <?php
    if($canExport) echo html::a($this->createLink('doc', $exportMethod, "libID=$libID&moduleID=0&docID=$docID"), "<i class='icon-export muted'> </i>" . $lang->export, 'hiddenwin', "class='btn btn-link' id='docExport'");
    if(common::hasPriv('doc', 'createLib')) echo html::a($this->createLink('doc', 'createLib', "type=" . ($objectType ? $objectType : 'nolink') . "&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->api->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
    if(common::hasPriv('doc', 'create')) echo $this->doc->printCreateBtn($lib, $moduleID);
    ?>
  </div>
  <?php endif;?>
</div>
<div id='mainContent'class="fade flex">
  <?php if($libID):?>
    <?php if(empty($object->deleted)):?>
    <div id='sideBar' class="panel side side-col col overflow-auto h-full-adjust">
      <?php include 'lefttree.html.php';?>
    </div>
    <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
    <?php endif;?>
    <div class="main-col h-full-adjust" data-min-width="400">
      <?php if($docID):?>
        <?php include './content.html.php';?>
      <?php else:?>
      <div class="cell">
        <div class="detail empty text-center">
        <?php echo $lang->doc->noDoc;?>
        </div>
      </div>
      <?php endif;?>
    </div>
  <?php else:?>
    <div class="cell">
      <div class="detail empty text-center">
        <?php echo $lang->doc->noLib;?>
        <?php echo html::a($this->createLink('doc', 'createLib', "type={$objectType}&objectID=$object->id"), "<i class='icon icon-plus'></i> " . $lang->doc->createLib, '', "class='btn btn-info iframe'");?>
      </div>
    </div>
  <?php endif;?>
</div>
<?php js::set('type', 'doc');?>
<script>
$('#pageNav .btn-group.angle-btn').click(function()
{
    if($(this).hasClass('opened')) return;
    $(this).addClass('opened');

    scrollToSelected();
})
</script>
<?php include '../../common/view/footer.html.php';?>
