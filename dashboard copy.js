

const toolbox = document.getElementById('toolbox');
const formArea = document.getElementById('form-area');
const saveBtn = document.getElementById('save-form');
const formTitleInput = document.getElementById('form-title');
const msg = document.getElementById('msg');

// Make dragstart reliable and cross-browser
toolbox.addEventListener('dragstart', e => {
  const el = e.target.closest('[draggable][data-type]');
  if (!el) return;
  const type = el.dataset.type;
  try {
    e.dataTransfer.setData('fieldType', type);   // custom
    e.dataTransfer.setData('text/plain', type);  // fallback
    e.dataTransfer.effectAllowed = 'copy';
  } catch (_) {}
});

// Ensure drop is allowed
formArea.addEventListener('dragover', e => {
  e.preventDefault();
  try { e.dataTransfer.dropEffect = 'copy'; } catch (_) {}
});

formArea.addEventListener('drop', async e => {
  e.preventDefault();

  // Read type from either key
  const type =
    e.dataTransfer.getData('fieldType') ||
    e.dataTransfer.getData('text/plain');

  if (!type) return; // nothing dragged from our toolbox

  let fieldId = 'field_' + Date.now();
  let label = prompt("Enter label for this field:");
  if (!label) return;

  if (type === 'email') fieldId = 'email';

  let html = '';
  const div = document.createElement('div');
  div.className = 'field-item';
  div.dataset.type = type;
  div.dataset.name = (type === 'email') ? 'email' : fieldId;
  div.dataset.label = label;

  // Field renderers
  if (type === 'text') {
    html = `<label>${label}: <input name="${fieldId}" type="text"></label>`;
  } else if (type === 'textarea') {
    html = `<label>${label}: <textarea name="${fieldId}"></textarea></label>`;
  } else if (type === 'checkbox') {
    html = `<label><input name="${fieldId}" type="checkbox"> ${label}</label>`;
  } else if (type === 'radio') {
    const opts = prompt("Enter comma-separated options (e.g., Yes,No):", "Option 1,Option 2");
    if (!opts) return;
    const optionsArr = opts.split(',').map(o => o.trim()).filter(Boolean);
    const optionsHTML = optionsArr.map(opt =>
      `<label>${opt} <input name="${fieldId}" type="radio" value="${opt}"></label>`
    ).join('<br>');
    div.dataset.options = JSON.stringify(optionsArr);
    html = `<strong>${label}</strong><br>${optionsHTML}`;
  } else if (type === 'countrycode') {
    html = `
      <label>${label}</label>
      <select name="${fieldId}_country" class="country-select"></select>
      <input name="${fieldId}_phone" type="text" placeholder="Phone Number">
    `;
  } else if (type === 'email') {
    html = `<label>${label}: <input name="email" type="email" placeholder="example@email.com"></label>`;
  } else if (type === 'file') { // ✅ FILE FIELD
    html = `<label>${label}: <input name="${fieldId}" type="file"></label>`;
  }

  div.innerHTML = html + ` <button type="button" onclick="this.parentNode.remove()">Remove</button>`;
  formArea.appendChild(div);

  // Populate countries after append
  if (type === 'countrycode') {
    setTimeout(() => {
      const select = div.querySelector('.country-select');
      const countries = [
        { name: "India", code: "+91" },
        { name: "United States", code: "+1" },
        { name: "United Kingdom", code: "+44" },
        { name: "Canada", code: "+1" },
        { name: "Australia", code: "+61" },
        { name: "Germany", code: "+49" },
        { name: "France", code: "+33" },
        { name: "China", code: "+86" },
        { name: "Japan", code: "+81" },
        { name: "Brazil", code: "+55" },
        { name: "South Africa", code: "+27" },
        { name: "Russia", code: "+7" }
      ];
      countries.forEach(c => {
        const option = document.createElement('option');
        option.value = c.code;
        option.textContent = `${c.name} (${c.code})`;
        if (c.name === 'India') option.selected = true;
        select.appendChild(option);
      });
    }, 0);
  }
});

// Save as before
saveBtn.addEventListener('click', () => {
  const title = formTitleInput.value.trim();
  if (!title) {
    alert('Enter form title');
    return;
  }

  const fields = [];
  document.querySelectorAll('#form-area .field-item').forEach(item => {
    const field = {
      type: item.dataset.type,
      name: item.dataset.name,
      label: item.dataset.label
    };

    if (field.type === 'radio' && item.dataset.options) {
      try { field.options = JSON.parse(item.dataset.options); } catch {}
    }
    if (field.type === 'countrycode') {
      field.subfields = {
        country_select: `${field.name}_country`,
        phone_input: `${field.name}_phone`
      };
    }
    fields.push(field);
  });

  fetch('save_form.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({ title, fields })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      const popup = document.getElementById('popup');
      const popupMsg = document.getElementById('popup-msg');
      const link = `form.php?f=${data.form_link}`;
      popupMsg.innerHTML = `
        ✅ Saved! <br>
        Form Link: <a href="${link}" target="_blank">${link}</a>
        <button id="copy-btn">Copy Link</button>
      `;
      popup.style.display = 'block';
      setTimeout(() => {
        document.getElementById('copy-btn').onclick = () => {
          navigator.clipboard.writeText(link);
          document.getElementById('copy-btn').innerText = "Copied!";
        };
      }, 50);
    } else {
      msg.textContent = 'Error saving form';
    }
  });
});






