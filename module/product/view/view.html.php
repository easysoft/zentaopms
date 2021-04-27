<?php
/**
 * The view view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: view.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $style = isonlybody() ? 'style="padding-top: 0px;"' : '';?>
<div id='mainContent' class="main-row" <?php echo $style;?>>
  <div class="col-8 main-col">
    <div class="row">
      <div class="col-sm-12">
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><span class="label-id"><?php echo $product->id;?></span> <span class="label label-light label-outline"><?php echo $product->code;?></span> <?php echo $product->name;?></h2>
            <div class="detail-content article-content">
              <p><?php echo $product->desc;?></p>
            </div>
          </div>
          <?php if($product->type == 'platform'):?>
          <div class="detail">
          <div class="detail-title"><strong><?php echo $lang->product->branchName['platform'];?></strong><a class="btn btn-link pull-right muted" href="<?php echo $this->createLink('branch', 'manage', "productID={$product->id}")?>"><i class="icon icon-more icon-sm"></i></a></div>
            <div class="detail-content">
              <ul class="clearfix branch-list">
                <?php foreach($branches as $branchName):?>
                <li><?php echo $branchName;?></li>
                <?php endforeach;?>
                <li><a class="text-muted" href="<?php echo $this->createLink('branch', 'manage', "productID={$product->id}")?>"><i class="icon icon-plus hl-primary text-primary"></i> &nbsp;<?php echo $lang->branch->add;?></a></li>
              </ul>
            </div>
          </div>
          <?php endif;?>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->product->manager;?></strong></div>
            <div class="detail-content">
              <table class="table table-data">
                <tbody>
                  <tr>
                    <th class='w-65px'><i class="icon icon-person icon-sm"></i> <?php echo $lang->productCommon;?></th>
                    <td><strong><?php echo zget($users, $product->PO);?></strong></td>
                    <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->product->release;?></th>
                    <td><strong><?php echo zget($users, $product->RD);?></strong></td>
                    <td></td>
                  </tr>
                  <tr>
                    <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->product->qa;?></th>
                    <td><strong><?php echo zget($users, $product->QD);?></strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->product->basicInfo;?></strong></div>
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <tr>
                    <th class="w-80px"><?php echo $lang->product->code;?></th>
                    <td><strong><?php echo $product->code;?></strong></td>
                    <th class="w-80px"><?php echo $lang->story->openedBy?></th>
                    <td colspan="2"><strong><?php echo zget($users, $product->createdBy);?></strong></td>
                  </tr>
                  <tr>
                    <th class="w-80px"><?php echo $lang->product->type;?></th>
                    <td><strong><?php echo zget($lang->product->typeList, $product->type);?></strong></td>
                    <th><?php echo $lang->story->openedDate?></th>
                    <td colspan="2"><strong><?php echo formatTime($product->createdDate, DT_DATE1);?></strong></td>
                  </tr>
                  <tr>
                    <th class="w-80px"><?php echo $lang->productCommon . $lang->product->status;?></th>
                    <td class="<?php echo $product->status;?>"><strong><?php echo zget($lang->product->statusList, $product->status);?></strong></td>
                    <th><?php echo $lang->product->acl;?></th>
                    <td colspan="2"><strong><?php echo $lang->product->aclList[$product->acl];?></strong></td>
                  </tr>
                  <?php if($product->acl == 'custom'):?>
                  <tr>
                    <th><?php echo $lang->product->whitelist;?></th>
                    <td>
                      <strong>
                        <?php
                        $whitelist = explode(',', $product->whitelist);
                        foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
                        ?>
                      </strong>
                    </td>
                  </tr>
                  <?php endif;?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->product->otherInfo;?></strong></div>
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <?php $space = common::checkNotCN() ? ' ' : '';?>
                  <tr>
                    <th><?php echo $lang->story->statusList['active']  . $space . $lang->story->common;?></th>
                    <td><strong><?php echo $product->stories['active']?></strong></td>
                    <th><?php echo $lang->product->plans?></th>
                    <td><strong><?php echo $product->plans?></strong></td>
                    <th class='w-80px'><?php echo $lang->product->bugs?></th>
                    <td><strong><?php echo $product->bugs?></strong></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->statusList['changed']  . $space . $lang->story->common;?></th>
                    <td><strong><?php echo $product->stories['changed']?></strong></td>
                    <th><?php echo $lang->product->releases?></th>
                    <td><strong><?php echo $product->releases?></strong></td>
                    <th><?php echo $lang->product->cases?></th>
                    <td><strong><?php echo $product->cases?></strong></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->statusList['draft']  . $space . $lang->story->common;?></th>
                    <td><strong><?php echo $product->stories['draft']?></strong></td>
                    <th><?php echo $lang->product->builds?></th>
                    <td><strong><?php echo $product->builds?></strong></td>
                    <th><?php echo $lang->product->docs?></th>
                    <td><strong><?php echo $product->docs?></strong></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->statusList['closed']  . $space . $lang->story->common;?></th>
                    <td><strong><?php echo $product->stories['closed']?></strong></td>
                    <?php if($config->systemMode == 'new'):?>
                    <th><?php echo $lang->product->projects?></th>
                    <td><strong><?php echo $product->projects?></strong></td>
                    <?php endif;?>
                    <th><?php echo $lang->product->executions?></th>
                    <td><strong><?php echo $product->executions?></strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <?php $this->printExtendFields($product, 'div', "position=right&inForm=0&inCell=1");?>
        </div>
      </div>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php
        $params = "product=$product->id";
        $browseLink = $this->session->productList ? $this->session->productList : inlink('browse', "productID=$product->id");
        common::printBack($browseLink);
        if(!$product->deleted)
        {
            echo $this->buildOperateMenu($product, 'view');

            echo "<div class='divider'></div>";

            if($product->status != 'closed')
            {
                common::printIcon('product', 'close', $params, $product, 'button', '', '', 'iframe', true);
                echo "<div class='divider'></div>";
            }

            common::printIcon('product', 'edit', $params, $product);
            common::printIcon('product', 'delete', $params, $product, 'button', 'trash', 'hiddenwin');
        }
        ?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class="row">
      <div class="col-sm-12">
        <?php $blockHistory = true;?>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=product&objectID=$product->id");?>
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
</div>
<?php include '../../common/view/footer.html.php';?>
