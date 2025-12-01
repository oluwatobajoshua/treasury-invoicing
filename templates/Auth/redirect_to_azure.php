<?php
/**
 * Redirect splash to allow client-side logs before navigation
 */
?>
<div style="max-width:600px;margin:40px auto;text-align:center;font-family:system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;">
  <h2>Redirecting to Microsoft...</h2>
  <p>Starting secure sign-in via Microsoft. This should only take a moment.</p>
  <p><a href="<?= h($authUrl) ?>" id="redirect-link">Continue to Microsoft</a></p>
  <p style="color:#777;font-size:13px;">If you are not redirected automatically, click the link above.</p>
  <?php if (isset($autoRedirect) && !$autoRedirect): ?>
    <div style="margin-top:12px;padding:10px 12px;border:1px solid #f0c36d;background:#fff8e1;color:#5d4037;border-radius:6px;text-align:left;">
      <strong>Debug:</strong> Auto-redirect paused after a sign-in error. Check Azure App Registration:
      <ul style="margin:6px 0 0 18px;padding:0;">
        <li>Ensure redirect URI matches exactly: <code><?= h($authUrl) ?></code> host/base.</li>
        <li>Scopes include <code>User.Read</code> and admin consent is granted (if required).</li>
        <li>If this is a Web (confidential) app, set client secret and enable it in env.</li>
      </ul>
    </div>
  <?php endif; ?>
</div>
<script>
  try {
    console.info('[Auth] Stage: redirect-splash loaded');
    console.debug('[Auth] Next: redirecting to Azure AD', {
      href: <?= json_encode($authUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
      time: new Date().toISOString()
    });
  } catch (e) {}
  <?php if (!isset($autoRedirect) || $autoRedirect): ?>
    setTimeout(function(){
      window.location.href = <?= json_encode($authUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    }, 300);
  <?php endif; ?>
</script>
