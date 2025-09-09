<?php

$my_theme = $theme;
$theme_screenshot = get_template_directory_uri() . '/screenshot.png';
$plugin_url = admin_url('admin.php?page=bitka_dashboard');

$system_info = [
  'wp_version'       => get_bloginfo('version'),
  'php_version'             => phpversion(),
  'woocommerce_version'     => defined('WC_VERSION') ? WC_VERSION : 'Nicht installiert',
  'active_plugins_count'    => function_exists('get_option') ? (is_array($active_plugins = get_option('active_plugins')) ? count($active_plugins) : 0) : 'N/A',
  'memory_limit'            => ini_get('memory_limit'),
  'upload_max_filesize'     => ini_get('upload_max_filesize'),
  'max_execution_time'      => ini_get('max_execution_time'),
  'site_url'                => get_site_url(),
];



?>

<section id="bitka-dashboard">
  <header class="bitka-dashboard-header">
    <a href="<?= esc_url($plugin_url); ?>">
      <svg xmlns="http://www.w3.org/2000/svg" class="bitka-dashboard__icon" fill="currentColor" viewBox="0 0 500 500">
        <path d="m4.3 178.7.3 175.8 2.6 10.6c12 48.8 37.9 84.4 78.6 108.3 18.1 10.6 43.6 19.8 59.9 21.7 6.4.7 43 1.2 106.3 1.3 89.4.1 97.2 0 105.5-1.7 18.7-3.8 30.7-7.8 46.7-15.9 19.2-9.7 34.8-21.7 49.4-37.8 19.5-21.7 30.4-42.2 38.8-73l3.1-11.5.3-120.8.3-120.7h-72.8l-.6-8.3c-1.2-16.9-7.8-36.2-17.2-50.1-19.4-29-55.1-46.2-108.2-52-12.2-1.4-34-1.6-153.8-1.6H3.9l.4 175.7zM278.5 85c38.3 7.1 60.7 27.8 64 59.1l.7 6.7-36.9 53.8c-51.1 74.5-59.7 87.3-59 88 .8.8 15.6-5.2 110.7-44.3 34.6-14.3 54.8-22.3 56.1-22.3.8 0 .9 17.9.6 60.2-.5 64.6-.8 69.3-5.8 79-3.4 6.8-10.6 17.3-15.5 22.7-10.3 11.5-32.3 24-46.4 26.3-11 1.9-183.5 1.8-193.6 0-3.8-.7-10.8-3-15.5-5.1-24.9-11-41.3-29.1-50.2-55.1l-2.6-7.5-.4-131.8-.3-131.9 94 .5c63.1.4 96.1.9 100.1 1.7z" />
      </svg>
    </a>

    <nav class="bitka-dashboard__nav">
      <a href="#">Einstellungen</a>
      <a href="#">Benutzerverwaltung</a>
      <a href="#">Berichte</a>
    </nav>

  </header>

  <main class="bitka-dashboard-main">
    <section class="bitka-dashboard__section">
      <h2>Bitka Dashboard</h2>

      <?= var_dump($support); ?>

    </section>

    <aside class="bitka-dashboard__sidebar">
      <?= admin_tpl_part('support.php', $support); ?>




      <h3>Theme</h3>

      <div class="bitka-card bitka-theme-card">
        <div style="position:relative; width:100%;">
          <img src="<?= $theme_screenshot ?>" alt="Theme Screenshot" style="box-shadow:0 1px 4px rgba(0,0,0,0.10); margin-bottom:0.7em; width:100%; border-radius:6px;" />
          <a href="<?= admin_url('themes.php'); ?>" style="position:absolute; inset:auto; right: 1rem; bottom: 1.5rem; background:#0073aa; color:#fff; padding:0.35em 1em; border-radius:4px; text-decoration:none; font-size:0.93em; transition:background 0.2s; opacity:0.93;">
            Themes
          </a>
        </div>
        <div style="font-weight:600; font-size:1.1em; color:#222; margin-bottom:0.2em;">
          <?= $my_theme['Name']; ?>
          <span style="background:#eee; color:#888; font-size:0.85em; border-radius:4px; padding:0.1em 0.6em; margin-left:0.4em;">
            <?= $my_theme['Version']; ?>
          </span>
        </div>
        <div style="font-size:0.93em; color:#555; margin-bottom:0.3em;">
          <span style="color:#0073aa;"><?= $my_theme['Author']; ?></span>
        </div>
      </div>

      <?= admin_tpl_part('system-info.php', $system_info); ?>

    </aside>
  </main>



</section>

<footer class="bitka-dashboard-footer" style="text-align:center; color:#888; font-size:0.97em;">
  &copy; <?= date('Y'); ?> Bitka – Theme und Dashboard. Alle Rechte vorbehalten.
</footer>