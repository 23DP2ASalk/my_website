function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }

        function toggleMenu() {
            document.querySelector('.nav-links').classList.toggle('active');
            document.querySelector('.hamburger').classList.toggle('active');
        }

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
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
