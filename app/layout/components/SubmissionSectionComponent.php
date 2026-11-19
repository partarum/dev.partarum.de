<?php
declare(strict_types=1);

namespace app\layout\components;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class SubmissionSectionComponent implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly string $formId = "startForm"
    ) {}

    public function render(): string {

        $escapedFormId = $this->escape($this->formId);

        return <<<HTML
        <article id="pfmFinalStage" class="pfm-final-stage">
            <header>
                <h2>Zum Abschluss</h2>
            </header>
            <section class="pfm-submission">
                <header>
                    <h3> Eingaben bestätigen und Formular absenden</h3>
                </header>
                <div class="pfm-wp-single pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-flex-gap-item w100-centerBox">
                        <legend>Anmerkungen und Bestätigung</legend>
                        <ul class="pfm-tl-column pre-form-task-list">
                            <li class="pfm-ts-column">
                                <label for="a_1_1" class="pre-flex-gap-item"> Bitte fügen Sie hier Anmerkungen hinzu</label>
                                <textarea id="a_1_1" name="a_1_n_1" rows="10" class="pre-flex-gap-item"></textarea>
                            </li>
                            <li>
                                <ul class="pfm-submit-box">
                                    <li class="pfm-ts-column">
                                        <label for="e_1_1" class="pre-flex-gap-item"> Bitte tragen Sie hier ihre E-Mail-Adresse ein:</label>
                                        <input id="e_1_1" name="e_1_n_1" type="email" placeholder="email@example.com" required class="pre-flex-gap-item">
                                    </li>
                                    <li class="pfm-ts-submit-button">
                                        <button form="{$escapedFormId}" type="submit">Absenden</button>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </section>
        </article>
HTML;
    }
}
