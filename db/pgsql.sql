DO $$
DECLARE
  rec RECORD;
  max_id BIGINT;
BEGIN
  FOR rec IN (
    SELECT
      t.table_name AS table_name,
      c.column_name AS column_name,
      regexp_replace(
        c.column_default,
        '^nextval\(''([^'']+)''::regclass\)$',
        '\1'
      ) AS seq_name
    FROM
      information_schema.columns c
    JOIN
      information_schema.tables t ON c.table_name = t.table_name
    WHERE
      t.table_type = 'BASE TABLE'
      AND c.column_default LIKE 'nextval(''%''::regclass)'
      AND t.table_schema = 'public'
  ) LOOP
    EXECUTE format('SELECT COALESCE(MAX(%I), 0) FROM %I', rec.column_name, rec.table_name)
    INTO max_id;

    EXECUTE format('SELECT setval(''%s'', %s)', rec.seq_name, max_id + 1);

    RAISE NOTICE '调整序列: % (表: %，字段: %)，新值: %',
      rec.seq_name, rec.table_name, rec.column_name, max_id + 1;
  END LOOP;
END $$;

--

CREATE INDEX idx_zt_searchindex_fts
ON zt_searchindex
USING gin(
  to_tsvector('pg_catalog.english',
    coalesce(title, '') || ' ' || coalesce(content, '')
  )
);