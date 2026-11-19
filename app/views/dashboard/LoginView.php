<?php
declare(strict_types=1);

namespace app\views\dashboard;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class LoginView implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly string $action = "dashboard",
        private readonly string $formId = "login"
    ) {}

    public function render(): string {

        $escapedAction = $this->escape($this->action);
        $escapedFormId = $this->escape($this->formId);

        return <<<HTML
<body>
<header id="header"></header>
<main id="content" class="single-box-center-small">
    <form method="POST" action="{$escapedAction}" class="login-form login-form--red" id="{$escapedFormId}">
        <fieldset class="login-form__container">
            <section class="login-form__field">
                <label for="user">Email / Name</label>
                <input type="text" id="user" name="user" autofocus required>
            </section>
            <section class="login-form__field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </section>
            <section class="login-form__actions">
                <button class="woodenButton" type="submit" form="{$escapedFormId}">Anmelden</button>
            </section>
        </fieldset>
    </form>
</main>
<footer id="footer"></footer>
</body>
HTML;
    }
}
