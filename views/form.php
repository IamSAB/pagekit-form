<div id="<?= $id ?>" class="sab-inline">
    <form <?= $class ? "class=\"$class\"" : '' ?> v-validator="form" @submit="submit | valid" v-cloak>
        <fieldset :disabled="status > 0">
            <?= $content ?>
        </fieldset>
        <invisible-recaptcha
            v-ref:recaptcha
            @verify="onCaptchaVerified"
            @expired="onCaptchaExpired"
            @error="onCaptchaError">
        </invisible-recaptcha>
    </form>
    <div class="sab-position-center" v-if="status == 1">
        <svg class="sab-loader" width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
            <g><circle cx="0" cy="0" r="70" fill="none" stroke-width="2"/></g>
        </svg>
    </div>
</div>