function togglePassword() {
  const passInput = document.getElementById("password");
  const toggle = document.querySelector(".toggle-password");
  if (passInput.type === "password") {
    passInput.type = "text";
    toggle.textContent = "🙈 Hide Password";
  } else {
    passInput.type = "password";
    toggle.textContent = "👁️ Show Password";
  }
}

function toggleDarkMode() {
  document.body.classList.toggle("dark-mode");
}
