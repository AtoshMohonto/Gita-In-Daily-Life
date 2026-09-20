<?php
/**
 * Reusable "Wisdom Card" component (§46). Expects a $card array:
 * quote, source, key_idea, apply, link (all optional except quote).
 */
$card = $card ?? [];
?>
<div class="wisdom-card shadow-sm">
  <div class="badge-label text-muted mb-2"><?= e(APP_NAME) ?></div>
  <blockquote class="font-serif fs-5 mb-3" style="color:var(--gidl-text);">
    “<?= e($card['quote'] ?? '') ?>”
  </blockquote>
  <?php if (!empty($card['source'])): ?>
    <p class="small text-muted mb-3">Source: <?= e($card['source']) ?></p>
  <?php endif; ?>
  <?php if (!empty($card['key_idea'])): ?>
    <p class="mb-2"><strong>Key Idea:</strong> <?= e($card['key_idea']) ?></p>
  <?php endif; ?>
  <?php if (!empty($card['apply'])): ?>
    <p class="mb-3"><strong>Apply Today:</strong> <?= e($card['apply']) ?></p>
  <?php endif; ?>
  <?php if (!empty($card['link'])): ?>
    <a href="<?= e($card['link']) ?>" class="btn btn-sm btn-outline-primary"><?= e(__t('action.read_more')) ?></a>
  <?php endif; ?>
</div>
