<?php
/**
 * The createstory view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
          <tr class='storyTR'>
            <th><?php echo $lang->story->reviewedBy;?></th>
            <td><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
            <?php if(!$this->story->checkForceReview()):?>
            <td>
              <div class='checkbox-primary'>
                <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin'/>
                <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
              </div>
            </td>
            <?php endif;?>
          </tr>
          <tr class='storyTR'>
            <th><?php echo $lang->story->title;?></th>
            <td colspan="2">
              <div class='table-row'>
                <div class='table-col'>
                  <div class="input-control has-icon-right">
                    <?php echo html::input('title', $issue->title, "class='form-control'");?>
                    <div class="colorpicker">
                      <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                      <ul class="dropdown-menu clearfix">
                        <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                      </ul>
                      <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                    </div>
                  </div>
                </div>
                <?php if(strpos(",$showFields,", ',pri,') !== false): // begin print pri selector?>
                <div class='table-col w-150px'>
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->story->pri;?></span>
                    <?php
                    $hasCustomPri = false;
                    foreach($lang->story->priList as $priKey => $priValue)
                    {
                        if(!empty($priKey) and (string)$priKey != (string)$priValue)
                        {
                            $hasCustomPri = true;
                            break;
                        }
                    }

                    $priList = $lang->story->priList;
                    if(end($priList)) unset($priList[0]);
                    ?>
                    <?php if($hasCustomPri):?>
                    <?php echo html::select('pri', (array)$priList, $issue->pri, "class='form-control'");?>
                    <?php else:?>
                    <div class="input-group-btn pri-selector" data-type="pri">
                      <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                        <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($issue->pri) ? '0' : $issue->pri?>" title="<?php echo $issue->pri?>"><?php echo $issue->pri?></span></span> &nbsp;<span class="caret"></span>
                      </button>
                      <div class='dropdown-menu pull-right'>
                        <?php echo html::select('pri', (array)$priList, $issue->pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                      </div>
                    </div>
                    <?php endif;?>
                  </div>
                </div>
                <?php endif; ?>
                <?php if(strpos(",$showFields,", ',estimate,') !== false):?>
                <div class='table-col w-120px'>
                  <div class="input-group">
                    <span class="input-group-addon fix-border br-0"><?php echo $lang->story->estimateAB;?></span>
                    <input type="text" name="estimate" id="estimate" value="" class="form-control" autocomplete="off" placeholder='<?php echo $lang->story->hour;?>' />
                  </div>
                </div>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <tr class='storyTR'>
            <th><?php echo $lang->story->spec;?></th>
            <td colspan="2">
              <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=story&link=spec');?>
              <?php echo html::textarea('spec', $issue->desc, "rows='9' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true' placeholder='" . htmlspecialchars($lang->story->specTemplate . "\n" . $lang->noticePasteImg) . "'");?>
            </td>
          </tr>
          <?php if(strpos(",$showFields,", ',verify,') !== false):?>
          <tr class='storyTR'>
            <th><?php echo $lang->story->verify;?></th>
            <td colspan="2"><?php echo html::textarea('verify', $verify, "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <?php endif;?>
         <tr class='storyTR'>
            <th><?php echo $lang->story->legendAttatch;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
          </tr>
