document.addEventListener('contextmenu', function(e) { e.preventDefault(); }, false);
document.addEventListener('keydown', function(e) {
    var isMac = navigator.platform.toUpperCase().indexOf("MAC") !== -1;
    var ctrlOrCmd = isMac ? e.metaKey : e.ctrlKey;
    if (ctrlOrCmd && (e.key.toLowerCase() === "c" || e.key.toLowerCase() === "v")) {
        e.preventDefault();
    }
    if (e.key === "F12") {
        e.preventDefault();
    }
    if (ctrlOrCmd && e.shiftKey &&
        (e.key.toLowerCase() === "i" || e.key.toLowerCase() === "j" || e.key.toLowerCase() === "c")) {
        e.preventDefault();
    }
}, false);
