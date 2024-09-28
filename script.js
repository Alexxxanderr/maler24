let currentStep = 1; // Aktueller Schritt des Formulars
const totalSteps = 10; // Gesamtzahl der Schritte im Formular

// Zeige den aktuellen Schritt basierend auf der Schritt-Nummer
function showStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById(`step${step}`).classList.add('active');
}

// Gehe einen Schritt vorwärts, wenn die Gesamtzahl der Schritte nicht überschritten wird
function nextStep(step) {
    if (step < totalSteps) {
        currentStep = step + 1;
        showStep(currentStep);
    }
}

// Gehe einen Schritt zurück, wenn der Schritt größer als 1 ist
function prevStep(step) {
    if (step > 1) {
        currentStep = step - 1;
        showStep(currentStep);
    }
}

// Erlaube das Bearbeiten eines bestimmten Schritts durch direkten Aufruf
function editStep(step) {
    currentStep = step;
    showStep(currentStep);
    document.getElementById('summary').classList.remove('active');
}

// Zeige die Zusammenfassung der Benutzereingaben
function showSummary() {
    const form = document.getElementById('multiStepForm');
    const formData = new FormData(form);
    let summaryHTML = '<h3>Ihre Eingaben:</h3>';

    // Schleife durch die Formulardaten und füge sie der Zusammenfassung hinzu
    for (let [key, value] of formData.entries()) {
        if(key !== "plz"){
            summaryHTML += `<p><strong>${key}:</strong> ${value}</p>`;
        }
    }

    // Berechne den Preis basierend auf den Quadratmetern (Beispiel: 100 € pro Quadratmeter)
    const Quadratmeter = formData.get('Quadratmeter');
    const price = Quadratmeter * 100;
    summaryHTML += `<p><strong>Gesamtpreis ca. </strong> €${price}</p>`;

    document.getElementById('summaryContent').innerHTML = summaryHTML;
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById('summary').classList.add('active');
}

// Diese Funktion wechselt vom Schritt 10 zur Zusammenfassung
function goToSummary() {
    if (validateStep10()) {
        showSummary();
    }
}

// Diese Funktion validiert Schritt 10 (Telefon und E-Mail)
function validateStep10() {
    const tel = document.getElementById('tel').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (tel === '' || email === '') {
        alert('Bitte füllen Sie sowohl das Telefon- als auch das E-Mail-Feld aus.');
        return false;
    }
    
    // Grundlegende E-Mail-Validierung
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Bitte geben Sie eine gültige E-Mail-Adresse ein.');
        return false;
    }
    
    return true;
}

// Initialisiere das Formular und zeige den aktuellen Schritt
showStep(currentStep);

// Füge Ereignislistener für die Navigationsbuttons hinzu
document.querySelectorAll('.step').forEach((step, index) => {
    const nextButton = step.querySelector('button[onclick^="nextStep"]');
    const prevButton = step.querySelector('button[onclick^="prevStep"]');
    const showSummaryButton = step.querySelector('button[onclick="showSummary()"]');

    if (nextButton) {
        nextButton.onclick = () => nextStep(index + 1);
    }
    if (prevButton) {
        prevButton.onclick = () => prevStep(index + 1);
    }
    if (showSummaryButton) {
        showSummaryButton.onclick = goToSummary();
    }
});

// Füge einen Ereignislistener für die Formularübertragung hinzu
document.getElementById('multiStepForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Optional: Formular zurücksetzen oder auf eine Dankeschön-Seite weiterleiten
            // this.reset();
            // window.location.href = 'thank-you.html';
        } else {
            alert('Fehler: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Fehler:', error);
        alert('Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
    });
});
