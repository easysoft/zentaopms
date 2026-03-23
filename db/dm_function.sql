CREATE OR REPLACE FUNCTION FIND_IN_SET
        (
                piv_str1 varchar2,
                piv_str2 varchar2,
                p_sep    varchar2 := ',')
        RETURN NUMBER
                    IS
        l_idx     number:=0;
        str       varchar2(500);
        piv_str   varchar2(500) := piv_str2;
        res       number        :=0;
        loopIndex number        :=0;
BEGIN
        IF instr(piv_str, p_sep, 1) = 0 THEN
                IF piv_str          = piv_str1 THEN
                        res        := 1;
                END IF;
        ELSE
                LOOP
                        l_idx    := instr(piv_str, p_sep);
                        loopIndex:=loopIndex+1;
                        IF l_idx > 0 THEN
                                str:= substr(piv_str, 1, l_idx-1);
                                IF str      = piv_str1 THEN
                                        res:= loopIndex;
                                        EXIT;
                                END IF;
                                piv_str := substr(piv_str, l_idx+length(p_sep));
                        ELSE
                                IF piv_str  = piv_str1 THEN
                                        res:= loopIndex;
                                END IF;
                                EXIT;
                        END IF;
                END LOOP;
        END IF;
        RETURN res;
END FIND_IN_SET;

--

CREATE OR REPLACE FUNCTION "IF"(
    p_condition  BOOLEAN,
    p_true_val   ANYTYPE,
    p_false_val  ANYTYPE
) RETURN ANYTYPE
AS
BEGIN
    IF p_condition IS NULL THEN
        RAISE_APPLICATION_ERROR(-20001, '判断条件不能为NULL');
    END IF;

    IF p_condition THEN
        RETURN p_true_val;
    ELSE
        RETURN p_false_val;
    END IF;
END;
