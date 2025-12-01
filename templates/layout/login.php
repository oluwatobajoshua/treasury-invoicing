<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Travel Request System</title>
    <?= $this->Html->css(['sunbeth-theme']) ?>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <?= $this->Flash->render() ?>
        <script>
            (function(){
                try {
                    const params = new URLSearchParams(window.location.search);
                    const hasCode = params.has('code');
                    const hasError = params.has('error');
                    const hasSessionState = params.has('session_state');
                    console.info('[Auth] Stage: login-layout load', { path: location.pathname });
                    if (hasCode || hasError || hasSessionState) {
                        console.info('[Auth] Stage: returned-from-microsoft', {
                            codePresent: hasCode,
                            errorPresent: hasError,
                            sessionStatePresent: hasSessionState
                        });
                    } else {
                        console.info('[Auth] Stage: landing-on-login');
                    }
                } catch(e) {}
            })();
        </script>
    <?php if (\Cake\Core\Configure::read('debug')): ?>
    <?php
        $azure = \Cake\Core\Configure::read('Azure') ?? [];
        $tenant = $azure['tenantId'] ?? '';
        $clientId = $azure['clientId'] ?? '';
        $redirectUri = $azure['redirectUri'] ?? '';
        $adminConsentUrl = ($tenant && $clientId) 
            ? 'https://login.microsoftonline.com/' . h($tenant) . '/adminconsent?client_id=' . h($clientId) . '&redirect_uri=' . urlencode($redirectUri)
            : '';
    ?>
    <div style="background:#fff7ed;border:1px solid #fdba74;color:#7c2d12;padding:10px 12px;margin:10px;font-size:13px;border-radius:6px">
        <strong>Debug:</strong> If sign-in loops, grant Graph consent
        <?php if ($adminConsentUrl): ?>
        <a href="<?= $adminConsentUrl ?>" target="_blank" rel="noopener">(admin consent)</a>
        <?php endif; ?>
        and ensure scopes include <code>User.Read</code>.
    </div>
    <?php endif; ?>
    <?= $this->fetch('content') ?>
        <?php
            // Fallback loader to log if dev-logger fails to load (404, path issues)
            $loggerUrl = $this->Url->assetUrl('js/dev-logger.js');
        ?>
        <script>
            (function(){
                var s = document.createElement('script');
                s.src = '<?= h($loggerUrl) ?>';
                s.onload = function(){ console.info('[App] dev-logger loaded'); };
                s.onerror = function(){ console.warn('[App] dev-logger failed to load', { url: '<?= h($loggerUrl) ?>' }); };
                document.body.appendChild(s);
            })();
        </script>
</body>
</html>
