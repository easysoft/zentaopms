<?php
/**
 * The obtain view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->extension->obtain;?></div>
      <div class='box-content a-center'>
        <?php
        echo "<span id='bydownloads'>"   . html::a(inlink('obtain', 'type=byDownloads'),   $lang->extension->byDownloads)   . '</span><br />';
        echo "<span id='byaddedtime'>"   . html::a(inlink('obtain', 'type=byAddedTime'),   $lang->extension->byAddedTime)   . '</span><br />';
        echo "<span id='byupdatedtime'>" . html::a(inlink('obtain', 'type=byUpdatedTime'), $lang->extension->byUpdatedTime) . '</span><br />';
        ?>
      </div>
      <div class='box-title'><?php echo $lang->extension->bySearch;?></div>
      <div class='box-content a-center'>
        <form method='post' action='<?php echo inlink('obtain', 'type=bySearch');?>'>
        <?php
        echo html::input('key', $this->post->key, "class='text-1'") . html::submitButton($lang->extension->bySearch);
        ?>
        </form>
      </div>
      <div class='box-title'><?php echo $lang->extension->byCategory;?></div>
      <div class='box-content' id='tree'>
        <?php echo $moduleTree;?>
      </div>
    </td>
    <td class='divider'></td>
    <td> 
      <table class='table-1 tablesorter'>
        <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->extension->id;?></th>
          <th class='w-150px'><?php echo $lang->extension->name;?></th>
          <th class='w-50px'><?php echo $lang->extension->code;?></th>
          <th class='w-50px'><?php echo $lang->extension->version;?></th>
          <th><?php echo $lang->extension->desc;?></th>
          <th><?php echo $lang->extension->author;?></th>
          <th class='w-id'><?php echo $lang->extension->downloads;?></th>
          <th><?php echo $lang->extension->public;?></th>
          <th><?php echo $lang->extension->compatible;?></th>
          <th class='w-150px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($extensions as $extension):?>
        <tr class='a-center'>
          <td class='strong'><?php echo $extension->id;?></td>
          <td class='a-left'><?php echo $extension->name;?></td>
          <td><?php echo $extension->code;?></td>
          <td><?php echo $extension->version;?></td>
          <td class='a-left'><?php echo $extension->desc;?></td>
          <td><?php echo $extension->author;?></td>
          <td><?php echo $extension->downloads;?></td>
          <td><?php echo $lang->extension->publicList[$extension->public];?></td>
          <td><?php echo $lang->extension->compatibleList[$extension->compatible];?></td>
          <td class='a-right'>
            <?php 
            $installLink = inlink('install',  "extension=$extension->code&downLink=" . helper::safe64Encode($extension->downLink) . "&md5=$extension->md5&overridePackage=no&ignoreCompitable=yes");
            if($extension->public)
            {
                $label = $extension->compatible ? $lang->extension->installAuto : $lang->extension->installForce;
                echo html::a($installLink, $label, '', 'class="iframe button-c"');
            }
            echo html::a($extension->downLink, $lang->extension->downloadAB, '', 'class=button-c');
            echo html::a($extension->site, $lang->extension->site, '_blank', 'class=button-c');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr><td class='a-right' colspan='10'><?php if($pager) $pager->show();?></td></tr>
        </tfoot>
      </table>
    </td>
  </tr>
</table>
<script>
$('#<?php echo $type;?>').addClass('active')
$('#module<?php echo $moduleID;?>').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
