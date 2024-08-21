function updateItemName(item, name)
{
    if(name === undefined) name = item.name;
    if(item.infoName === name) return;
    item.infoName   = name;
    item.name       = name;
    const namePath  = name.split('.');
    item.order      = namePath.reduce((total, level, index) => total + (+level * ([1000000, 1000, 1, 0.001, 0.000001][index])), 0);
    item.level      = namePath.length;
    item.selfName   = namePath.pop();
    item.parentName = namePath.join('.');
}
