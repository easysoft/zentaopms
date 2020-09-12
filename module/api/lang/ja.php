<?php
/**
 * The api module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      zengqingyang wangguannan
 * @package     api
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->api = new stdclass();
$lang->api->common = 'API';
$lang->api->getModel = 'スーパーモデルでインタフェース呼び出す';
$lang->api->sql = 'SQLクエリインタフェース';

$lang->api->position = '位置';
$lang->api->startLine = '%s,%s行';
$lang->api->desc = '説明';
$lang->api->debug = 'デバッグ';
$lang->api->submit = '提出';
$lang->api->url = 'アドレス';
$lang->api->result = '結果';
$lang->api->status = 'ステータス';
$lang->api->data = '内容';
$lang->api->noParam = 'GETモードのデバッグはパラメーターが必要ありません。';
$lang->api->post = 'POSTモードのデバッグについては、ページリストを参照してください';

$lang->api->error = new stdclass();
$lang->api->error->onlySelect = 'SQLクエリインタフェースはSELECTクエリのみサポートします';
$lang->api->error->disabled   = '因为安全原因，该功能被禁用。可以到config目录，修改配置项 %s，打开此功能。';
