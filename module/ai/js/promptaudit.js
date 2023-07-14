(function()
{
    const inputs = [...Array.from(document.querySelectorAll('input')), ...Array.from(document.querySelectorAll('textarea'))];
    inputs.forEach(element => {
        const displayElement = document.getElementById(element.name + 'Display');
        if(!displayElement) return;
        element.addEventListener('input', () => {
            displayElement.innerText = element.value;
        });
    });
})();
