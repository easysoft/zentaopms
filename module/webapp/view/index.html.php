<?php
/**
 * The browse view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<div class='container bd-0'>
  <div class='cards webapps' id='webapps'>
    <?php foreach($webapps as $webapp):?>
    <div class='col-md-4 col-sm-6'><div class='card' id='webapp<?php echo $webapp->id?>'>
      <div class='media webapp-icon'><img src='<?php echo empty($webapp->icon) ? $config->webRoot . 'theme/default/images/main/webapp-default.png' : $webapp->icon?>' width='72' height='72' /></div>
      <div class='card-heading' class='webapp-name' title='<?php echo $webapp->name?>'>
        <div class='pull-right'>
        <?php common::printLink('webapp', 'uninstall',  "webapp=$webapp->id", "<i class='icon-remove'></i> " . $lang->webapp->uninstall, 'hiddenwin',  "class='text-muted'"); ?>
        </div>
        <strong><?php common::printLink('webapp', 'view', "webappID=$webapp->id", $webapp->name, '',  "class='webapp'");?></strong> <small class='text-muted'><?php echo $webapp->author;?></small>
      </div>
      <div class='card-content text-muted' title='<?php echo $webapp->abstract?>'><?php echo $webapp->abstract;?></div>
      <div class='card-actions webapp-actions'>
        <div class='pull-right'>
        <?php
        $url     = $webapp->addType == 'custom' ? $webapp->url : $config->webapp->url . "/webapp-showapp-{$webapp->appid}.html";
        $popup   = '';
        $target  = '_self';
        $misc    = '';
        if($webapp->target == 'blank') $target   = '_blank';
        if($webapp->target == 'popup')
        {
            $width  = 0;
            $height = 0;
            if($webapp->size) list($width, $height) = explode('x', $webapp->size);
            $misc = "data-width='" . $width . "' data-height='" . $height . "'";
            $popup  = 'popup';
        }
        echo html::a($url, $lang->webapp->useapp, $target,  "id='useapp$webapp->id' class='btn btn-success runapp $popup' onclick='addView($webapp->id);' data-title='$webapp->name' $misc");
        ?>
        </div>
      </div>
    </div></div>
    <?php endforeach;?>
  </div>
</div>
<script type='text/javascript'>
var packup = '<?php echo $lang->webapp->packup?>';
var useapp = '<?php echo $lang->webapp->useapp?>';
var module = '<?php echo $module?>';
</script>
<?php include '../../common/view/footer.html.php';?>
