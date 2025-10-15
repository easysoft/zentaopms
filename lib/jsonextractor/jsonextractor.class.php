<?php
/**
 * JSON字段提取器
 * Extract json data by fields with type conversion.
 *
 * 语法规则说明：
 * - 简单字段：field1,field2
 * - 嵌套字段：parent:{child1,child2}
 * - 数组索引：users[0]:{name,email}, users[*]:{name}
 * - 字段别名：field|alias, parent|parentAlias:{child|childAlias}
 * - 类型转换：field(类型)|alias，支持类型：array/int/float/bool/string
 *   示例：user(array)（map转数组）、age(int)|用户年龄（字符串转整数）
 */
class jsonextractor
{
    /**
     * 错误信息存储
     * @var string[]
     */
    private $errors = array();

    /**
     * 从JSON中提取字段并应用类型转换
     * @param string $json JSON字符串
     * @param string $query 查询字符串（支持类型转换语法）
     * @return string|false 提取后的JSON字符串，失败返回false
     */
    public function extract($json, $query)
    {
        $this->errors = array();

        /* 解析原始JSON */
        $data = json_decode($json, true);
        if(json_last_error() !== JSON_ERROR_NONE)
        {
            $this->errors[] = "JSON解析错误: " . json_last_error_msg();
            return false;
        }

        /* 解析查询语句结构 */
        $queryStructure = $this->parseQuery($query);
        if($queryStructure === false) return false;

        /* 提取字段并应用类型转换 */
        $result = $this->extractFields($data, $queryStructure);

        /* 返回格式化JSON */
        return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * 解析查询字符串，生成查询结构数组
     * @param string $query 查询字符串
     * @return array|bool 查询结构数组，失败返回false
     */
    private function parseQuery($query)
    {
        $query = trim($query);

        if (empty($query)) {
            $this->errors[] = "empty";
            return false;
        }

        try
        {
            return $this->parseExpression($query, 0)[0];
        }
        catch(Exception $e)
        {
            $this->errors[] = "error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * 递归解析表达式（处理嵌套结构）
     * @param string $query 查询字符串
     * @param int $start 起始解析位置
     * @return array [查询结构数组, 下一个解析位置]
     * @throws Exception 语法错误时抛出异常
     */
    private function parseExpression($query, $start)
    {
        $result = [];
        $i = $start;
        $len = strlen($query);

        while($i < $len)
        {
            /* 跳过空白字符 */
            while($i < $len && in_array($query[$i], [' ', "\t", "\n", "\r"])) $i++;

            if($i >= $len) break;

            /* 遇到闭合符，退出当前嵌套层级 */
            if($query[$i] === '}') break;

            /* 解析字段名、类型、别名、数组索引 */
            $fieldInfo = $this->parseFieldName($query, $i);
            $fieldName = $fieldInfo['name'];
            $fieldAlias = $fieldInfo['alias'];
            $arrayIndex = $fieldInfo['index'];
            $targetType = $fieldInfo['target_type'];
            $i = $fieldInfo['pos'];

            /* 跳过空白字符 */
            while($i < $len && in_array($query[$i], [' ', "\t", "\n", "\r"])) $i++;

            /* 处理嵌套结构（如 parent:{child}） */
            if($i < $len && $query[$i] === ':')
            {
                $i++; // 跳过冒号

                /* 跳过空白字符 */
                while($i < $len && in_array($query[$i], [' ', "\t", "\n", "\r"])) $i++;

                if($i < $len && $query[$i] === '{')
                {
                    $i++; // 跳过左大括号

                    /* 递归解析嵌套字段 */
                    list($nestedResult, $newPos) = $this->parseExpression($query, $i);
                    $i = $newPos;

                    /* 检查是否有闭合符 */
                    if($i < $len && $query[$i] === '}')
                    {
                        $i++; // 跳过右大括号
                    }
                    else
                    {
                        throw new Exception("缺少闭合符 '}'");
                    }

                    /* 存储嵌套字段结构（包含类型转换信息）*/
                    $result[$fieldName] = array(
                        'type' => 'nested',
                        'alias' => $fieldAlias,
                        'index' => $arrayIndex,
                        'target_type' => $targetType,
                        'fields' => $nestedResult
                    );
                }
                else
                {
                    throw new Exception("'{' afer ':'");
                }
            }
            else
            {
                /* 存储简单字段结构（包含类型转换信息）*/
                $result[$fieldName] = array(
                    'type' => 'simple',
                    'alias' => $fieldAlias,
                    'index' => $arrayIndex,
                    'target_type' => $targetType
                );
            }

            /* 跳过空白字符 */
            while($i < $len && in_array($query[$i], [' ', "\t", "\n", "\r"])) $i++;

            /* 处理字段分隔符 */
            if($i < $len && $query[$i] === ',') $i++; // 跳过逗号
        }

        return [$result, $i];
    }

    /**
     * 解析字段名、类型转换、别名、数组索引
     * @param string $query 查询字符串
     * @param int $start 起始解析位置
     * @return array 包含字段信息的数组
     * @throws Exception 语法错误时抛出异常
     */
    private function parseFieldName($query, $start)
    {
        $i = $start;
        $len = strlen($query);
        $name = '';
        $alias = null;
        $index = null;
        $targetType = null;

        /* 1. 读取字段名（可能包含类型转换，如 user(array)）*/
        while($i < $len && !in_array($query[$i], [':', ',', '}', '[', '|', ' ', "\t", "\n", "\r"]))
        {
            $name .= $query[$i];
            $i++;
        }

        /* 2. 解析类型转换（格式：字段名(类型)）*/
        $typePattern = '/^(.+)\((\w+)\)$/';
        if(preg_match($typePattern, $name, $matches))
        {
            $name = trim($matches[1]);
            $targetType = trim($matches[2]);

            /* 验证支持的类型 */
            $allowedTypes = array('array', 'int', 'float', 'bool', 'string');
            if(!in_array($targetType, $allowedTypes))
            {
                throw new Exception("Not support '{$targetType}', only " . implode(',', $allowedTypes));
            }
        }

        /* 3. 解析别名（格式：字段名|别名）*/
        if($i < $len && $query[$i] === '|')
        {
            $i++; // 跳过竖线

            $alias = '';
            while($i < $len && !in_array($query[$i], [':', ',', '}', '[', ' ', "\t", "\n", "\r"]))
            {
                $alias .= $query[$i];
                $i++;
            }
            $alias = trim($alias);

            /* 别名不能为空 */
            if(empty($alias)) throw new Exception("alias is empty");
        }

        /* 4. 解析数组索引（格式：字段名[索引]）*/
        if($i < $len && $query[$i] === '[')
        {
            $i++; // 跳过左中括号

            $indexStr = '';
            while($i < $len && $query[$i] !== ']')
            {
                $indexStr .= $query[$i];
                $i++;
            }

            /* 检查是否有闭合符 */
            if($i < $len && $query[$i] === ']')
            {
                $i++; // 跳过右中括号

                /* 处理通配符索引和数字索引 */
                if($indexStr === '*')
                {
                    $index = '*';
                }
                elseif(is_numeric($indexStr))
                {
                    $index = intval($indexStr);
                }
                else
                {
                    throw new Exception("index error '{$indexStr}', only support number or '*'");
                }
            }
            else
            {
                throw new Exception("need ']'");
            }
        }

        /* 字段名不能为空 */
        if(empty($name)) throw new Exception("field is empty");

        return array(
            'name'        => $name,
            'alias'       => $alias,
            'index'       => $index,
            'target_type' => $targetType,
            'pos'         => $i
        );
    }

    /**
     * 提取字段并应用类型转换
     * @param array|object $data 原始数据（数组或对象）
     * @param array $queryStructure 查询结构数组
     * @return array 提取并转换后的结果数组
     */
    private function extractFields($data, $queryStructure)
    {
        /* 非数组结构直接返回（避免后续报错）*/
        if(!is_array($queryStructure)) return $this->convertValueType($data, null);
        if(!is_array($data) && !is_object($data)) return null;

        $result = array();
        $dataArray = (array)$data;

        foreach($queryStructure as $field => $config)
        {
            $resultKey = $config['alias'] ?? $field; // 优先使用别名
            $targetType = $config['target_type'] ?? null; // 目标转换类型

            /* 1. 处理通配符字段（如 *:{name}）*/
            if($field === '*')
            {
                foreach($dataArray as $key => $value)
                {
                    if($config['type'] === 'simple')
                    {
                        /* 简单通配符字段：直接转换值 */
                        $result[$key] = $this->convertValueType($value, $targetType);
                    }
                    elseif($config['type'] === 'nested')
                    {
                        /* 嵌套通配符字段：先提取嵌套字段，再转换 */
                        $nestedValue = $this->extractFields($value, $config['fields']);
                        $result[$key] = $this->convertValueType($nestedValue, $targetType);
                    }
                }
                continue;
            }

            /* 字段不存在时跳过 */
            if(!array_key_exists($field, $dataArray)) continue;
            $fieldData = $dataArray[$field];

            /* 2. 处理数组索引（如 users[0]、users[*]）*/
            if($config['index'] !== null)
            {
                /* 非数组类型跳过 */
                if(!is_array($fieldData)) continue;

                /* 2.1 通配符索引（提取所有数组元素）*/
                if($config['index'] === '*')
                {
                    $extractedArray = array();
                    foreach($fieldData as $item)
                    {
                        /* 提取元素值（简单/嵌套）*/
                        $itemValue = $config['type'] === 'simple'
                            ? $item
                            : $this->extractFields($item, $config['fields']);
                        /* 应用类型转换 */
                        $extractedArray[] = $this->convertValueType($itemValue, $targetType);
                    }
                    $result[$resultKey] = $extractedArray;
                }
                /* 2.2 特定数字索引（提取指定位置元素）*/
                elseif(isset($fieldData[$config['index']]))
                {
                    $item = $fieldData[$config['index']];
                    /* 提取元素值（简单/嵌套）*/
                    $itemValue = $config['type'] === 'simple'
                        ? $item
                        : $this->extractFields($item, $config['fields']);
                    /* 应用类型转换并保留索引*/
                    $result[$resultKey] = array(
                        $config['index'] => $this->convertValueType($itemValue, $targetType)
                    );
                }
            }
            /* 3. 无数组索引（普通字段）*/
            else
            {
                /* 提取字段值（简单/嵌套）*/
                $fieldValue = $config['type'] === 'simple'
                    ? $fieldData
                    : $this->extractFields($fieldData, $config['fields']);
                /* 应用类型转换 */
                $result[$resultKey] = $this->convertValueType($fieldValue, $targetType);
            }
        }

        return $result;
    }

    /**
     * 按目标类型转换值
     * @param mixed $value 原始值
     * @param string|null $targetType 目标类型（array/int/float/bool/string）
     * @return mixed 转换后的值
     */
    private function convertValueType($value, ?string $targetType)
    {
        /* 无目标类型时返回原始值 */
        if($targetType === null) return $value;

        switch($targetType)
        {
            /* 转换为数组：关联数组→索引数组，非数组→包装为数组 */
            case 'array':
                if(is_array($value)) return array_values($value);
                return [$value];

            /* 转换为整数：仅对数值/数值字符串有效 */
            case 'int':
                return is_numeric($value) ? intval($value) : $value;

            /* 转换为浮点数：仅对数值/数值字符串有效 */
            case 'float':
                return is_numeric($value) ? floatval($value) : $value;

            /* 转换为布尔值：处理字符串"true"/"false"和数字1/0 */
            case 'bool':
                if(is_string($value))
                {
                    $lowerValue = strtolower($value);
                    return $lowerValue === 'true' || $lowerValue === '1';
                }
                return boolval($value);

            /* 转换为字符串：直接强制转换 */
            case 'string':
                return strval($value);

            /* 未知类型返回原始值 */
            default:
                return $value;
        }
    }

    /**
     * 获取错误信息列表
     * @return string[] 错误信息数组
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
