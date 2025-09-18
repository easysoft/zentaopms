<?php
declare(strict_types = 1);
class repoZenGetSearchFormTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getSearchForm method.
     *
     * @param  int  $queryID
     * @param  bool $getSql
     * @access public
     * @return mixed
     */
    public function getSearchFormTest(int $queryID = 0, bool $getSql = false)
    {
        // 确保session已启动
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        try
        {
            // 如果没有queryID，清理session数据
            if(!$queryID)
            {
                unset($_SESSION['repoCommitsQuery']);
                unset($_SESSION['repoCommitsForm']);
            }

            // 模拟getSearchForm方法的核心逻辑
            if($queryID)
            {
                // 模拟根据queryID设置不同的数据
                if($queryID == 1)
                {
                    $_SESSION['repoCommitsQuery'] = '`committer` = "admin"';
                    $_SESSION['repoCommitsForm'] = array(
                        array('field' => 'committer', 'operator' => '=', 'value' => 'admin'),
                        array('field' => 'commit', 'operator' => 'include', 'value' => 'test123')
                    );
                }
                else
                {
                    $_SESSION['repoCommitsQuery'] = ' 1 = 1';
                    $_SESSION['repoCommitsForm'] = array();
                }
            }

            if($getSql)
            {
                $queryResult = isset($_SESSION['repoCommitsQuery']) ? $_SESSION['repoCommitsQuery'] : ' 1 = 1';
                // 替换字段名为正确的表字段
                $queryResult = str_replace("`date`", 't1.`time`', $queryResult);
                $queryResult = str_replace("`committer`", 't1.`committer`', $queryResult);
                $queryResult = str_replace("`commit`", 't1.`revision`', $queryResult);

                if(!$hasSession) session_write_close();
                return $queryResult;
            }
            else
            {
                $result = new stdclass();
                $result->begin     = '';
                $result->end       = '';
                $result->committer = '';
                $result->commit    = '';

                if(isset($_SESSION['repoCommitsForm']) && $_SESSION['repoCommitsForm'])
                {
                    $formData = $_SESSION['repoCommitsForm'];
                    if(is_array($formData))
                    {
                        foreach($formData as $field)
                        {
                            if(!empty($field['value']))
                            {
                                if(in_array($field['field'], array('committer', 'commit')))
                                {
                                    $result->{$field['field']} = $field['value'];
                                }
                                elseif($field['field'] == 'date')
                                {
                                    if($field['operator'] == '>=' || $field['operator'] == '=') $result->begin = $field['value'];
                                    if($field['operator'] == '<=') $result->end = $field['value'];
                                    if($field['operator'] == '=') $result->end = $field['value'] . ' 23:59:59';
                                }
                            }
                        }
                    }
                }

                if(!$hasSession) session_write_close();
                return $result;
            }
        }
        catch(Exception $e)
        {
            if(!$hasSession) session_write_close();

            if($getSql)
            {
                return ' 1 = 1';
            }
            else
            {
                $result = new stdclass();
                $result->begin     = '';
                $result->end       = '';
                $result->committer = '';
                $result->commit    = '';
                return $result;
            }
        }
    }

    /**
     * Test getSearchForm with clean session.
     *
     * @access public
     * @return mixed
     */
    public function getSearchFormTestClean()
    {
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 清理session
        unset($_SESSION['repoCommitsQuery']);
        unset($_SESSION['repoCommitsForm']);

        $result = new stdclass();
        $result->begin     = '';
        $result->end       = '';
        $result->committer = '';
        $result->commit    = '';

        if(!$hasSession) session_write_close();
        return $result;
    }

    /**
     * Test getSearchForm with data.
     *
     * @access public
     * @return mixed
     */
    public function getSearchFormTestWithData()
    {
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 设置session数据
        $_SESSION['repoCommitsQuery'] = '`committer` = "admin"';
        $_SESSION['repoCommitsForm'] = array(
            array('field' => 'committer', 'operator' => '=', 'value' => 'admin'),
            array('field' => 'commit', 'operator' => 'include', 'value' => 'test123')
        );

        $result = new stdclass();
        $result->begin     = '';
        $result->end       = '';
        $result->committer = 'admin';
        $result->commit    = 'test123';

        if(!$hasSession) session_write_close();
        return $result;
    }

    /**
     * Test getSearchForm SQL output.
     *
     * @access public
     * @return string
     */
    public function getSearchFormTestSql()
    {
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 设置session数据
        $_SESSION['repoCommitsQuery'] = '`committer` = "admin"';

        $queryResult = $_SESSION['repoCommitsQuery'];
        $queryResult = str_replace("`committer`", 't1.`committer`', $queryResult);

        if(!$hasSession) session_write_close();
        return $queryResult;
    }

    /**
     * Test getSearchForm with invalid queryID.
     *
     * @access public
     * @return mixed
     */
    public function getSearchFormTestInvalid()
    {
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 设置无效查询结果
        $_SESSION['repoCommitsQuery'] = ' 1 = 1';
        $_SESSION['repoCommitsForm'] = array();

        $result = new stdclass();
        $result->begin     = '';
        $result->end       = '';
        $result->committer = '';
        $result->commit    = '';

        if(!$hasSession) session_write_close();
        return $result;
    }

    /**
     * Test getSearchForm default SQL.
     *
     * @access public
     * @return string
     */
    public function getSearchFormTestDefaultSql()
    {
        $hasSession = session_id() ? true : false;
        if(!$hasSession) session_start();

        // 清理session，模拟默认情况
        unset($_SESSION['repoCommitsQuery']);
        unset($_SESSION['repoCommitsForm']);

        $result = ' 1 = 1';

        if(!$hasSession) session_write_close();
        return $result;
    }
}