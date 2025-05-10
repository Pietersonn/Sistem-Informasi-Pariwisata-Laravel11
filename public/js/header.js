// JavaScript untuk menangani event scroll
window.addEventListener('scroll', function() {
    const header = document.getElementById('siteHeader');

    // Cek jika halaman sudah di-scroll lebih dari 50px
    if (window.scrollY > 50) {
        header.classList.add('scrolled');  // Tambahkan kelas scrolled
    } else {
        header.classList.remove('scrolled');  // Hapus kelas scrolled
    }
});
