<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats Tracker - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">Stats Tracker</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#home">Sākums</a></li>
                    <li><a href="#about">Par mums</a></li>
                    <li class="dropdown">
                        <a href="#services">Pakalpojumi ▼</a>
                        <div class="dropdown-content">
                            <a href="#stats">Statistikas ievadīšana</a>
                            <a href="#analysis">Datu analīze</a>
                            <a href="#reports">Atskaites</a>
                            <a href="#export">Datu eksports</a>
                        </div>
                    </li>
                    <li><a href="#contact">Kontakti</a></li>
                </ul>
                <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌓</button>
                
                <!-- User Info & Logout -->
                <div class="user-info">
                    <span>{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Iziet</button>
                    </form>
                </div>
                
                <div class="hamburger" onclick="toggleMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero" id="home">
            <h1>Universāls Sporta Statistikas Apkopotājs</h1>
            <p>Profesionāla platforma spēļu statistikas ievadīšanai, uzglabāšanai un analīzei. Vienkārši, ātri un efektīvi!</p>
        </section>

        <div class="cards-container">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400" alt="Basketbols" class="card-image">
                <div class="card-content">
                    <h3>🏀 Basketbols</h3>
                    <p>Ievadiet un analizējiet basketbola spēļu statistiku: punkti, piespēles, atlēkušās bumbas, bloķējumi un vairāk.</p>
                    <button class="card-btn" onclick="openModal('Basketbols')">Uzzināt vairāk</button>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=400" alt="Futbols" class="card-image">
                <div class="card-content">
                    <h3>⚽ Futbols</h3>
                    <p>Uzglabājiet futbola spēļu datus: vārti, piespēles, precizitāte, aizsardzības darbības un komandas statistika.</p>
                    <button class="card-btn" onclick="openModal('Futbols')">Uzzināt vairāk</button>
                </div>
            </div>

            <div class="card">
                <img src="https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=400" alt="Volejbols" class="card-image">
                <div class="card-content">
                    <h3>🏐 Volejbols</h3>
                    <p>Reģistrējiet volejbola statistiku: servēšana, uzbrukumi, bloķi, uzkritieni un seta rezultāti.</p>
                    <button class="card-btn" onclick="openModal('Volejbols')">Uzzināt vairāk</button>
                </div>
            </div>
        </div>

        </div>

        <aside></aside>

        <!-- NEWS API Section -->
        <section class="news-section">
            <h2>🏆 Sporta Ziņas</h2>
            <div class="search-container">
                <input type="text" id="news-search" placeholder="Meklēt sporta ziņas... (piemēram: 'basketball', 'football')" value="sports">
                <button onclick="searchNews()" class="search-btn">Meklēt</button>
                <button onclick="clearHistory()" class="clear-btn">Notīrīt vēsturi</button>
            </div>
            
            <div class="search-history">
                <h3>Meklēšanas vēsture:</h3>
                <div id="history-list"></div>
            </div>

            <div id="loading" class="loading" style="display: none;">
                <div class="spinner"></div>
                <p>Ielādē ziņas...</p>
            </div>

            <div id="error-message" class="error-message" style="display: none;"></div>

            <div id="news-container" class="news-container"></div>
        </section>

        <aside></aside>

        <aside>
            <h2>📈 Jaunākie raksti</h2>
            <ul>
                <li>Kā efektīvi analizēt basketbola statistiku</li>
                <li>SQL datubāzes optimizācija sporta datiem</li>
                <li>Top 10 statistikas rādītāji futbolā</li>
                <li>Kā izveidot profesionālas sporta atskaites</li>
                <li>Datu vizualizācija sporta analītikā</li>
            </ul>
        </aside>
    </main>

    <footer id="contact">
        <div class="footer-content">
            <p>&copy; 2025 Stats Tracker - Universāls Sporta Statistikas Apkopotājs</p>
            <p>Visi dati tiek drošī uzglabāti SQL datubāzēs</p>
            <div class="social-links">
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Twitter">🐦</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="LinkedIn">💼</a>
            </div>
        </div>
    </footer>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2 id="modal-title"></h2>
            <p id="modal-text"></p>
            <img id="modal-img" src="" alt="" style="width: 100%; border-radius: 10px; margin: 1rem 0;">
            <p>Šī platforma ļauj jums:</p>
            <ul style="margin-left: 2rem; margin-top: 1rem;">
                <li>✓ Ievadīt detalizētu spēļu statistiku</li>
                <li>✓ Uzglabāt datus drošās SQL datubāzēs</li>
                <li>✓ Analizēt sniegumu ar vizualizācijām</li>
                <li>✓ Eksportēt atskaites dažādos formātos</li>
                <li>✓ Salīdzināt spēlētāju statistiku</li>
            </ul>
        </div>
    </div>

    <script src="{{ asset('js/dashboard-script.js') }}"></script>
    <script src="{{ asset('js/news-api.js') }}"></script>
</body>
</html>