// Animasi scroll ringan — pengganti library AOS (tanpa CDN/JS 20KB).
// Elemen dengan [data-aos] di-animasi sekali saat masuk viewport:
//   - observer menambah class `aos-init` (semua elemen saat siap)
//   - class `aos-animate` ditambah saat elemen terlihat → CSS keyframes jalan (lihat app.css)
// Kalau JS/IntersectionObserver tidak ada, elemen tetap terlihat penuh (anti-fail).
(function () {
    var els = document.querySelectorAll('[data-aos]');
    if (!els.length) return;

    function showAll() {
        els.forEach(function (el) {
            el.classList.add('aos-init', 'aos-animate');
        });
    }

    if (!('IntersectionObserver' in window)) {
        showAll();
        return;
    }

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            var delay = el.getAttribute('data-aos-delay');
            if (delay) el.style.animationDelay = delay + 'ms';
            el.classList.add('aos-animate');
            io.unobserve(el);
        });
    }, { threshold: 0 });

    els.forEach(function (el) {
        el.classList.add('aos-init');
        io.observe(el);
    });
})();
