CREATE OR REPLACE FUNCTION FIND_IN_SET(
  target text,
  list text
) RETURNS boolean AS $$
DECLARE
  arr text[];
  i integer;
BEGIN
  IF list IS NULL OR list = '' THEN
    RETURN false;
  END IF;

  arr := string_to_array(list, ',');

  FOR i IN 1..array_length(arr, 1) LOOP
    IF arr[i] = target THEN
      RETURN true;
    END IF;
  END LOOP;

  RETURN false;
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION FIND_IN_SET(
  target integer,
  list text
) RETURNS boolean AS $$
BEGIN
  RETURN FIND_IN_SET(target::text, list);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION GROUP_CONCAT(
    input text,
    delimiter text = ','
) RETURNS text AS $$
BEGIN
    RETURN string_agg(input, delimiter);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

CREATE OR REPLACE FUNCTION GROUP_CONCAT(
    input numeric,
    delimiter text = ','
) RETURNS text AS $$
BEGIN
    RETURN string_agg(input::text, delimiter);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

CREATE OR REPLACE FUNCTION GROUP_CONCAT(
    input integer,
    delimiter text = ','
) RETURNS text AS $$
BEGIN
    RETURN string_agg(input::text, delimiter);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION ROUND(
    num float,
    decimals integer
) RETURNS numeric AS $$
BEGIN
    RETURN ROUND(num::numeric, decimals);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION ROUND(
    num double precision,
    decimals integer
) RETURNS numeric AS $$
BEGIN
    RETURN ROUND(num::numeric, decimals);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION ROUND(
    num text,
    decimals integer
) RETURNS numeric AS $$
BEGIN
    RETURN ROUND(num::numeric, decimals);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION TIMESTAMPDIFF(
    unit TEXT,
    start_time TIMESTAMP,
    end_time TIMESTAMP
) RETURNS INTEGER AS $$
DECLARE
    diff_interval INTERVAL;
    diff_seconds NUMERIC;
BEGIN
    diff_interval := end_time - start_time;

    CASE lower(unit)
        WHEN 'year' THEN
            RETURN EXTRACT(YEAR FROM end_time) - EXTRACT(YEAR FROM start_time)
                - CASE WHEN (EXTRACT(MONTH FROM end_time) < EXTRACT(MONTH FROM start_time))
                        OR (EXTRACT(MONTH FROM end_time) = EXTRACT(MONTH FROM start_time)
                            AND EXTRACT(DAY FROM end_time) < EXTRACT(DAY FROM start_time))
                      THEN 1 ELSE 0 END;
        WHEN 'month' THEN
            RETURN (EXTRACT(YEAR FROM end_time) - EXTRACT(YEAR FROM start_time)) * 12
                + (EXTRACT(MONTH FROM end_time) - EXTRACT(MONTH FROM start_time))
                - CASE WHEN EXTRACT(DAY FROM end_time) < EXTRACT(DAY FROM start_time)
                      THEN 1 ELSE 0 END;
        WHEN 'day' THEN
            RETURN EXTRACT(DAY FROM diff_interval);
        WHEN 'hour' THEN
            diff_seconds := EXTRACT(EPOCH FROM diff_interval);
            RETURN floor(diff_seconds / 3600);
        WHEN 'minute' THEN
            diff_seconds := EXTRACT(EPOCH FROM diff_interval);
            RETURN floor(diff_seconds / 60);
        WHEN 'second' THEN
            RETURN EXTRACT(EPOCH FROM diff_interval);
        ELSE
            RETURN NULL;
    END CASE;
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(bigint, integer)
RETURNS bigint AS $$
BEGIN
  RETURN COALESCE($1, $2::bigint);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(double precision, integer)
RETURNS double precision AS $$
BEGIN
  RETURN COALESCE($1, $2::double precision);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(integer, bigint)
RETURNS bigint AS $$
BEGIN
  RETURN COALESCE($1::bigint, $2);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(bigint, bigint)
RETURNS bigint AS $$
BEGIN
  RETURN COALESCE($1, $2);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(integer, integer)
RETURNS integer AS $$
BEGIN
  RETURN COALESCE($1, $2);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(numeric, numeric)
RETURNS numeric AS $$
BEGIN
  RETURN COALESCE($1, $2);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION IFNULL(text, text)
RETURNS text AS $$
BEGIN
  RETURN COALESCE($1, $2);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION day(date_val DATE)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(DAY FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION day(date_val TIMESTAMP)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(DAY FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION day(date_val TIMESTAMPTZ)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(DAY FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION MONTH(date_val DATE)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(MONTH FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION MONTH(date_val TIMESTAMP)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(MONTH FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION MONTH(date_val TIMESTAMPTZ)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(MONTH FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION YEAR(date_val DATE)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(YEAR FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION YEAR(date_val TIMESTAMP)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(YEAR FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION YEAR(date_val TIMESTAMPTZ)
RETURNS INTEGER AS $$ BEGIN RETURN EXTRACT(YEAR FROM date_val)::INTEGER; END; $$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION DATEDIFF(
    end_date ANYELEMENT,
    start_date ANYELEMENT
) RETURNS INTEGER AS $$
BEGIN
    RETURN (end_date - start_date)::INTEGER;
END;
$$ LANGUAGE plpgsql;
--

CREATE OR REPLACE FUNCTION IF(
    condition BOOLEAN,
    true_val BOOLEAN,
    false_val BOOLEAN
) RETURNS BOOLEAN AS $$
BEGIN
    IF condition THEN
        RETURN true_val;
    ELSE
        RETURN false_val;
    END IF;
END;
$$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION IF(
    condition BOOLEAN,
    true_val DOUBLE PRECISION,
    false_val INTEGER
) RETURNS DOUBLE PRECISION AS $$
BEGIN
    IF condition THEN
        RETURN true_val;
    ELSE
        RETURN false_val::DOUBLE PRECISION;
    END IF;
END;
$$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION IF(
    condition BOOLEAN,
    true_val TEXT,
    false_val DATE
) RETURNS DATE AS $$
BEGIN
    IF condition THEN
        RETURN true_val;
    ELSE
        RETURN false_val::DATE;
    END IF;
END;
$$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION IF(
    condition BOOLEAN,
    true_val DATE,
    false_val DATE
) RETURNS DATE AS $$
BEGIN
    IF condition THEN
        RETURN true_val;
    ELSE
        RETURN false_val;
    END IF;
END;
$$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION IF(
    condition BOOLEAN,
    true_val  timestamp without time zone,
    false_val DATE
) RETURNS DATE AS $$
BEGIN
    IF condition THEN
        RETURN true_val::DATE;
    ELSE
        RETURN false_val;
    END IF;
END;
$$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION IF(
    condition BOOLEAN,
    true_val  timestamp without time zone,
    false_val timestamp without time zone
) RETURNS DATE AS $$
BEGIN
    IF condition THEN
        RETURN true_val;
    ELSE
        RETURN false_val;
    END IF;
END;
$$ LANGUAGE plpgsql;

--

CREATE OR REPLACE FUNCTION DATE_FORMAT(
    date_val DATE,
    format_str TEXT
) RETURNS TEXT AS $$
DECLARE
    pg_format TEXT;
BEGIN
    pg_format := REPLACE(format_str, '%Y', 'YYYY');
    pg_format := REPLACE(pg_format, '%y', 'YY');
    pg_format := REPLACE(pg_format, '%m', 'MM');
    pg_format := REPLACE(pg_format, '%c', 'MM');
    pg_format := REPLACE(pg_format, '%d', 'DD');
    pg_format := REPLACE(pg_format, '%e', 'DD');
    pg_format := REPLACE(pg_format, '%H', 'HH24');
    pg_format := REPLACE(pg_format, '%h', 'HH12');
    pg_format := REPLACE(pg_format, '%i', 'MI');
    pg_format := REPLACE(pg_format, '%s', 'SS');
    pg_format := REPLACE(pg_format, '%W', 'Day');
    pg_format := REPLACE(pg_format, '%a', 'Dy');
    pg_format := REPLACE(pg_format, '%M', 'Month');
    pg_format := REPLACE(pg_format, '%b', 'Mon');

    RETURN TO_CHAR(date_val, pg_format);
END;
$$ LANGUAGE plpgsql IMMUTABLE;
