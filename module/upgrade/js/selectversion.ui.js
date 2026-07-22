const featureVersionLimits = {
    ""   : [22, 3],
    biz  : [13, 3],
    max  : [5, 3],
    ipd  : [4, 3]
};

const getFeatureVersionInfo = function(version)
{
    const match = String(version || "").toLowerCase().match(/^([a-z]+)?(\d.*)$/);
    if(!match) return null;

    const parts = match[2].split("_").reduce((result, part) =>
    {
        const number = part.match(/^\d+/);
        if(number) result.push(Number(number[0]));
        return result;
    }, []);

    return {prefix: match[1] || "", parts};
};

const compareFeatureVersion = function(currentParts, limitParts)
{
    const length = Math.max(currentParts.length, limitParts.length);
    for(let index = 0; index < length; index ++)
    {
        const current = currentParts[index] || 0;
        const limit   = limitParts[index] || 0;

        if(current < limit) return -1;
        if(current > limit) return 1;
    }

    return 0;
};

const shouldShowFeatureDesc = function(version)
{
    const info = getFeatureVersionInfo(version);
    if(!info) return false;

    if(info.prefix === "pro") return true;
    if(!Object.prototype.hasOwnProperty.call(featureVersionLimits, info.prefix)) return false;

    return compareFeatureVersion(info.parts, featureVersionLimits[info.prefix]) <= 0;
};

window.loadFeature = function()
{
    const fromVersion = $("[name=fromVersion]").val();
    $("#featureDesc")[shouldShowFeatureDesc(fromVersion) ? "show" : "hide"]();
};

window.waitDom('[name=fromVersion]', loadFeature);
