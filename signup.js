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

function checkStrength(password) {
  const bar = document.getElementById("strengthBar");
  let strength = 0;
  if (password.length >= 6) strength += 1;
  if (password.match(/[a-z]+/)) strength += 1;
  if (password.match(/[A-Z]+/)) strength += 1;
  if (password.match(/[0-9]+/)) strength += 1;
  if (password.match(/[$@#&!]+/)) strength += 1;

  bar.style.width = strength * 20 + "%";
  if (strength <= 2) bar.style.background = "red";
  else if (strength <= 4) bar.style.background = "orange";
  else bar.style.background = "green";
}

document.getElementById("password").addEventListener("input", function() {
  checkStrength(this.value);
});

function toggleDarkMode() {
  document.body.classList.toggle("dark-mode");
}
