#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen;
su('admin');

/**

title=productModel->getProductLink();
timeout=0
cid=1

*/

global $tester;
$productModel = $tester->loadModel('product');
$productModel->config->requestType = 'PATH_INFO';
$productModel->config->webRoot     = '/';

r($productModel->getProductLink('product', 'roadmap'))           && p() && e('/product-roadmap-%s.html');    //当前页面是product-roadmap，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product', 'roadmap', '', true)) && p() && e('/product-roadmap-%s-%s.html'); //当前页面是product-roadmap，包含分支，切换1.5级导航时跳转链接。

r($productModel->getProductLink('bug',     'view'))                          && p() && e('/bug-browse-%s-all-.html');            //当前页面是bug-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('bug',     'view',     'projectID=1'))       && p() && e('/bug-browse-%s-all-projectID=1.html'); //当前页面是bug-view，传入extra参数，切换1.5级导航时跳转链接。
r($productModel->getProductLink('bug',     'view',     'projectID=1', true)) && p() && e('/bug-browse-%s-%s-projectID=1.html');  //当前页面是bug-view，传入extra参数，并包含分支，切换1.5级导航时跳转链接。
r($productModel->getProductLink('project', 'testcase', '1,all'))             && p() && e('/project-testcase-1-%s-all-all.html'); //当前页面是project-testcase，传入extra参数，切换1.5级导航时跳转链接。

r($productModel->getProductLink('product',     'project'))     && p() && e('/product-project-all-%s.html');                       //当前页面是product-project，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'dynamic'))     && p() && e('/product-dynamic-%s-.html');                          //当前页面是product-dynamic，切换1.5级导航时跳转链接。
r($productModel->getProductLink('project',     'bug'))         && p() && e('/project-bug--%s.html');                              //当前页面是project-bug，切换1.5级导航时跳转链接。
r($productModel->getProductLink('qa',          'index'))       && p() && e('/bug-browse-%s.html');                                //当前页面是qa-index，切换1.5级导航时跳转链接。
r($productModel->getProductLink('story',       'report'))      && p() && e('/story-report-%s-all-.html');                         //当前页面是story-report，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testcase',    'browse'))      && p() && e('/testcase-browse-%s-all-.html');                      //当前页面是testcase-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testreport',  'create'))      && p() && e('/testreport-create--testtask-%s.html');               //当前页面是testreport-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testreport',  'edit'))        && p() && e('/testreport-browse-%s.html');                         //当前页面是testreport-edit，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testtask',    'browseUnits')) && p() && e('/testtask-browseUnits-%s-newest-id_desc-0-0-1.html'); //当前页面是testtask-browseUnits，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testtask',    'browse'))      && p() && e('/testtask-browse-%s-all-.html');                      //当前页面是testtask-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('execution',   'bug'))         && p() && e('/execution-bug--%s.html');                            //当前页面是execution-bug，切换1.5级导航时跳转链接。
r($productModel->getProductLink('execution',   'testcase'))    && p() && e('/execution-testcase--%s.html');                       //当前页面是execution-testcase，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'doc'))         && p() && e('/product-doc-%s.html');                               //当前页面是product-doc，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'view'))        && p() && e('/product-view-%s.html');                              //当前页面是product-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'create'))      && p() && e('/product-browse-%s-.html');                           //当前页面是product-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'showImport'))  && p() && e('/product-browse-%s-.html');                           //当前页面是product-showImport，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'browse'))      && p() && e('/product-browse-%s-all--0.html');                     //当前页面是product-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'index'))       && p() && e('/product-browse-%s-all--0.html');                     //当前页面是product-index，切换1.5级导航时跳转链接。
r($productModel->getProductLink('product',     'all'))         && p() && e('/product-browse-%s-all--0.html');                     //当前页面是product-all，切换1.5级导航时跳转链接。
r($productModel->getProductLink('ticket',      'browse'))      && p() && e('/ticket-browse-byProduct-%s.html');                   //当前页面是ticket-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('ticket',      'view'))        && p() && e('/ticket-browse-byProduct-%s.html');                   //当前页面是ticket-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('ticket',      'edit'))        && p() && e('/ticket-browse-byProduct-%s.html');                   //当前页面是ticket-edit，切换1.5级导航时跳转链接。
r($productModel->getProductLink('feedback',    'view'))        && p() && e('/feedback-admin-byProduct-%s.html');                  //当前页面是feedback-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('productplan', 'create'))      && p() && e('/productplan-create-%s.html');                        //当前页面是productplan-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('productplan', 'view'))        && p() && e('/productplan-view-%s.html');                          //当前页面是productplan-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('release',     'create'))      && p() && e('/release-create-%s.html');                            //当前页面是release-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('release',     'view'))        && p() && e('/release-view-%s.html');                              //当前页面是release-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testsuite',   'create'))      && p() && e('/testsuite-create-%s.html');                          //当前页面是testsuite-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testsuite',   'view'))        && p() && e('/testsuite-browse-%s.html');                          //当前页面是testsuite-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('design',      'view'))        && p() && e('/design-browse-%s.html');                             //当前页面是design-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('execution',   'view'))        && p() && e('/execution-view--%s.html');                           //当前页面是execution-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('programplan', 'view'))        && p() && e('/programplan-browse-%s-%s-gantt.html');               //当前页面是programplan-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('project',     'view'))        && p() && e('/project-view--%s.html');                             //当前页面是project-view，切换1.5级导航时跳转链接。
r($productModel->getProductLink('tree',        'browse'))      && p() && e('/tree-browse-%s--0.html');                            //当前页面是tree-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('ticket',      'create'))      && p() && e('/ticket-create-%s.html');                             //当前页面是ticket-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testtask',    'create'))      && p() && e('/testtask-browse-%s-all.html');                       //当前页面是testtask-create，切换1.5级导航时跳转链接。
r($productModel->getProductLink('api',         'browse'))      && p() && e('/doc-productSpace-%s.html');                          //当前页面是api-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('doc',         'browse'))      && p() && e('/doc-productSpace-%s.html');                          //当前页面是doc-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('productplan', 'browse'))      && p() && e('/productplan-browse-%s.html');                        //当前页面是productplan-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('release',     'browse'))      && p() && e('/release-browse-%s.html');                            //当前页面是release-browse，切换1.5级导航时跳转链接。
r($productModel->getProductLink('roadmap',     'browse'))      && p() && e('/roadmap-browse-%s.html');                            //当前页面是release-browse，切换1.5级导航时跳转链接。

$productModel->config->vision = 'lite';
r($productModel->getProductLink('feedback', 'view')) && p() && e('/feedback-browse-byProduct-%s.html'); //当前页面是feedback-view，在运营管理界面，切换1.5级导航时跳转链接。

$productModel->app->tab = 'project';
r($productModel->getProductLink('testcase', 'groupCase', '1'))          && p() && e('/testcase-groupcase-%s-all--1.html#app=project'); //当前页面是testcase-groupcase，在项目视图下，切换1.5级导航时跳转链接。
r($productModel->getProductLink('testcase', 'zeroCase', 'projectID=1')) && p() && e('/testcase-zerocase-%s-all--1.html#app=project');  //当前页面是testcase-zerocase，在项目视图下，切换1.5级导航时跳转链接。
