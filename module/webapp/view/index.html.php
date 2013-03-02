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
<table class='table-1'>
  <tr>
    <td>
      <ul id='webapps'>
        <?php foreach($webapps as $webapp):?>
        <li>
          <table class='fixed exttable' id='webapp<?php echo $webapp->id?>'>
            <tr>
              <td rowspan='3' width='73' height='73' class='webapp-icon'><img src='<?php echo empty($webapp->icon) ? $config->webRoot . 'theme/default/images/main/webapp-default.png' : $webapp->icon?>' width='72' height='72' /></td>
              <td class='webapp-name' title='<?php echo $webapp->name?>'><?php echo $webapp->name . "($webapp->author)"?></td>
            </tr>
            <tr><td valign='top'><div class='webapp-info' title='<?php echo $webapp->abstract?>'><?php echo empty($webapp->abstract) ? '&nbsp;' : $webapp->abstract?></div></td></tr>
            <tr>
              <td>
              <?php
              $url     = $webapp->addType == 'custom' ? $webapp->url : $config->webapp->url . "/webapp-showapp-{$webapp->appid}.html";
              $method  = '';
              $popup   = '';
              $target  = '_self';
              if($webapp->target == 'blank') $target   = '_blank';
              if($webapp->target == 'iframe')$method   = "toggleShowapp($webapp->id, \"$webapp->name\");";
              if($webapp->target == 'popup')
              {
                  $width  = 0;
                  $height = 0;
                  if($webapp->size) list($width, $height) = explode('x', $webapp->size);
                  $method = "popup($width, $height);";
                  $popup  = 'popup';
              }
              echo html::a($url, $lang->webapp->useapp, $target,  "id='useapp$webapp->id' class='button-c $popup' onclick='addView($webapp->id);$method'");
              common::printLink('webapp', 'view', "webappID=$webapp->id", $lang->webapp->view, '',  "class='button-c webapp'");
              common::printLink('webapp', 'uninstall',  "webapp=$webapp->id", $lang->webapp->uninstall, 'hiddenwin',  "class='button-c'");
              ?>
              </td>
            </tr>
          </table>
        </li>
        <?php endforeach;?>
      </ul>
    </td>
  </tr>
</table>
<script type='text/javascript'>
var packup = '<?php echo $lang->webapp->packup?>';
var useapp = '<?php echo $lang->webapp->useapp?>';
var module = '<?php echo $module?>';
</script>
<?php include '../../common/view/footer.html.php';?>
