document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("theme-toggle");
    if (!toggleBtn) return;
    const iconDark = document.getElementById("theme-toggle-dark-icon");
    const iconLight = document.getElementById("theme-toggle-light-icon");
    const root = document.documentElement;

    // load theme dari localStorage
    const savedTheme = localStorage.getItem("theme");
    console.log('Saved theme:', savedTheme);

    // Terapkan tema yang disimpan atau preferensi sistem
    if (savedTheme === "dark") {
        console.log('test3')
        root.classList.add("dark");
        iconLight.classList.add("hidden");
        iconDark.classList.remove("hidden");
    } else if (savedTheme === "light") {
        console.log("sdfsdf")
        root.classList.remove("dark");
        iconLight.classList.remove("hidden");
        iconDark.classList.add("hidden");
    } else {
        console.log('sdfsdf')
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
            console.log('test')
            iconLight.classList.add("hidden");
            iconDark.classList.remove("hidden");
            localStorage.setItem("theme", "dark");
        } else {
            console.log('test2')
            iconLight.classList.remove("hidden");
            iconDark.classList.add("hidden");
            localStorage.setItem("theme", "light");
        }
    });
});

