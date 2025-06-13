/**
 * examguard_pretest.js
 * Verzögert den Start des Tests um 1–10 Sekunden.
 */
window.addEventListener("load", function () {
    const button = document.querySelector('input[type="submit"][name="cmd[startPlayer]"][value="Test starten"]');
    if (!button) return;

    button.addEventListener("click", function (e) {
        e.preventDefault();

        if (button.dataset.delayStarted === "1") return;
        button.dataset.delayStarted = "1";

        const delay = Math.floor(Math.random() * 10) + 1;
        const originalValue = button.value;

        let countdown = delay;
        button.disabled = true;

        const interval = setInterval(() => {
            if (countdown <= 0) {
                clearInterval(interval);
                button.disabled = false;
                button.value = originalValue;

                const form = button.closest("form");
                if (form) form.submit();
                else button.click();
            } else {
                button.value = `Test startet in ${countdown} Sek...`;
                countdown--;
            }
        }, 1000);
    });
});

