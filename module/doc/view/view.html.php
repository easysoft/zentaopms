<?php
/**
 * The view view file of doc module of ZenTaoPMS.
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
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('treeData', $libTree);?>
<?php js::set('docID', $docID);?>
<?php js::set('linkParams', "objectID=$objectID&%s");?>
<?php js::set('docLang', $lang->doc);?>
<?php if($app->tab == 'execution'):;?>
<style>.panel-body{min-height: 180px}</style>
<?php endif;?>
<div id='mainContent'class="fade flex">
  <?php if($libID):?>
    <div id='sideBar' class="panel side side-col col overflow-auto h-full-adjust">
      <?php include 'lefttree.html.php';?>
    </div>
    <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
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
