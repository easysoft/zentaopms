1. 修改zentaophp中的version number，打tag。
2. 修改zentaoms中的version
    config.php中的version.
    install中的version。
3. 修改升级程序。(版本列表。)
4. 打包zentaoms。
5. 合并目录。
6. 修改www/index.php中的包含路径。
7. 导出新的数据库。 grep -v '\-\-' /mnt/c/zentao.sql  |grep -v ^$ |sed "s/DROP/\-\- DROP/" >zentao.sql
8. zip包。
9. windows包。
10. 上传文件。
11. 撰写升级声明。
