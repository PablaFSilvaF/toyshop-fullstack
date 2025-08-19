document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const errorMessages = document.getElementById("error-messages");
  function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }
  function validatePassword(password) {
    const minLength = password.length >= 8;
    const hasNumber = /\d/.test(password);
    const hasUpperCase = /[A-Z]/.test(password);
    return minLength && hasNumber && hasUpperCase;
  }
  form.addEventListener("input", () => {
    let messages = [];

    if (!validateEmail(emailInput.value)) {
      messages.push("El correo electrónico no es válido.");
    }
    if (!validatePassword(passwordInput.value)) {
      messages.push("La contraseña debe tener al menos 8 caracteres, incluir un número y una letra mayúscula.");
    }
    errorMessages.innerHTML = messages.join("<br>");
  });
  form.addEventListener("submit", (e) => {
    if (errorMessages.innerHTML !== "") {
      e.preventDefault(); // Evita que se envíe el formulario si hay errores
      alert("Por favor corrige los errores antes de continuar.");
    }
  });
});
