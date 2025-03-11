<?php
$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
if ($config->userLibCaptcha):
    switch ($config->userLibCaptchaType):
        case 'cloudflare':
            $options = $config->userLibCaptchaOptions["cloudflare"];
            $cloudflareSiteKey = $options["siteKey"];

            $request = \Config\Services::request();
            $locale = $request->getLocale();
?>
            <div class="cf-turnstile" data-language="<?= $locale ?>" data-sitekey="<?= $cloudflareSiteKey ?>" data-callback="javascriptCallback"></div>
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js"></script>
            <button type="submit"><?= $label ?></button>
        <?php
            break;

        case 'recaptcha-v3':
            $options = $config->userLibCaptchaOptions["recaptcha-v3"];
            $captchaV3SiteKey = $options["siteKey"];
        ?>
            <button class="g-recaptcha" data-sitekey="<?= $captchaV3SiteKey ?>" data-callback="submitForm"><?= $label ?></button>

            <script src='https://www.google.com/recaptcha/api.js'></script>
            <script>
                function submitForm() {
                    document.getElementById('userForm').submit();
                }
            </script>
        <?php
            break;
        default:
        ?>
            <button type="submit"><?= $label ?></button>
    <?php
            break;
    endswitch;
    ?>
    <noscript><?= lang('Messages.enablejs'); ?></noscript>
<?php else: ?>
    <button type="submit"><?= $label ?></button>
<?php endif; ?>