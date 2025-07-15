// ⛔️ Schutzmaßnahmen (wie gehabt)
document.addEventListener('contextmenu', function(e) { e.preventDefault(); }, false);
document.addEventListener('keydown', function(e) {
    var isMac = navigator.platform.toUpperCase().indexOf("MAC") !== -1;
    var ctrlOrCmd = isMac ? e.metaKey : e.ctrlKey;
    var key = e.key.toLowerCase();

    if (ctrlOrCmd && ["c", "v", "x", "a", "s"].includes(key)) {
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

const style = document.createElement("style");
style.innerHTML = `* { user-select: none !important; }`;
document.head.appendChild(style);

// 🧮 Taschenrechner nachladen
(function () {
  const insertCalculator = async () => {
    try {
      const response = await fetch('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamGuard/templates/calculator_overlay.html');
      const html = await response.text();
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html;
      document.body.appendChild(wrapper);

      // Jetzt calculator.js einbinden
      const script = document.createElement('script');
      script.src = './Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamGuard/js/calculator.js';
      document.body.appendChild(script);
    } catch (error) {
      console.error('Taschenrechner konnte nicht geladen werden:', error);
    }
  };

  if (window.location.href.includes('ilTestPlayer')) {
    window.addEventListener('load', insertCalculator);
  }
})();
