<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<script>
  var testcaseBatchCreateNum = '<?php echo $config->testcase->batchCreate;?>';
  var noResultsMatch = '<?php echo $lang->noResultsMatch;?>';
</script>
<form method='post' enctype='multipart/form-data'>
  <table align='center' class='table-1 fixed'> 
    <caption><?php echo $lang->testcase->batchCreate;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='w-300px'><?php echo $lang->testcase->module;?></th>
      <th class='w-180px'><?php echo $lang->testcase->type;?></th>
      <th><?php echo $lang->testcase->story;?></th>
      <th class='w-300px'><?php echo $lang->testcase->title;?></th>
    </tr>
    <?php for($i = 0; $i < $config->testcase->batchCreate; $i++):?>
    <?php $moduleOptionMenu['same'] = $lang->testcase->same; if($i != 0) $currentModuleID = 'same';?>
    <?php $lang->testcase->typeList['same'] = $lang->testcase->same; $type = ($i == 0 ? 'feature' : 'same');?>
    <?php $stories['same'] = $lang->testcase->same; $story = $i == 0 ? '' : 'same';?>
    <?php $pri = 3;?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class=select-1");?></td>
      <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class=select-1"); echo "<span class='star'>*</span>";?></td>
      <td class='a-left'style='overflow:visible'><?php echo html::select("story[$i]", $stories, $story, 'class=select-1');?></td>
      <td><?php echo html::input("title[$i]", '', "class='text-1'"); echo "<span class='star'>*</span>";?></td>
    </tr>  
    <?php endfor;?>
    <tr>
      <td colspan='5'>
        <div class='half-left red'><?php echo $lang->testcase->notes;?></div>
        <div class='half-right'><?php echo html::submitButton() . html::resetButton();?></div>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
