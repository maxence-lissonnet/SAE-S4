const listItems = document.querySelectorAll("#main nav ul li");
const content = document.getElementById('content');
const initialPanel = document.body.dataset.initialPanel;

const debug = isDebug();

function getMvcAdminUrl(panel) {
    const marker = '/MVC/index.php';
    const path = window.location.pathname;
    const markerIndex = path.indexOf(marker);
    const appRoot = markerIndex === -1 ? '' : path.slice(0, markerIndex);

    let url = `${appRoot}/MVC/index.php?page=admin_panel&panel=${encodeURIComponent(panel)}`;
    if (debug) {
        url += '&debug=1';
    }

    return url;
}

// Check for debug
function isDebug() {
    const url = window.location.href;
    return url.includes('?debug');
}

listItems.forEach(item => {
    item.addEventListener('click', () => {
        listItems.forEach(li => li.classList.remove('selected'));
        item.classList.add('selected');
        content.src = getMvcAdminUrl(item.getAttribute('perm'));
    });
});

const firstItem = listItems[0];
const selectedItem = Array.from(listItems).find(item => item.getAttribute('perm') === initialPanel) ?? firstItem;

if (selectedItem) {
    selectedItem.click();
}
