
// Cegah FOUC(flash of unstyled content) – terapkan tema sedini mungkin
(function() {
    const saved = localStorage.getItem("theme");

    if (saved === "dark") {
        document.documentElement.classList.add("dark");
    } else if (saved === "light") {
        document.documentElement.classList.remove("dark");
    } else {
        // Ikuti preferensi sistem
        if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            document.documentElement.classList.add("dark");
        }
    }
})();