function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    // Save preference to localStorage
    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
    } else {
        localStorage.setItem('darkMode', 'disabled');
    }
}

// Load dark mode preference
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
});

function toggleMenu() {
    const navLinks = document.querySelector('.nav-links');
    const hamburger = document.querySelector('.hamburger');
    const userInfo = document.querySelector('.user-info');
    
    // Toggle nav links
    navLinks.classList.toggle('active');
    hamburger.classList.toggle('active');
    
    // Close user info if open
    if (userInfo && userInfo.classList.contains('active')) {
        userInfo.classList.remove('active');
    }
    
    // Prevent body scroll when menu is open on mobile
    if (navLinks.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}

// Toggle user info on mobile
function toggleUserInfo() {
    const userInfo = document.querySelector('.user-info');
    const navLinks = document.querySelector('.nav-links');
    
    if (userInfo) {
        userInfo.classList.toggle('active');
        
        // Close nav menu if open
        if (navLinks && navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
            document.querySelector('.hamburger').classList.remove('active');
        }
    }
}

// Handle dropdown on mobile
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    if (dropdown) {
        dropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.classList.toggle('active');
            }
        });
    }
});

function openModal(sport) {
    const modal = document.getElementById('modal');
    const title = document.getElementById('modal-title');
    const text = document.getElementById('modal-text');
    const img = document.getElementById('modal-img');
    
    const content = {
        'Basketbols': {
            text: 'Basketbola statistikas moduļa ietvaros varat ievadīt un analizēt visus svarīgākos spēles rādītājus. Sistēma automātiski aprēķina efektivitātes rādītājus un ģenerē detalizētas atskaites.',
            img: 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=600'
        },
        'Futbols': {
            text: 'Futbola statistikas apkopošana ietver gan individuālo spēlētāju, gan komandas snieguma analīzi. Reģistrējiet vārtus, piespēles, precizitāti un daudz ko citu.',
            img: 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600'
        },
        'Volejbols': {
            text: 'Volejbola statistikas modulis ļauj detalizēti uzglabāt servēšanas, uzbrukumu un aizsardzības darbību statistiku, kā arī analizēt setu rezultātus un spēlētāju efektivitāti.',
            img: 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=600'
        }
    };
    
    title.textContent = sport + ' - Detalizēta informācija';
    text.textContent = content[sport].text;
    img.src = content[sport].img;
    modal.style.display = 'block';
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target == modal) {
        closeModal();
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const navLinks = document.querySelector('.nav-links');
    const hamburger = document.querySelector('.hamburger');
    const userInfo = document.querySelector('.user-info');
    const header = document.querySelector('header');
    
    if (navLinks && navLinks.classList.contains('active')) {
        if (!header.contains(event.target)) {
            navLinks.classList.remove('active');
            hamburger.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }
    
    if (userInfo && userInfo.classList.contains('active')) {
        if (!userInfo.contains(event.target) && !event.target.closest('.user-toggle')) {
            userInfo.classList.remove('active');
        }
    }
});

// Handle window resize
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
        if (window.innerWidth > 768) {
            const navLinks = document.querySelector('.nav-links');
            const hamburger = document.querySelector('.hamburger');
            const userInfo = document.querySelector('.user-info');
            
            if (navLinks) {
                navLinks.classList.remove('active');
            }
            if (hamburger) {
                hamburger.classList.remove('active');
            }
            if (userInfo) {
                userInfo.classList.remove('active');
            }
            document.body.style.overflow = 'auto';
        }
    }, 250);
});