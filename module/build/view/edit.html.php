<?php
/**
 * The edit view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'> 
    <caption><?php echo $lang->build->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->build->product;?></th>
      <td><?php echo html::select('product', $products, $build->product, "class='select-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->name;?></th>
      <td><?php echo html::input('name', $build->name, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->builder;?></th>
      <td><?php echo html::select('builder', $users, $build->builder, 'class="select-3"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->date;?></th>
      <td><?php echo html::input('date', $build->date, "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->scmPath;?></th>
      <td><?php echo html::input('scmPath', $build->scmPath, "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->filePath;?></th>
      <td><?php echo html::input('filePath', $build->filePath, "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->linkStoriesAndBugs;?></th>
      <td>
        <div class="w-p90">
          <div class='half-left' style="height:225px; overflow-y:auto">
            <table class='table-1'>
            <caption><?php echo $lang->build->linkStories;?></caption>
              <tr>
                <th class='w-id'><?php echo $lang->idAB;?></th>
                <th><?php echo $lang->story->title;?></th>
                <th class='w-hour'><?php echo $lang->statusAB;?></th>
                <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
              </tr>
              <?php foreach($stories as $key => $story):?>
              <?php
              $storyLink = $this->createLink('story', 'view', "storyID=$story->id");
              ?>
              <tr class='a-center'>
                <td><input type='checkbox' name='stories[]' value="<?php echo $story->id;?>" <?php if(strpos($build->stories, $story->id) !== false) echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?></td>
                <td class='a-left nobr'><?php echo html::a($storyLink,$story->title);?></td>
                <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
                <td><?php echo $lang->story->stageList[$story->stage];?></td>
              </tr>
              <?php endforeach;?>
            </table>
          </div>
          <div class='half-right' style="height:225px; overflow-y:auto">
            <table class='table-1'>
              <caption><?php echo $lang->build->linkBugs;?></caption>
              <tr>
                <th class='w-id'>       <?php echo $lang->idAB;?></th>
                <th><?php echo $lang->bug->title;?></th>
                <th class='w-100px'><?php echo $lang->bug->status;?></th>
              </tr>
              <?php foreach($bugs as $bug):?>
              <tr class='a-center'>
                <td><input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if(strpos($build->bugs, $bug->id) !== false) echo 'checked';?>> <?php echo sprintf('%03d', $bug->id);?></td>
                <td class='a-left nobr'><?php common::printLink('bug', 'view', "bugID=$bug->id", $bug->title, '', "class='preview'");?></td>
                <td><?php echo $lang->bug->statusList[$bug->status];?></td>
              </tr>
              <?php endforeach;?>
            </table>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->build->desc;?></th>
      <td><?php echo html::textarea('desc', htmlspecialchars($build->desc), "rows='15' class='area-1'");?></td>
    </tr>  
    <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton() .html::hidden('project', $build->project);?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
