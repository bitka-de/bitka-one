<h3>System-Informationen</h3>
<div class="bitka-card bitka-info-box">
  <ul>
    <?php
    $systemInfo = [
      'WordPress Version'    => $wp_version ?? 'n/a',
      'PHP Version'          => $php_version ?? 'n/a',
      'WooCommerce'          => $woocommerce_version ?? 'n/a',
      'Aktive Plugins'       => $active_plugins_count ?? 'n/a',
      'Memory Limit'         => $memory_limit ?? 'n/a',
      'Upload-Limit'         => $upload_max_filesize ?? 'n/a',
      'Max. Ausführungszeit' => isset($max_execution_time) ? "{$max_execution_time} Sek." : 'n/a',
      'Seiten-URL'           => $site_url ?? 'n/a',
    ];

    foreach ($systemInfo as $label => $value): ?>
      <li>
        <strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>:</strong>
        <?php if ($label === 'Seiten-URL'): ?>
          <span style="word-break:break-all;"><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></span>
        <?php else: ?>
          <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
