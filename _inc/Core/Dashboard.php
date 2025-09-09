<?php

namespace Bitka\Core;

use DataLoader;

class Dashboard
{
  private DataLoader $dataLoader;

  public function __construct()
  {
    $this->dataLoader = new DataLoader();
    add_action('admin_menu', [$this, 'addAdminPage']);
    add_action('admin_enqueue_scripts', [$this, 'enqueueDashboardCss']);
    add_action('admin_footer', [$this, 'removeAdminFooterOnDashboard']);
  }

  /**
   * Legt eine eigene Admin-Seite im Backend an
   */
  public function addAdminPage(): void
  {
    add_menu_page(
      __('Bitka Dashboard', 'bitka-one'),
      __('Bitka', 'bitka-one'),
      'manage_options',
      'bitka_dashboard',
      [$this, 'renderAdminPage'],
      $this->getDashboardSvg(), // Icon als Data-URL
      3
    );
  }

  /**
   * Gibt das SVG-Icon für das Dashboard zurück.
   */
  private function getDashboardSvg(): string
  {
    $svg = <<<SVG
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 500 500">
      <path d="m4.3 178.7.3 175.8 2.6 10.6c12 48.8 37.9 84.4 78.6 108.3 18.1 10.6 43.6 19.8 59.9 21.7 6.4.7 43 1.2 106.3 1.3 89.4.1 97.2 0 105.5-1.7 18.7-3.8 30.7-7.8 46.7-15.9 19.2-9.7 34.8-21.7 49.4-37.8 19.5-21.7 30.4-42.2 38.8-73l3.1-11.5.3-120.8.3-120.7h-72.8l-.6-8.3c-1.2-16.9-7.8-36.2-17.2-50.1-19.4-29-55.1-46.2-108.2-52-12.2-1.4-34-1.6-153.8-1.6H3.9l.4 175.7zM278.5 85c38.3 7.1 60.7 27.8 64 59.1l.7 6.7-36.9 53.8c-51.1 74.5-59.7 87.3-59 88 .8.8 15.6-5.2 110.7-44.3 34.6-14.3 54.8-22.3 56.1-22.3.8 0 .9 17.9.6 60.2-.5 64.6-.8 69.3-5.8 79-3.4 6.8-10.6 17.3-15.5 22.7-10.3 11.5-32.3 24-46.4 26.3-11 1.9-183.5 1.8-193.6 0-3.8-.7-10.8-3-15.5-5.1-24.9-11-41.3-29.1-50.2-55.1l-2.6-7.5-.4-131.8-.3-131.9 94 .5c63.1.4 96.1.9 100.1 1.7z"/>
    </svg>
    SVG;

    return 'data:image/svg+xml;base64,' . base64_encode($svg);  
  }

  /**
   * Callback für die Admin-Seite
   */
  public function renderAdminPage(): void
  {
    $template = get_template_directory() . '/templates/admin/bitka-dashboard.php';

    if (!file_exists($template)) {
      echo '<div class="wrap"><h1>Bitka Dashboard</h1><p>Willkommen im Bitka Admin-Bereich!</p></div>';
      return;
    }

    $support = $this->dataLoader->loadJson('support.json');

    $theme = wp_get_theme();
    $theme_screenshot = get_template_directory_uri() . '/screenshot.png';
    include $template;
  }

  /**
   * Lädt das Dashboard-CSS nur auf der eigenen Admin-Seite
   */
  public function enqueueDashboardCss(string $hook): void
  {
    if ($hook === 'toplevel_page_bitka_dashboard') {
      wp_enqueue_style(
        'bitka-dashboard-css',
        get_template_directory_uri() . '/assets/css/admin/dashboard.css',
        [],
        wp_get_theme()->get('Version')
      );
      wp_enqueue_style(
        'bitka-support-card-css',
        get_template_directory_uri() . '/assets/css/support-card.css',
        [],
        wp_get_theme()->get('Version')
      );
    }
  }

  /**
   * Entfernt den Admin-Footer nur auf der Bitka Dashboard-Seite
   */
  public function removeAdminFooterOnDashboard(): void
  {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_bitka_dashboard') {
      echo '<style>#wpfooter {display:none !important;}</style>';
    }
  }
}
