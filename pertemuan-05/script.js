document.addEventListener("DOMContentLoaded", function () {
    // === Tombol menu ===
    const menuToggle = document.getElementById("menuToggle");
    const nav = document.querySelector("nav");

    if (menuToggle && nav) {
        menuToggle.addEventListener("click", function () {
            nav.classList.toggle("active");
            this.textContent = nav.classList.contains("active") ? "\u2716" : "\u2630";
        });
    }

    // === Menyapa pengguna ===
    let nama = prompt("Siapa nama kamu?");
    if (nama) {
        alert("Halo, " + nama + "!");
        const pesanEl = document.getElementById("pesan");
        if (pesanEl) {
            pesanEl.innerText = "Halo, " + nama + "!";
        }
    }

    // === Validasi Form ===
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Cegah reload default

            const namaInput = document.getElementById("nama");
            const emailInput = document.getElementById("email");
            const pesanInput = document.getElementById("pesan");

            if (!namaInput || !emailInput || !pesanInput) return;

            let isValid = true;

            // Hapus pesan error lama
            document.querySelectorAll(".error-msg").forEach(el => el.remove());

            // Validasi kosong
            if (!namaInput.value.trim() || !emailInput.value.trim() || !pesanInput.value.trim()) {
                alert("Semua kolom wajib diisi!");
                return;
            }

            // Validasi nama
            if (namaInput.value.trim().length < 3) {
                showError(namaInput, "Nama minimal 3 huruf dan tidak boleh kosong.");
                isValid = false;
            } else if (!/^[A-Za-z\s]+$/.test(namaInput.value)) {
                showError(namaInput, "Nama hanya boleh berisi huruf dan spasi.");
                isValid = false;
            }

            // Validasi email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                showError(emailInput, "Format email tidak valid. Contoh: nama@mail.com");
                isValid = false;
            }

            // Validasi pesan
            if (pesanInput.value.trim().length < 10) {
                showError(pesanInput, "Pesan minimal 10 karakter agar lebih jelas.");
                isValid = false;
            }

            if (isValid) {
                alert(`Terima kasih, ${namaInput.value.trim()}! Pesan Anda telah dikirim.`);
                form.reset();
                location.reload();
            }
        });
    }

    // === Hitung karakter textarea ===
    const pesanField = document.getElementById("pesan");
    const charCount = document.getElementById("charCount");

    if (pesanField && charCount) {
        pesanField.addEventListener("input", function () {
            const panjang = this.value.length;
            charCount.textContent = panjang + "/200 karakter";
        });
    }

    // === Layout responsif untuk label & counter ===
    function setupCharCountLayout() {
        const label = document.querySelector('label[for="pesan"]');
        if (!label) return;

        let wrapper = label.querySelector('[data-wrapper="pesan-wrapper"]');
        const span = label.querySelector('span');
        const textarea = document.getElementById("pesan");
        const counter = document.getElementById("charCount");

        if (!span || !textarea || !counter) return;

        if (!wrapper) {
            wrapper = document.createElement("div");
            wrapper.dataset.wrapper = "pesan-wrapper";
            wrapper.style.width = "100%";
            wrapper.style.flex = "1";
            wrapper.style.display = "flex";
            wrapper.style.flexDirection = "column";

            label.insertBefore(wrapper, textarea);
            wrapper.appendChild(textarea);
            wrapper.appendChild(counter);

            textarea.style.width = "100%";
            textarea.style.boxSizing = "border-box";
            counter.style.color = "#555";
            counter.style.fontSize = "14px";
            counter.style.marginTop = "4px";
        }

        applyResponsiveLayout();
    }

    function applyResponsiveLayout() {
        const label = document.querySelector('label[for="pesan"]');
        const span = label?.querySelector("span");
        const wrapper = label?.querySelector('[data-wrapper="pesan-wrapper"]');
        const counter = document.getElementById("charCount");

        if (!label || !span || !wrapper || !counter) return;

        const isMobile = window.matchMedia("(max-width: 600px)").matches;

        if (isMobile) {
            label.style.display = "flex";
            label.style.flexDirection = "column";
            label.style.alignItems = "flex-start";
            span.style.textAlign = "left";
            span.style.marginBottom = "4px";
        } else {
            label.style.display = "flex";
            label.style.flexDirection = "row";
            label.style.alignItems = "baseline";
            span.style.minWidth = "180px";
            span.style.textAlign = "right";
            span.style.paddingRight = "16px";
        }
    }

    setupCharCountLayout();
    window.addEventListener("resize", applyResponsiveLayout);
});

// === Fungsi Error ===
function showError(inputElement, message) {
    document
        .querySelectorAll(`.error-msg[data-for-id="${inputElement.id}"]`)
        .forEach(el => el.remove());

    let label = inputElement.closest("label");
    if (!label) {
        label = document.querySelector(`label[for="${inputElement.id}"]`);
    }

    const small = document.createElement("small");
    small.className = "error-msg";
    small.textContent = message;
    small.style.color = "red";
    small.style.fontSize = "14px";
    small.style.display = "block";
    small.style.marginTop = "4px";
    small.dataset.forId = inputElement.id;

    inputElement.style.border = "1px solid red";
    inputElement.insertAdjacentElement("afterend", small);

    alignErrorMessage(small, inputElement);
}


function alignErrorMessage(smallEl, inputEl) {
    const isMobile = window.matchMedia("(max-width: 600px)").matches;
    if (isMobile) {
        smallEl.style.marginLeft = "0";
        smallEl.style.width = "100%";
        return;
    }

    const label = inputEl.closest("label");
    if (!label) return;

    const rectLabel = label.getBoundingClientRect();
    const rectInput = inputEl.getBoundingClientRect();
    const offsetLeft = Math.max(0, Math.round(rectInput.left - rectLabel.left));

    smallEl.style.marginLeft = offsetLeft + "px";
    smallEl.style.width = Math.round(rectInput.width) + "px";
}

// === Realign error message saat resize ===
window.addEventListener("resize", () => {
    document.querySelectorAll(".error-msg").forEach(small => {
        const target = document.getElementById(small.dataset.forId);
        if (target) alignErrorMessage(small, target);
    });
});
