<footer class="main-footer">

    <div class="boxed-footer">
        <div>
            <h3>Kontakt</h3>

            <address class="address-footer">
                Hundskram<br>
                Vanessa Wagner<br>
                Fridtjof-Nansen-Straße 15<br>
                76228 Karlsruhe
            </address>
        </div>
        <div>
            <h3>Shop
                <button class="footer-menu-toggle" aria-label="Menü öffnen/schließen">
                    <svg viewBox="0 0 256 256" width="20" height="20" fill="currentColor" aria-hidden="true">
                        <path d="M224 128a8 8 0 0 1-8 8h-80v80a8 8 0 0 1-16 0v-80H40a8 8 0 0 1 0-16h80V40a8 8 0 0 1 16 0v80h80a8 8 0 0 1 8 8Z" />
                    </svg>
                </button>
            </h3>

            <ul class="footer-shop-links">
                <li><a href="#">Allgemeine Geschäftsbedingungen</a></li>
                <li><a href="#">Versand & Lieferung</a></li>
                <li><a href="#">Zahlungsweisen</a></li>
                <li><a href="#">Widerruf</a></li>
                <li><a href="#">Sicherheitshinweise</a></li>
            </ul>
        </div>

        <div>
            <h3>Kategorien
                <button class="footer-menu-toggle" aria-label="Menü öffnen/schließen">
                    <svg viewBox="0 0 256 256" width="20" height="20" fill="currentColor" aria-hidden="true">
                        <path d="M224 128a8 8 0 0 1-8 8h-80v80a8 8 0 0 1-16 0v-80H40a8 8 0 0 1 0-16h80V40a8 8 0 0 1 16 0v80h80a8 8 0 0 1 8 8Z" />
                    </svg>

                </button>

            </h3>

            <ul class="footer-shop-links">
                <li><a href="#">Kategorien</a></li>
                <li><a href="#">Geschirre</a></li>
                <li><a href="#">Halsbänder</a></li>
                <li><a href="#">Leinen</a></li>
                <li><a href="#">Accessoires</a></li>
                <li><a href="#">Hundedecke</a></li>
                <li><a href="#">Spielen</a></li>
            </ul>

        </div>
        <div>
            <h3>
                Informationen
                <button class="footer-menu-toggle" aria-label="Menü öffnen/schließen">
                    <svg viewBox="0 0 256 256" width="20" height="20" fill="currentColor" aria-hidden="true">
                        <path d="M224 128a8 8 0 0 1-8 8h-80v80a8 8 0 0 1-16 0v-80H40a8 8 0 0 1 0-16h80V40a8 8 0 0 1 16 0v80h80a8 8 0 0 1 8 8Z" />
                    </svg>
                </button>
            </h3>

            <ul class="footer-shop-links">
                <li><a href="#">Impressum</a></li>
                <li><a href="#">Datenschutz</a></li>
                <li><a href="#">Kontakt</a></li>
            </ul>
        </div>
    </div>

    <div>
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
    </div>


    <script>
        const footerMenuToggle = document.querySelector('.footer-menu-toggle');
        const footerShopLinks = document.querySelectorAll('.footer-shop-links');

        document.querySelectorAll('.footer-menu-toggle').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', () => {
                const parentDiv = toggleBtn.closest('div');
                const childShopLinks = parentDiv.querySelector('.footer-shop-links');
                if (childShopLinks) {
                    childShopLinks.classList.toggle('footer-shop-links-active');
                }
            });
        });
    </script>
</footer>