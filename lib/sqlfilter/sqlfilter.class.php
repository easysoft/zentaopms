<?php
/**
 * sqlfilter - 多语言SQL注入防护过滤器
 *
 * 设计目标：
 * 1. 支持多语言字符（中文、阿拉伯文、俄文、韩文等）
 * 2. 防止SQL注入攻击
 * 3. 提供不同安全级别的过滤选项
 */
class sqlfilter
{
    /**
     * 方案1：基于危险字符和模式的过滤（推荐方案）
     *
     * 核心思路：
     * - 保留所有正常的语言字符
     * - 只移除明确的SQL注入威胁
     * - 多层防护确保安全性
     *
     * @param string $input 用户输入
     * @return string 过滤后的安全字符串
     */
    public function filterForSQL($input)
    {
        // 步骤1：基础验证
        if(!is_string($input) || empty(trim($input))) return '';

        // 步骤2：移除控制字符（但保留正常的空白字符）
        $filtered = $this->removeControlCharacters($input);

        // 步骤3：移除SQL注入攻击模式
        $filtered = $this->removeSQLInjectionPatterns($filtered);

        // 步骤4：处理危险的SQL字符组合
        $filtered = $this->removeDangerousSQLChars($filtered);

        // 步骤5：去除首尾的空白字符
        $filtered = trim($filtered);

        return $filtered;
    }

    /**
     * 方案2：Unicode属性过滤 + SQL危险字符过滤
     *
     * 适用场景：
     * - 需要更宽松的字符支持
     * - 用户可能输入各种标点符号
     * - 对性能要求不是特别高
     *
     * @param string $input 用户输入
     * @return string 过滤后的字符串
     */
    public function filterUnicodeForSQL($input)
    {
        if(!is_string($input) || empty(trim($input))) return '';

        // 步骤1：使用Unicode属性类保留语言字符和基本标点
        // \p{L} = 所有语言的字母
        // \p{N} = 所有数字
        // \p{M} = 标记字符（如重音符号）
        // \s = 空白字符
        // \.\,\!\?\-\_\(\) = 常用标点符号
        $filtered = preg_replace('/[^\p{L}\p{N}\p{M}\s\.\,\!\?\-\_\(\)]/u', ' ', $input);

        // 步骤2：移除SQL注入模式
        $filtered = $this->removeSQLInjectionPatterns($filtered);

        // 步骤3：标准化空格
        $filtered = preg_replace('/\s+/', ' ', $filtered);

        // 步骤4：去除首尾的空白字符
        $filtered = trim($filtered);

        return $filtered;
    }

    /**
     * 方案3：严格的SQL安全过滤
     *
     * 适用场景：
     * - 高安全要求的环境
     * - 可以接受某些特殊字符被过滤
     * - 关键数据操作
     *
     * @param string $input 用户输入
     * @return string 过滤后的字符串
     */
    public function filterStrictSQL($input)
    {
        if(!is_string($input) || empty(trim($input))) return '';

        // 步骤1：只保留字母、数字、最基本的标点
        $filtered = preg_replace('/[^\p{L}\p{N}\s\.\,\!\?\-\_]/u', '', $input);

        // 步骤2：移除SQL注入模式
        $filtered = $this->removeSQLInjectionPatterns($filtered);

        // 步骤3：去除首尾的空白字符
        $filtered = trim($filtered);

        return $filtered;
    }

    /**
     * 移除控制字符（但保留正常的空白字符）
     *
     * 控制字符可能被用于绕过安全检查或造成解析错误
     *
     * @param string $input 输入字符串
     * @return string 处理后的字符串
     */
    private function removeControlCharacters($input)
    {
        // 移除C0和C1控制字符
        // 但保留：制表符(\t)、换行符(\n)、回车符(\r)
        // \x00-\x08: NULL到BACKSPACE
        // \x0B: 垂直制表符
        // \x0C: 换页符
        // \x0E-\x1F: 其他C0控制字符
        // \x7F-\x9F: DEL和C1控制字符
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F]/u', '', $input);
    }

    /**
     * 移除明显的SQL注入攻击模式
     *
     * 这是防护的核心部分，识别并移除常见的SQL注入技术
     *
     * @param string $input 输入字符串
     * @return string 处理后的字符串
     */
    private function removeSQLInjectionPatterns($input)
    {
        $dangerousPatterns = [
            // === SQL注释攻击 ===
            '/--[\s\S]*$/i',        // 单行注释：-- comment
            '/\/\*[\s\S]*?\*\//i',  // 块注释：/* comment */
            '/#.*$/m',              // MySQL特有的#注释

            // === 联合查询注入 ===
            '/\b(union\s+select)\b/i',     // UNION SELECT
            '/\b(union\s+all\s+select)\b/i', // UNION ALL SELECT

            // === 数据操作注入 ===
            '/\b(drop\s+table)\b/i',       // DROP TABLE
            '/\b(drop\s+database)\b/i',    // DROP DATABASE
            '/\b(delete\s+from)\b/i',      // DELETE FROM
            '/\b(insert\s+into)\b/i',      // INSERT INTO
            '/\b(update\s+.*\s+set)\b/i',  // UPDATE ... SET
            '/\b(alter\s+table)\b/i',      // ALTER TABLE
            '/\b(create\s+table)\b/i',     // CREATE TABLE
            '/\b(truncate\s+table)\b/i',   // TRUNCATE TABLE

            // === 存储过程和函数执行 ===
            '/\b(exec\s*\()\b/i',          // EXEC()
            '/\b(execute\s*\()\b/i',       // EXECUTE()
            '/\b(sp_executesql)\b/i',      // SQL Server存储过程
            '/\b(xp_cmdshell)\b/i',        // SQL Server命令执行

            // === 文件操作注入 ===
            '/\b(load_file)\b/i',          // MySQL LOAD_FILE()
            '/\b(into\s+outfile)\b/i',     // INTO OUTFILE
            '/\b(into\s+dumpfile)\b/i',    // INTO DUMPFILE

            // === 编码绕过尝试 ===
            '/\\\\x[0-9a-f]{2}/i',         // 十六进制编码 \x41
            '/\\\\[0-7]{1,3}/i',           // 八进制编码 \101
            '/%[0-9a-f]{2}/i',             // URL编码 %41

            // === 其他危险模式 ===
            '/\b(information_schema)\b/i',  // 信息模式数据库
            '/\b(mysql\.user)\b/i',        // MySQL用户表
            '/\b(pg_sleep)\b/i',           // PostgreSQL延时函数
            '/\b(waitfor\s+delay)\b/i',    // SQL Server延时
        ];

        // 逐个应用模式，将匹配内容替换为空格
        foreach($dangerousPatterns as $pattern) $input = preg_replace($pattern, ' ', $input);

        return $input;
    }

    /**
     * 移除危险的SQL字符组合
     *
     * 处理单个字符可能无害，但组合起来就危险的情况
     *
     * @param string $input 输入字符串
     * @return string 处理后的字符串
     */
    private function removeDangerousSQLChars($input)
    {
        $replacements = [
            // === 引号相关 ===
            "/'+/i" => "",              // 多个单引号：'''
            '/"+/i' => "",              // 多个双引号："""
            "/'+\s*or\s*'+/i" => " ",   // 引号包围的OR：'or'
            "/'+\s*and\s*'+/i" => " ",  // 引号包围的AND：'and'

            // === SQL语法字符 ===
            '/;+/' => " ",              // 分号（语句分隔符）
            '/\|\|/' => " ",            // SQL字符串连接符 ||
            '/`+/' => "",               // 反引号（MySQL标识符）

            // === 注释字符组合 ===
            '/--+/' => " ",             // 多个连字符
            '/\/\*|\*\//' => " ",       // 注释开始/结束符

            // === 危险操作符 ===
            '/\s*=\s*[\'"]?\s*[\'"]?\s*/i' => " ", // 可能的等号注入
            '/\s+(or|and)\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+[\'"]?/i' => " ", // 1=1类型注入
        ];

        foreach($replacements as $pattern => $replacement) $input = preg_replace($pattern, $replacement, $input);

        return $input;
    }

    /**
     * 验证过滤后的字符串是否仍包含危险特征
     *
     * 作为最后的安全检查
     *
     * @param string $input 已过滤的字符串
     * @return bool true表示安全，false表示仍有风险
     */
    public function validateSafety($input)
    {
        // 危险特征检查
        $dangerousPatterns = [
            '/[\'"`;]/i',  // 引号和分号
            '/\b(select|union|drop|delete|insert|update|alter|create|exec)\b/i', // SQL关键词
            '/--|\*\/|\/\*/',  // 注释符
            '/\|\|/',          // 字符串连接
            '/\bor\s+\d+\s*=\s*\d+/i', // 1=1类型条件
        ];

        foreach($dangerousPatterns as $pattern)
        {
            if(preg_match($pattern, $input)) return false; // 发现危险特征
        }

        return true; // 通过安全检查
    }

    /**
     * 根据安全级别选择合适的过滤方法
     *
     * @param string $input 用户输入
     * @param string $securityLevel 安全级别：'high', 'medium', 'low'
     * @return string 过滤后的字符串
     */
    public function getRecommendedFilter($input, $securityLevel = 'medium')
    {
        switch($securityLevel)
        {
            case 'high':
                // 高安全级别：最严格的过滤
                return $this->filterStrictSQL($input);

            case 'low':
                // 低安全级别：更宽松，保留更多字符
                return $this->filterUnicodeForSQL($input);

            case 'medium':
            default:
                // 中等安全级别：平衡安全性和可用性
                return $this->filterForSQL($input);
        }
    }

    /**
     * 批量过滤多个输入
     *
     * @param array $inputs 输入数组
     * @param string $securityLevel 安全级别
     * @return array 过滤后的数组
     */
    public function filterMultiple($inputs, $securityLevel = 'medium')
    {
        $filtered = [];
        foreach($inputs as $key => $input) $filtered[$key] = $this->getRecommendedFilter($input, $securityLevel);
        return $filtered;
    }

    /**
     * 生成过滤报告（调试用）
     *
     * @param string $original 原始输入
     * @param string $filtered 过滤后结果
     * @return array 详细的过滤报告
     */
    public function generateFilterReport($original, $filtered)
    {
        return [
            'original' => $original,
            'filtered' => $filtered,
            'length_change' => strlen($original) - strlen($filtered),
            'removed_chars' => $this->getRemovedChars($original, $filtered),
            'safety_check' => $this->validateSafety($filtered),
            'potential_threats' => $this->identifyThreats($original)
        ];
    }

    /**
     * 识别输入中的潜在威胁（调试和日志记录用）
     *
     * @param string $input 输入字符串
     * @return array 发现的威胁列表
     */
    private function identifyThreats($input)
    {
        $threats = [];

        $threatPatterns = [
            'sql_injection' => '/\b(union|select|drop|delete|insert|update)\b/i',
            'comment_attack' => '/--|\*\/|\/\*/',
            'quote_manipulation' => '/[\'"]{2,}/',
            'statement_separator' => '/;/',
            'encoding_bypass' => '/\\\\x[0-9a-f]{2}|%[0-9a-f]{2}/i'
        ];

        foreach($threatPatterns as $threat => $pattern)
        {
            if(preg_match_all($pattern, $input, $matches)) $threats[$threat] = $matches[0];
        }

        return $threats;
    }

    /**
     * 计算被移除的字符
     *
     * @param string $original 原始字符串
     * @param string $filtered 过滤后字符串
     * @return string 被移除的字符
     */
    private function getRemovedChars($original, $filtered)
    {
        $originalChars = mb_str_split($original, 1, 'UTF-8');
        $filteredChars = mb_str_split($filtered, 1, 'UTF-8');

        // 简化的差异检测
        $diff = array_diff($originalChars, $filteredChars);
        return implode('', array_unique($diff));
    }
}
