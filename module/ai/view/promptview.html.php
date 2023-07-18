<?php
/**
 * The ai prompt details view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a(helper::createLink('ai', 'prompts'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $prompt->id?></span>
      <span class="text" title='<?php echo $prompt->name;?>'><?php echo $prompt->name;?></span>
      <?php if($prompt->deleted) echo "<span class='label label-danger'>{$lang->ai->prompts->deleted}</span>";?>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a(helper::createLink('ai', 'createprompt'), "<i class='icon icon-plus'></i> {$lang->ai->prompts->create}", '', "class='btn btn-primary iframe'");?>
  </div>
</div>

<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::a(helper::createLink('ai', 'prompts'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn'");?>
        <div class='divider'></div>
        <?php if($prompt->status == 'draft') echo html::a(helper::createLink('ai', 'promptassignrole', "prompt=$prompt->id"), '<i class="icon icon-design icon-sm"></i> ' . $lang->ai->prompts->action->design, '', "class='btn'");?>
        <?php if($prompt->status == 'draft' && $this->ai->isExecutable($prompt)) echo html::a($this->ai->getTestingLocation($prompt), '<i class="icon icon-bug icon-sm"></i> ' . $lang->ai->prompts->action->test, '', "class='btn'");?>
        <?php if($prompt->status == 'draft' && $this->ai->isExecutable($prompt)) echo html::a(helper::createLink('ai', 'promptpublish', "id={$prompt->id}"), '<i class="icon icon-publish icon-sm"></i> ' . $lang->ai->prompts->action->publish, '', "class='btn'");?>
        <?php if($prompt->status == 'active') echo html::a(helper::createLink('ai', 'promptunpublish', "id={$prompt->id}"), '<i class="icon icon-ban icon-sm"></i> ' . $lang->ai->prompts->action->unpublish, '', "class='btn' id='unpublish-btn'");?>
        <div class='divider'></div>
        <?php echo html::a(helper::createLink('ai', 'promptedit', "prompt=$prompt->id"), '<i class="icon icon-edit icon-sm"></i>', '', "class='btn' title='{$lang->ai->prompts->action->edit}'");?>
        <?php echo html::a(helper::createLink('ai', 'promptdelete'), '<i class="icon icon-trash icon-sm"></i>', '', "class='btn deleter' title='{$lang->ai->prompts->action->delete}'");?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">

  </div>
</div>

<script>
  $(function()
  {
    /* TODO: use new modal for these. */
    $('.deleter').click(function()
    {
      if(confirm('<?php echo $lang->ai->prompts->action->deleteConfirm;?>'))
      {
        $.getJSON($(this).attr('href'), function(resp)
        {
          if(resp.result != 'success') $.zui.messager.danger(resp.message);
          return location.reload();
        });
      }
      return false;
    });
    $('#unpublish-btn').click(function()
    {
      if(confirm('<?php echo $lang->ai->prompts->action->draftConfirm;?>'))
      {
        $.getJSON($(this).attr('href'), function(resp)
        {
          if(resp.result != 'success') $.zui.messager.danger(resp.message);
          return location.reload();
        });
      }
      return false;
    });
  });
</script>

<?php include '../../common/view/footer.html.php';?>
