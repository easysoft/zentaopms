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
<div id='mainContent' class="main-row" <?php if(isonlybody()) echo 'style="padding-top: 0px;"';?>>
  <div class="col-8 main-col">
    <div class="row">
      <div class="col-sm-12">
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><span class="label-id"><?php echo $product->id;?></span> <span class="label label-light label-outline"><?php echo $product->code;?></span> <?php echo $product->name;?></h2>
            <div class="detail-content article-content">
              <p><span class="text-limit" data-limit-size="40"><?php echo $product->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></p>
              <p>
                <span class="label label-primary label-outline"><?php echo $lang->product->typeAB . ':' . zget($lang->product->typeList, $product->type);?></span>
                <span class="label label-success label-outline"><?php echo $lang->product->status . ':' . $this->processStatus('product', $product);?></span>
                <?php if($product->deleted):?>
                <span class='label label-danger label-outline'><?php echo $lang->product->deleted;?></span>
                <?php endif; ?>
              </p>
            </div>
          </div>
          <?php if($product->type == 'platform'):?>
          <div class="detail">
          <div class="detail-title"><strong><?php echo $lang->product->branchName['platform'];?></strong><a class="btn btn-link pull-right muted"><i class="icon icon-more icon-sm"></i></a></div>
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
                    <td><em><?php echo zget($users, $product->PO);?></em></td>
                  </tr>
                  <tr>
                    <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->product->qa;?></th>
                    <td><em><?php echo zget($users, $product->QD);?></em></td>
                  </tr>
                  <tr>
                    <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->product->release;?></th>
                    <td><em><?php echo zget($users, $product->RD);?></em></td>
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
                    <th class="w-80px"><?php echo $lang->story->openedBy?></th>
                    <td><em><?php echo zget($users, $product->createdBy);?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->openedDate?></th>
                    <td><em><?php echo formatTime($product->createdDate, DT_DATE1);?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->product->acl;?></th>
                    <td><em><?php echo $lang->product->aclList[$product->acl];?></em></td>
                  </tr>
                  <?php if($product->acl == 'custom'):?>
                  <tr>
                    <th><?php echo $lang->product->whitelist;?></th>
                    <td>
                      <em>
                        <?php
                        $whitelist = explode(',', $product->whitelist);
                        foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
                        ?>
                      </em>
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
                    <td><em><?php echo $product->stories['active']?></em></td>
                    <th><?php echo $lang->product->plans?></th>
                    <td><em><?php echo $product->plans?></em></td>
                    <th class='w-80px'><?php echo $lang->product->bugs?></th>
                    <td><em><?php echo $product->bugs?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->statusList['changed']  . $space . $lang->story->common;?></th>
                    <td><em><?php echo $product->stories['changed']?></em></td>
                    <th><?php echo $lang->product->projects?></th>
                    <td><em><?php echo $product->projects?></em></td>
                    <th><?php echo $lang->product->cases?></th>
                    <td><em><?php echo $product->cases?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->statusList['draft']  . $space . $lang->story->common;?></th>
                    <td><em><?php echo $product->stories['draft']?></em></td>
                    <th><?php echo $lang->product->builds?></th>
                    <td><em><?php echo $product->builds?></em></td>
                    <th><?php echo $lang->product->docs?></th>
                    <td><em><?php echo $product->docs?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->story->statusList['closed']  . $space . $lang->story->common;?></th>
                    <td><em><?php echo $product->stories['closed']?></em></td>
                    <th><?php echo $lang->product->releases?></th>
                    <td><em><?php echo $product->releases?></em></td>
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
