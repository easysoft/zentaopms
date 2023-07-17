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

<?php include '../../common/view/footer.html.php';?>
