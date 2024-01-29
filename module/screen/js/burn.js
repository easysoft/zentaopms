$(function()
{
    initBurnChar();
});

function truncateCustomString(str, maxLength)
{
    let count     = 0;
    let strLength = 0;

    for(let char of str)
    {
        if(char.match(/[A-Z]/))
        {
            count += 2;
        }
        else if(char.match(/[a-z]/))
        {
            count += 1;
        }
        else if(char.charCodeAt(0) > 255)
        {
            count += 2;
        }
        else
        {
            count += 1;
        }

        if(count > maxLength)
        {
            break;
        }

        strLength++;
    }

    return str.slice(0, strLength);
}
