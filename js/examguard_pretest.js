document.addEventListener("DOMContentLoaded", function () {
    const delaySeconds = 10;
    const startButton = document.querySelector('button[data-action*="initTest"]');

    if (!startButton) {
        console.log("❌ Kein Start-Button gefunden.");
        return;
    }

    console.log("✅ Start-Button gefunden:", startButton);

    const originalUrl = startButton.getAttribute("data-action");

    // Bestehende Eventlistener vollständig ersetzen
    const newButton = startButton.cloneNode(true);
    startButton.parentNode.replaceChild(newButton, startButton);

    newButton.addEventListener("click", function (e) {
        e.preventDefault();
        console.log("🕒 Button wurde geklickt. Countdown startet.");

        newButton.disabled = true;
        newButton.classList.add("disabled");

        const countdownSpan = document.createElement("span");
        countdownSpan.style.marginLeft = "1em";
        countdownSpan.style.fontWeight = "bold";
        newButton.appendChild(countdownSpan);

        let remaining = delaySeconds;
        countdownSpan.textContent = `Startet in ${remaining}s...`;

        const interval = setInterval(() => {
            remaining--;
            countdownSpan.textContent = `Startet in ${remaining}s...`;

            if (remaining <= 0) {
                clearInterval(interval);
                console.log("✅ Weiterleitung zu:", originalUrl);
                window.location.href = originalUrl;
            }
        }, 1000);
    });
});
