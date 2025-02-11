#!/bin/bash

set -e  # 遇到错误立即退出
set -u  # 使用未定义的变量时报错

rootDir="$(dirname "$(dirname "$(realpath "$0")")")"
input_file="${rootDir}/db/zentao.sql"
output_file="${rootDir}/config/zentaouniqueindexes.php"

# 检查输入文件是否存在
if [ ! -f "${input_file}" ]; then
    echo "Error: Input file ${input_file} not found!" >&2
    exit 1
fi

# 检查输入文件是否可读
if [ ! -r "${input_file}" ]; then
    echo "Error: Cannot read input file ${input_file}!" >&2
    exit 1
fi

if [ -f "${output_file}" ]; then
    rm "${output_file}" || {
        echo "Error: Failed to remove existing output file!" >&2
        exit 1
    }
fi

# 提取索引并转换为PHP数组格式,通过管道排序,添加逗号分隔符,并删除空行
(
echo "<?php"
echo "\$config->uniqueIndexes = ["
grep -oP 'CREATE UNIQUE INDEX[^;]+;' "${input_file}" | while read -r line; do
    table="$(echo "${line}" | grep -oP 'ON `\K[^`]+' | sed "s/zt_/TABLE_/" | tr '[:lower:]' '[:upper:]')"
    array_items="$(echo "${line}" | grep -oP '\(\K[^)]+' | sed "s/\`,\`/\`, \`/g" | sed "s/\`/\'/g")"
    echo "    ${table} => [${array_items}]"
done | sort | sed '$!s/$/,/'
echo "];"
) | tr -s '\n' > "${output_file}" || {
    echo "Error: Failed to write to output file!" >&2
    exit 1
}

echo "Successfully generated ${output_file}"
