document.addEventListener('DOMContentLoaded', function() {
    // Element selectors
    const header = document.querySelector('.fixed-header');
    const mobileToggle = document.getElementById('mobileToggle');
    const navLinks = document.getElementById('navLinks');
    
    // Dropdown Profile - UPDATED SELECTORS
    const userDropdown = document.querySelector('.user-dropdown');
    const userAvatar = document.querySelector('.user-avatar'); // Changed from .user-avatar-btn
    
    // Tambahkan area trigger untuk hover
    const triggerArea = document.createElement('div');
    triggerArea.className = 'header-trigger-area';
    document.body.appendChild(triggerArea);
    
    // Variabel untuk tracking
    let lastScrollTop = 0;
    let isHeaderVisible = true;
    let isHoveringHeader = false;
    let scrollTimer = null;
    
    // Function untuk menangani scroll
    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Tambahkan class scrolled untuk efek shadow
        if (scrollTop > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Jika sedang hover pada header, jangan sembunyikan
        if (isHoveringHeader) {
            return;
        }
        
        // Logic untuk menampilkan/menyembunyikan header
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scroll ke bawah dan sudah cukup jauh dari atas
            if (isHeaderVisible) {
                header.classList.add('header-hidden');
                isHeaderVisible = false;
            }
        } else {
            // Scroll ke atas
            if (!isHeaderVisible) {
                header.classList.remove('header-hidden');
                isHeaderVisible = true;
            }
        }
        
        lastScrollTop = scrollTop;
        
        // Set timer untuk menyembunyikan header setelah berhenti scroll
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function() {
            if (scrollTop > 100 && !isHoveringHeader) {
                header.classList.add('header-hidden');
                isHeaderVisible = false;
            }
        }, 2000); // Sembunyikan setelah 2 detik tidak ada aktivitas scroll
    }
    
    // Event listener untuk scroll
    window.addEventListener('scroll', handleScroll);
    
    // Event listeners untuk hover
    header.addEventListener('mouseenter', function() {
        isHoveringHeader = true;
        header.classList.remove('header-hidden');
        isHeaderVisible = true;
    });
    
    header.addEventListener('mouseleave', function() {
        isHoveringHeader = false;
        // Hanya sembunyikan jika sudah di-scroll dan tidak ada aktivitas
        if (window.pageYOffset > 100) {
            scrollTimer = setTimeout(function() {
                if (!isHoveringHeader) {
                    header.classList.add('header-hidden');
                    isHeaderVisible = false;
                }
            }, 1000);
        }
    });
    
    // Trigger area untuk menampilkan header saat hover di area atas
    triggerArea.addEventListener('mouseenter', function() {
        header.classList.remove('header-hidden');
        isHeaderVisible = true;
        isHoveringHeader = true;
    });
    
    triggerArea.addEventListener('mouseleave', function() {
        isHoveringHeader = false;
        // Sembunyikan kembali jika scroll sudah jauh
        if (window.pageYOffset > 100) {
            setTimeout(function() {
                if (!isHoveringHeader) {
                    header.classList.add('header-hidden');
                    isHeaderVisible = false;
                }
            }, 1000);
        }
    });
    
    // Mobile menu toggle
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            
            // Toggle ikon menu/close
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Tutup menu mobile ketika link diklik
    const navItems = document.querySelectorAll('.nav-links a');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            if (navLinks && navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
                
                // Reset ikon ke bars
                if (mobileToggle) {
                    const icon = mobileToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    });
    
    // DROPDOWN PROFILE HANDLING - UPDATED FOR CLICK ONLY
    if (userDropdown && userAvatar) {
        // Event listener untuk kontrol klik dropdown profile
        userAvatar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function(event) {
            if (!userDropdown.contains(event.target)) {
                userDropdown.classList.remove('active');
            }
        });

        // Prevent dropdown from closing when clicking inside it
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});