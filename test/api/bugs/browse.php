#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 获取bug列表
cid=1
pid=1

 >> /bugs/
创建最少字段的Bug >> product required.
创建最少字段的Bug >> title required.
创建最少字段的Bug >> Bug1
创建最多字段的Bug >> Bug2
创建不存在产品的Bug >> product does not exist.
 >> Bug2
 >> Bug1
 >> Bug1
 >> Bug1
 >> Bug3
 >> Bug3
 >> true

*/

/**
 * Class bugTester
 *
 * @uses apiTester
 * @package api
 * @version $id$
 */
class bugTester extends apiTester
{
    /**
     * Get bug.
     *
     * @param  int $bugID
     * @access public
     * @return object
     */
    function get($bugID)
    {
        return $this->rest->get("/bugs/$bugID");
    }

    /**
     * Get bugs.
     *
     * @param int    $productID
     * @param string $order
     * @param int    $page
     * @param int    $limit
     * @access public
     * @return object
     */
    function getList($productID, $order = '', $page = 0, $limit = 0)
    {
        $vars = array();
        if($order) $vars['order'] = $order;
        if($page)  $vars['page']  = $page;
        if($limit) $vars['limit'] = $limit;
        $vars = empty($vars) ? '' : '?' . http_build_query($vars);

        return $this->rest->get("/products/$productID$vars");
    }

    /**
     * Create bug.
     *
     * @param  array  $data
     * @access public
     * @return object
     */
    function create($data)
    {
        return $this->rest->post("/bugs", $data);
    }

    /**
     * Delete bug.
     *
     * @param  int $bugID
     * @access public
     * @return object
     */
    function delete($bugID)
    {
        return $this->rest->delete("/bugs/$bugID");
    }
}

/* Users. */
$admin          = new bugTester('admin');
$noProduct1User = new bugTester('noProduct1User');

/* Test post.*/
$noProductBug    = array('title' => 'Bug1');
$noTitleBug      = array('product' => 1);
$minFieldBug     = array('product' => 1, 'title' => 'Bug1');
$maxFieldBug     = array('product' => 1, 'title' => 'Bug2', 'build' => 0);
$wrongProductBug = array('product' => 99999, 'title' => 'Bug1');
$normalBug       = array('product' => 1, 'title' => 'Bug2');

$fields = 'bug:title';
r($admin->create($noProductBug))    && c(400) && p('error') && e('product required.'); // 创建最少字段的Bug
r($admin->create($noTitleBug))      && c(400) && p('error') && e('title required.'); // 创建最少字段的Bug
r($admin->create($minFieldBug))     && c(200) && p($fields) && e('Bug1'); // 创建最少字段的Bug
r($admin->create($maxFieldBug))     && c(200) && p($fields) && e('Bug2'); // 创建最多字段的Bug
r($admin->create($wrongProductBug)) && c(400) && p('error') && e('product does not exist.'); // 创建不存在产品的Bug

/* Test get. */
$bug = $admin->create($normalBug);
r($admin->get($bug->id))          && c(200) && p($fields) && e('Bug2');
r($admin->get(9999999))           && c(400) && p('error') && e('');
r($noProduct1User->get($bug->id)) && c(403) && p('error') && e('');

r($admin->getList(1))                   && c(200) && p('0:title') && e('Bug1');
r($admin->getList(1), 'id_desc')        && c(200) && p('0:title') && e('Bug1');
r($admin->getList(1), 'id_desc', 2, 10) && c(200) && p('0:title') && e('Bug1');

/* Test put. */
$modifyBug1 = array('product' => 1, 'title' => 'Bug3');
$modifyBug2 = array('product' => 1, 'title' => 'Bug3');

r($admin->put($modifyBug1)) && c(200) && p($fields) && e('Bug3');
r($admin->put($modifyBug2)) && c(200) && p($fields) && e('Bug3');

/* Test delete*/
r($admin->delete($bug->id)) && c(201) && p() && e('');
r($admin->get($bug->id))    && c(200) && p('bug:deleted') && e('true');
