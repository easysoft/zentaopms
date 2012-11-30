<?php
/**
 * The browse view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
    <?php foreach($webapps as $webapp):?>
    <table class='table-1 exttable' id='webapp<?php echo $webapp->id?>'>
      <caption><img src='<?php echo $webapp->icon?>'> <?php echo "$webapp->name";?></caption> 
      <tr valign='middle'>
        <td>
          <div class='mb-10px'><?php echo $webapp->desc;?></div>
          <div>
            <?php
            echo "{$lang->webapp->author} :     <i>{$webapp->author}</i> ";
            echo "{$lang->webapp->addType}:     <i>{$lang->webapp->addTypeList[$webapp->addType]}</i> ";
            ?>
          </div>
        </td>
        <td class='w-220px a-right'>
        <?php
        $uninstallCode = html::a(inlink('uninstall',  "webapp=$webapp->id"), $lang->webapp->uninstall, 'hiddenwin',  "class='button-c'");
        $url     = $webapp->addType == 'custom' ? $webapp->url : $config->webapp->url . "/webapp-showapp-{$webapp->appid}.html";
        $method  = '';
        $popup   = '';
        $target  = '_self';
        if($webapp->target == 'blank') $target   = '_blank';
        if($webapp->target == 'iframe')$method   = "toggleShowapp($webapp->id);";
        if($webapp->target == 'popup')
        {
            $width  = 0;
            $height = 0;
            if($webapp->size) list($width, $height) = explode('x', $webapp->size);
            $method = "popup($width, $height);";
            $popup  = 'popup';
        }
        $useAppCode    = html::a($url, $lang->webapp->useapp, $target,  "id='useapp$webapp->id' class='button-c $popup' onclick='addView($webapp->id);$method'");
        $editAppCode    = html::a(inlink('edit', "webappID=$webapp->id"), $lang->edit, '',  "class='button-c webapp'");
        echo $useAppCode . $editAppCode . $uninstallCode;
        ?>
        </td>
      </tr>
    </table>
    <?php endforeach;?>
    </td>
  </tr>
</table>
<script type='text/javascript'>
var packup = '<?php echo $lang->webapp->packup?>';
var useapp = '<?php echo $lang->webapp->useapp?>';
var module = '<?php echo $module?>';
</script>
<?php include '../../common/view/footer.html.php';?>
