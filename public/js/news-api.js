const API_KEY = '993171fccd68402289dfb60b73b35149';
const API_URL = 'https://newsapi.org/v2/everything';

// Load search history from localStorage
function loadHistory() {
    const history = JSON.parse(localStorage.getItem('newsSearchHistory')) || [];
    displayHistory(history);
}

// Save search to history
function saveToHistory(query) {
    let history = JSON.parse(localStorage.getItem('newsSearchHistory')) || [];
    
    // Remove if already exists
    history = history.filter(item => item !== query);
    
    // Add to beginning
    history.unshift(query);
    
    // Keep only last 5 searches
    history = history.slice(0, 5);
    
    localStorage.setItem('newsSearchHistory', JSON.stringify(history));
    displayHistory(history);
}

// Display search history
function displayHistory(history) {
    const historyList = document.getElementById('history-list');
    
    if (history.length === 0) {
        historyList.innerHTML = '<p style="opacity: 0.6;">Nav meklēšanas vēstures</p>';
        return;
    }
    
    historyList.innerHTML = history.map(item => 
        `<span class="history-item" onclick="searchFromHistory('${item}')">${item}</span>`
    ).join('');
}

// Search from history
function searchFromHistory(query) {
    document.getElementById('news-search').value = query;
    searchNews();
}

// Clear history
function clearHistory() {
    localStorage.removeItem('newsSearchHistory');
    displayHistory([]);
}

// Main search function
async function searchNews() {
    const searchInput = document.getElementById('news-search');
    const query = searchInput.value.trim();
    
    if (!query) {
        showError('Lūdzu, ievadiet meklēšanas vārdu!');
        return;
    }
    
    // Save to history
    saveToHistory(query);
    
    // Show loading
    document.getElementById('loading').style.display = 'block';
    document.getElementById('error-message').style.display = 'none';
    document.getElementById('news-container').innerHTML = '';
    
    try {
        const response = await fetch(`${API_URL}?q=${encodeURIComponent(query)}&apiKey=${API_KEY}&language=en&pageSize=12&sortBy=publishedAt`);
        
        if (!response.ok) {
            throw new Error('Neizdevās ielādēt ziņas');
        }
        
        const data = await response.json();
        
        document.getElementById('loading').style.display = 'none';
        
        if (data.articles && data.articles.length > 0) {
            displayNews(data.articles);
        } else {
            showError('Dati nav pieejami. Mēģiniet citu meklēšanas vārdu.');
        }
        
    } catch (error) {
        document.getElementById('loading').style.display = 'none';
        showError('Kļūda: ' + error.message);
        console.error('Error:', error);
    }
}

// Display news articles
function displayNews(articles) {
    const container = document.getElementById('news-container');
    
    container.innerHTML = articles.map(article => {
        const date = new Date(article.publishedAt).toLocaleDateString('lv-LV', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        const imageHtml = article.urlToImage 
            ? `<img src="${article.urlToImage}" alt="${article.title}" class="news-image" onerror="this.parentElement.innerHTML='<div class=\\'news-no-image\\'>📰</div>'">`
            : '<div class="news-no-image">📰</div>';
        
        return `
            <div class="news-card">
                ${imageHtml}
                <div class="news-content">
                    <span class="news-source">${article.source.name || 'Nezināms avots'}</span>
                    <h3 class="news-title">${article.title}</h3>
                    <p class="news-description">${article.description || 'Nav apraksta pieejama.'}</p>
                    <p class="news-date">📅 ${date}</p>
                    <a href="${article.url}" target="_blank" class="news-link">Lasīt vairāk →</a>
                </div>
            </div>
        `;
    }).join('');
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

// Load history on page load
document.addEventListener('DOMContentLoaded', function() {
    loadHistory();
    // Auto-search "sports" on page load
    searchNews();
});

// Allow Enter key to search
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('news-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchNews();
        }
    });
});