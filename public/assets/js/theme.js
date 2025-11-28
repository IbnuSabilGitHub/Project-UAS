document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("theme-toggle");
    const iconDark = document.getElementById("theme-toggle-dark-icon");
    const iconLight = document.getElementById("theme-toggle-light-icon");
    const root = document.documentElement;

    // load theme dari localStorage
    const savedTheme = localStorage.getItem("theme");

    // Terapkan tema yang disimpan atau preferensi sistem
    if (savedTheme === "dark") {
        root.classList.add("dark");
        iconLight.classList.add("hidden");
        iconDark.classList.remove("hidden");
    } else if (savedTheme === "light") {
        root.classList.remove("dark");
        iconLight.classList.remove("hidden");
        iconDark.classList.add("hidden");
    } else {
        if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            root.classList.add("dark");
            iconLight.classList.add("hidden");
            iconDark.classList.remove("hidden");
        }
    }

    // Toggle tema saat tombol diklik
    toggleBtn.addEventListener("click", () => {
        const isDark = root.classList.toggle("dark");

        // Update icons
        if (isDark) {
            iconLight.classList.add("hidden");
            iconDark.classList.remove("hidden");
            localStorage.setItem("theme", "dark");
        } else {
            iconLight.classList.remove("hidden");
            iconDark.classList.add("hidden");
            localStorage.setItem("theme", "light");
        }
    });
});

