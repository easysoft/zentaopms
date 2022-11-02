update zt_kanban k
set 
  k.colWidth    = if(k.colWidth < 200, 200, k.colWidth),
  k.minColWidth = if(k.minColWidth < 200, 200, k.minColWidth),
  k.maxColWidth = if(k.maxColWidth <= 200 and k.minColWidth <= 200, 201, k.maxColWidth)
where k.colWidth < 200 or k.minColWidth < 200 or k.maxColWidth < 200;

update zt_project p
set 
  p.colWidth    = if(p.colWidth < 200, 200, p.colWidth),
  p.minColWidth = if(p.minColWidth < 200, 200, p.minColWidth),
  p.maxColWidth = if(p.maxColWidth <= 200 and p.minColWidth <= 200, 201, p.maxColWidth)
where p.colWidth < 200 or p.minColWidth < 200 or p.maxColWidth < 200;
