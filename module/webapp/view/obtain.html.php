<?php
/**
 * The obtain view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <Yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div class='side'>
  <form class='side-search mgb-20' method='post' action='<?php echo inlink('obtain', 'type=bySearch');?>'>
    <div class="input-group">
      <?php echo html::input('key', $this->post->key, "class='form-control' placeholder='{$lang->webapp->bySearch}'");?>
      <span class="input-group-btn">
        <?php echo html::submitButton('<i class="icon-search"></i>', '', ''); ?>
      </span>
    </div>
  </form>
  <div class='list-group'>
      <?php
      echo html::a(inlink('obtain', 'type=byUpdatedTime'), $lang->webapp->byUpdatedTime, '', "class='list-group-item' id='byupdatedtime'");
      echo html::a(inlink('obtain', 'type=byAddedTime'),   $lang->webapp->byAddedTime, '', "class='list-group-item' id='byaddedtime'");
      echo html::a(inlink('obtain', 'type=byDownloads'),   $lang->webapp->byDownloads, '', "class='list-group-item' id='bydownloads'");
      ?>
  </div>
  <div class='panel panel-sm'>
    <div class='panel-heading'><?php echo $lang->webapp->byCategory;?></div>
    <div class='panel-body'>
      <?php $moduleTree ? print($moduleTree) : print($lang->webapp->errorGetModules);?>
    </div>
  </div>
</div>
<div class='main'>
  <?php if($webapps):?>
  <div id='webapps' class='cards webstore webapps pd-0 mg-0'>
    <?php foreach($webapps as $webapp):?>
    <div class='col-md-6 col-sm-6'><div class='card' id='webapp<?php echo $webapp->id?>'>
      <div class='media webapp-icon'><img src='<?php echo empty($webapp->icon) ? '/theme/default/images/main/webapp-default.png' : $config->webapp->url . $webapp->icon?>' width='72' height='72' /></div>
      <div class='card-heading' class='webapp-name' title='<?php echo $webapp->name?>'>
        <strong><?php common::printLink('webapp', 'view', "webappID=$webapp->id&type=api", $webapp->name, '',  "class='apiapp'");?></strong> <small class='text-muted'><?php echo $webapp->author;?></small>
      </div>
      <div class='card-content text-muted' title='<?php echo $webapp->abstract?>'><?php echo $webapp->abstract;?></div>
      <div class='card-actions webapp-actions'>
        <div class='pull-right'>
          <div class='btn-group'>
          <?php
          $url     = $webapp->url;
          $popup   = '';
          $target  = '_self';
          $misc    = '';
          if($webapp->target == 'popup')
          {
              $width  = 0;
              $height = 0;
              if($webapp->size) list($width, $height) = explode('x', $webapp->size);
              $misc = "data-width='" . $width . "' data-height='" . $height . "'";
              $popup  = 'popup';
          }
          else
          {
              $method = "popup(1024, 600);";
              $misc = "data-width='1024' data-height='600'";
              $popup  = 'popup';
          }
          echo isset($installeds[$webapp->id]) ? html::commonButton("<i class='icon-ok'></i> " . $lang->webapp->installed, "disabled='disabled'", 'btn-success') : html::a(inLink('install', "webappID={$webapp->id}"), $lang->webapp->install, '_self', "class='btn btn-primary iframe'");

          echo html::a($url, $lang->webapp->preview, '', "id='useapp$webapp->id' class='btn runapp $popup' data-title='$webapp->name' $misc");
          ?>
          </div>
        </div>
      </div>
    </div></div>
    <?php endforeach;?>
  </div>
  <div class='clearfix'><?php if($pager) $pager->show();?></div>
  <?php else:?>
    <div class='box-title'><?php echo $lang->webapp->errorOccurs;?></div>
    <div class='box-content'><?php echo $lang->webapp->errorGetExtensions;?></div>
  <?php endif;?>
</div>
<?php js::set('installed', $lang->webapp->installed)?>
<script>
$('#<?php echo $type;?>').addClass('active')
$('#module<?php echo $moduleID;?>').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
