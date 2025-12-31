CREATE OR REPLACE FUNCTION FIND_IN_SET
        (
                piv_str1 varchar2,
                piv_str2 varchar2,
                p_sep    varchar2 := ',')
        RETURN NUMBER
                    IS
        l_idx     number:=0;                 -- 用于计算piv_str2中分隔符的位置
        str       varchar2(500);             -- 根据分隔符截取的子字符串
        piv_str   varchar2(500) := piv_str2; -- 将piv_str2赋值给piv_str
        res       number        :=0;         -- 返回结果
        loopIndex number        :=0;
BEGIN
        -- 如果piv_str中没有分割符，直接判断piv_str1和piv_str是否相等，相等 res=1
        IF instr(piv_str, p_sep, 1) = 0 THEN
                IF piv_str          = piv_str1 THEN
                        res        := 1;
                END IF;
        ELSE
                -- 循环按分隔符截取piv_str
                LOOP
                        l_idx    := instr(piv_str, p_sep);
                        loopIndex:=loopIndex+1;
                        -- 当piv_str中还有分隔符时
                        IF l_idx > 0 THEN
                                    -- 截取第一个分隔符前的字段str
                                str:= substr(piv_str, 1, l_idx-1);
                                -- 判断 str 和piv_str1 是否相等，相等 res=1 并结束循环判断
                                IF str      = piv_str1 THEN
                                        res:= loopIndex;
                                        EXIT;
                                END IF;
                                piv_str := substr(piv_str, l_idx+length(p_sep));
                        ELSE
                                -- 当截取后的piv_str 中不存在分割符时，判断piv_str和piv_str1是否相等，相等 res=1
                                IF piv_str  = piv_str1 THEN
                                        res:= loopIndex;
                                END IF;
                                -- 无论最后是否相等，都跳出循环
                                EXIT;
                        END IF;
                END LOOP;
                -- 结束循环
        END IF;
        -- 返回res
        RETURN res;
END FIND_IN_SET;
/

CREATE OR REPLACE FUNCTION "IF"(
    p_condition  BOOLEAN,        -- 判断条件
    p_true_val   ANYTYPE,        -- true分支返回值（任意类型）
    p_false_val  ANYTYPE         -- false分支返回值（任意类型）
) RETURNS ANYTYPE
AS
BEGIN
    -- 仅校验条件非空，不校验类型
    IF p_condition IS NULL THEN
        RAISE_APPLICATION_ERROR(-20001, '判断条件不能为NULL');
    END IF;

    -- 核心逻辑：直接返回不同类型值
    IF p_condition THEN
        RETURN p_true_val;       -- 如：字符串
    ELSE
        RETURN p_false_val;      -- 如：数值
    END IF;
END;
/
