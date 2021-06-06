<?php
/**
 * The view file of automation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     automation
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-10">
      <div class='text-center'>
        <h2 style='margin-top: 18px;'><?php echo $lang->automation->title;?></h1>
      </div>
      <div class='ztf-block'>
        <div class='ztf-logo'>
          <img src='<?php echo $config->webRoot . 'theme/default/images/main/ztf.png';?>' />
        </div>
        <div class='ztf-download'>
          <div class='ztf-download-trait'>
            <div><span class='dot-symbol'>●</span><span><?php echo $lang->automation->ztfFeature1;?></span></div>
            <div><span class='dot-symbol'>●</span><span><?php echo $lang->automation->ztfFeature19;?></span></div>
            <div><span class='dot-symbol'>●</span><span><?php echo $lang->automation->ztfFeature7;?></span></div>
            <div><span class='dot-symbol'>●</span><span><?php echo $lang->automation->ztfFeature12;?></span></div>
          </div>
          <div class="ztf-download-button">
            <a href="<?php echo $config->automation->ztfSite;?>" target='_blank' class='btn btn-primary btn-automation'><?php echo $lang->automation->ztfSite;?></a>
            <a href="<?php echo $config->automation->ztfDownload;?>" target='_blank' class='btn btn-primary btn-automation'><?php echo $lang->automation->ztfDownload;?></a>
            <a href="<?php echo $config->automation->ztfManual;?>" target='_blank' class='btn btn-primary btn-automation'><?php echo $lang->automation->ztfManual;?></a>
          </div>
        </div>
      </div>

      <div class='block-content'>
        <div class='block-details'>
          <p><?php echo $lang->automation->ztfFeature2;?></p>
          <ul>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature3;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature4;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature5;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature6;?></li>
          </ul>
        </div>
        <div class='block-details block-details-right'>
          <p><?php echo $lang->automation->ztfFeature7;?></p>
          <ul>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature8;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature9;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature10;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature11;?></li>
          </ul>
        </div>
        <div class='block-details block-details-line'>
          <p><?php echo $lang->automation->ztfFeature12;?></p>
          <ul>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature13;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature14;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature15;?></li>
          </ul>
        </div>
        <div class='block-details block-details-line block-details-right'>
          <p><?php echo $lang->automation->ztfFeature12;?></p>
          <ul>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature16;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature17;?></li>
            <li><span class='dot-symbol'>●</span><?php echo $lang->automation->ztfFeature18;?></li>
          </ul>
        </div>
      </div>

      <div class='zendata-block'>
        <div class='zendata-logo'>
          <img src='<?php echo $config->webRoot . 'theme/default/images/main/zendata.png';?>' />
        </div>
        <div class='zendata-download'>
          <div class='zendata-download-trait'>
            <div><span class='dot-symbol'>●</span><?php echo $lang->automation->zendata;?></div>
            <div><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature4;?></div>
            <div><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature8;?></div>
          </div>
          <div class="zendata-download-button">
            <a href="<?php echo $config->automation->zendataSite;?>" target='_blank' class='btn btn-primary btn-automation'><?php echo $lang->automation->zendataSite;?></a>
            <a href="<?php echo $config->automation->zendataDownload;?>" target='_blank' class='btn btn-primary btn-automation'><?php echo $lang->automation->zendataDownload;?></a>
            <a href="<?php echo $config->automation->zendataManual;?>" target='_blank' class='btn btn-primary btn-automation'><?php echo $lang->automation->zendataManual;?></a>
          </div>
        </div>
      </div>

      <div class='zentata-content'>
        <div class='block-zendata-block'>
          <div class='block-zendata'>
            <p><?php echo $lang->automation->zendata;?></p>
            <ul>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature1;?></li>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature2;?></li>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature3;?></li>
            </ul>
          </div>
        </div>
        <div class='block-zendata-block'>
          <div class='block-zendata'>
            <p><?php echo $lang->automation->zendataFeature4;?></p>
            <ul>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature5;?></li>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature6;?></li>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature7;?></li>
            </ul>
          </div>
        </div>
        <div class='block-zendata-block'>
          <div class='block-zendata' style='width: 100%;'>
            <p><?php echo $lang->automation->zendataFeature8;?></p>
            <ul>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature9;?></li>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature10;?></li>
              <li><span class='dot-symbol'>●</span><?php echo $lang->automation->zendataFeature11;?></li>
            </ul>
          </div>
        </div>
      </div>

      <div class='qr-code'>
        <div class='qr-code-img'>
          <img src='<?php echo $config->webRoot . 'theme/default/images/main/qrcode.png';?>' />
        </div>
        <div class='qr-code-details'>
          <div style='font-size: 16px; font-weight: bold;'><?php echo $lang->automation->groupTips;?></div>
          <div class='qr-code-group'><span><?php echo $lang->automation->groupTitle;?></span> <span style='color: #1d7cf1;'><?php echo $lang->automation->qqGroup;?></span></div>
          <div class='qr-code-group'><?php echo $lang->automation->groupDescription;?></div>
        </div>
      </div>
    </div>
    <div class="col-xs-1"></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
