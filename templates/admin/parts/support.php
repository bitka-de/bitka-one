<h3 class="bitka-support-title">Support</h3>
<div class="bitka-card">
  <div class="bitka-support-card-header">
    <?php if (!empty($contact['person']['img'])): ?>
      <img class="bitka-support-avatar" src="<?= _echo($contact['person']['img'], 'url'); ?>" alt="Profilbild" />
    <?php else: ?>
      <div class="bitka-support-avatar bitka-support-avatar--placeholder">
        <span><?= strtoupper(substr($contact['person']['name'], 0, 1)); ?></span>
      </div>
    <?php endif; ?>

    <div class="bitka-support-meta">
      <div class="bitka-support-name">
        <?= _echo($contact['person']['name']); ?>
      </div>
      <div class="bitka-support-company">
        <?= _echo($company); ?>
      </div>
    </div>
  </div>

  <div class="bitka-support-card-footer">
    <div class="bitka-support-actions">
      <a class="bitka-support-action bitka-support-action--mail" href="mailto:<?= esc_attr($email); ?>">
        <svg viewBox="0 0 256 256">
          <path d="M224 48H32a8 8 0 0 0-8 8v136a16 16 0 0 0 16 16h176a16 16 0 0 0 16-16V56a8 8 0 0 0-8-8Zm-96 85.15L52.57 64h150.86ZM98.71 128 40 181.81V74.19Zm11.84 10.85 12 11.05a8 8 0 0 0 10.82 0l12-11.05 58 53.15H52.57ZM157.29 128 216 74.18v107.64Z" />
        </svg>
        E-Mail
      </a>
      <?php if (!empty($contact['phone'])): ?>
        <a class="bitka-support-action bitka-support-action--phone" href="tel:<?= esc_attr($contact['phone']); ?>">
          <svg viewBox="0 0 256 256">
            <path d="m222.37 158.46-47.11-21.11-.13-.06a16 16 0 0 0-15.17 1.4 8.12 8.12 0 0 0-.75.56L134.87 160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16 16 0 0 0 1.32-15.06v-.12L97.54 33.64a16 16 0 0 0-16.62-9.52A56.26 56.26 0 0 0 32 80c0 79.4 64.6 144 144 144a56.26 56.26 0 0 0 55.88-48.92 16 16 0 0 0-9.51-16.62ZM176 208A128.14 128.14 0 0 1 48 80a40.2 40.2 0 0 1 34.87-40 .61.61 0 0 0 0 .12l21 47-20.67 24.74a6.13 6.13 0 0 0-.57.77 16 16 0 0 0-1 15.7c9.06 18.53 27.73 37.06 46.46 46.11a16 16 0 0 0 15.75-1.14 8.44 8.44 0 0 0 .74-.56L168.89 152l47 21.05h.11A40.21 40.21 0 0 1 176 208Z" />
          </svg>
          Anrufen
        </a>
      <?php endif; ?>
      <?php if (!empty($ticket_url)): ?>
        <a class="bitka-support-action bitka-support-action--ticket" href="<?= esc_url($ticket_url); ?>" target="_blank">
          <svg class="bitka-support-action-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="7" width="18" height="13" rx="2" />
            <path d="M16 3v4M8 3v4M3 11h18" />
          </svg>
          Ticket
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>