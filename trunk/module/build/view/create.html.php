<?php
/**
 * The create view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->build->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->build->product;?></th>
      <td><?php echo html::select('product', $products, '', "class='select-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->name;?></th>
      <td><?php echo html::input('name', '', "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->builder;?></th>
      <td><?php echo html::select('builder', $users, $app->user->account, 'class="select-3"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->date;?></th>
      <td><?php echo html::input('date', helper::today(), "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->scmPath;?></th>
      <td><?php echo html::input('scmPath', '', "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->filePath;?></th>
      <td><?php echo html::input('filePath', '', "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->linkStoriesAndBugs;?></th>
      <td>
        <div class="w-p90">

          <div class='half-left'>
            <table class='mainTable'>
              <tr style='border-bottom:none'>
                <td style='border-bottom:none; padding:0px'>
                  <table class='headTable'>
                    <caption><?php echo $lang->build->linkStories;?></caption>
                    <tr>
                      <th class='w-id a-left'> <?php echo html::selectAll('story', 'checkbox') . $lang->idAB;?></th>
                      <th><?php echo $lang->story->title;?></th>
                      <th class='w-hour'><?php echo $lang->statusAB;?></th>
                      <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr style='border-bottom:none'>
                <td style='border-bottom:none; padding:0px'>
                  <div class='contentDiv'>
                    <table class='f-left table-1 fixed'>
                      <?php foreach($stories as $key => $story):?>
                      <?php
                      $storyLink = $this->createLink('story', 'view', "storyID=$story->id");
                      ?>
                      <tr class='a-center'>
                        <td class='w-id a-left' id='story'>
                          <input type='checkbox' name='stories[]' value="<?php echo $story->id;?>" <?php if($story->stage == 'developed' or $story->status == 'closed') echo 'checked';?>> <?php echo sprintf('%03d', $story->id);?>
                        </td>
                        <td class='a-left nobr'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
                        <td class='<?php echo $story->status;?> w-50px'><?php echo $lang->story->statusList[$story->status];?></td>
                        <td class='w-80px'><?php echo $lang->story->stageList[$story->stage];?></td>
                      </tr>
                    <?php endforeach;?>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </div>

          <div class='half-right'>
            <table class='mainTable'>
              <tr style='border-bottom:none'>
                <td style='border-bottom:none; padding:0px'>
                  <table class='headTable'>
                    <caption><?php echo $lang->build->linkBugs;?></caption>
                    <tr>
                      <th class='w-id a-left'><?php echo html::selectAll('bug', 'checkbox') . $lang->idAB;?></th>
                      <th><?php echo $lang->bug->title;?></th>
                      <th class='w-100px'><?php echo $lang->bug->status;?></th>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr style='border-bottom:none'>
                <td style='border-bottom:none; padding:0px'>
                  <div class='contentDiv'>
                    <table class='f-left table-1 fixed'>
                      <?php foreach($bugs as $bug):?>
                      <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id");?>
                      <tr class='a-center'>
                        <td class='w-id a-left' id='bug'>
                          <input type='checkbox' name='bugs[]' value="<?php echo $bug->id;?>" <?php if($bug->status == 'resolved' or $bug->status == 'closed') echo "checked";?>> <?php echo sprintf('%03d', $bug->id);?>
                        </td>
                        <td class='a-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
                        <td class='w-80px'><?php echo $lang->bug->statusList[$bug->status];?></td>
                      </tr>
                      <?php endforeach;?>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </div>

        </div>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->build->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='10' class='area-1'");?></td>
    </tr>  
    <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
