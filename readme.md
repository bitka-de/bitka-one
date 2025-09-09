
# Bitka One Theme

Ein modernes, sauberes WordPress-Theme mit WooCommerce-Support und eigenem Admin-Dashboard.

## Features

- **Custom Dashboard:** Übersichtliche Admin-Seite mit Theme- und Systeminfos
- **Support-Card:** Kontaktinfos, Support-Links, Ticket-System (Daten aus JSON)
- **WooCommerce-Integration:** Eigene WooCommerce-Templates und Anpassungen
- **Modernes CSS:** Responsive, modernes Design für Backend und Frontend
- **PHP 8, Strict Types:** Sauberer, wartbarer Code
- **Datenhandling:** Zentrale DataLoader-Klasse für JSON-Daten

## Verzeichnisstruktur

```
bitka-one/
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   └── support-card.css
│   ├── images/
│   └── js/
│       └── main.js
├── inc/
│   ├── core/
│   │   ├── Dashboard.php
│   │   └── DataLoader.php
│   └── Woo.php
├── templates/
│   ├── admin/
│   │   ├── bitka-dashboard.php
│   │   └── parts/
│   │       └── support.php
├── data/
│   └── support.json
├── functions.php
├── style.css
├── index.php
├── header.php
├── footer.php
├── woocommerce.php
├── screenshot.png
└── readme.md
```

## Installation

1. Theme-Ordner nach `/wp-content/themes/` kopieren
2. Im WordPress-Backend aktivieren
3. (Optional) WooCommerce installieren und aktivieren

## Entwicklung

- **CSS:** Anpassungen in `assets/css/` vornehmen
- **Dashboard:** Templates unter `templates/admin/` bearbeiten
- **Support-Daten:** `data/support.json` pflegen
- **PHP:** Alle Core-Funktionen in `inc/core/`

## Support & Kontakt

Support-Infos werden im Dashboard angezeigt (Daten aus `data/support.json`).

---

**Bitka One Theme** – entwickelt für moderne WordPress-Projekte.
