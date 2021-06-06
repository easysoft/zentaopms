<?php
/**
 * The objectLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($app->openApp == 'execution'):;?>
<style>.panel-body{min-height: 180px}</style>
<?php endif;?>
<div class="fade main-row split-row" id="mainRow">
  <?php if($libID):?>
    <?php include './side.html.php';?>
    <div class="main-col" data-min-width="400">
      <?php if($docID):?>
        <?php include './content.html.php';?>
      <?php else:?>
      <div class="cell">
        <div class="detail empty text-center">
        <?php echo $type == 'book' ? $lang->doc->noArticle : $lang->doc->noDoc;?>
        </div>
      </div>
      <?php endif;?>
    </div>
  <?php else:?>
    <div class="cell">
      <div class="detail empty text-center">
        <?php echo $type == 'book' ? $lang->doc->noBook : $lang->doc->noLib;?>
        <?php if($type != 'book') echo html::a($this->createLink('doc', 'createLib', "type={$objectType}&objectID=$object->id"), "<i class='icon icon-plus'></i> " . $lang->doc->createLib, '', "class='btn btn-info iframe'");?>
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

    setTimeout(function()
    {
        scrollToSelected();
    }, 100);
})
</script>
<?php include '../../common/view/footer.html.php';?>
