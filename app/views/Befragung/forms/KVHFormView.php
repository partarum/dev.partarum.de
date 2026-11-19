<?php
declare(strict_types=1);

namespace app\views\Befragung\forms;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class KVHFormView implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly string $displayDate = ""
    ) {}

    public function render(): string {

        $escapedDisplayDate = $this->escape($this->displayDate);

        return <<<HTML
                <article class="pfm-area pre-form-area">
            <header>
                <h2>Marktverlauf</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <header>
                    <h3>Allgemein</h3>
                </header>
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Allgemeine Geschäftslage
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Allgemeine Geschäftslage</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_1_1" class="pfm-ts-label pre-form-task-label">gut</label>
                                <input type="radio" id="b_1_1" value="100" name="b_1_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_1_2" class="pfm-ts-label pre-form-task-label">befriedigend</label>
                                <input type="radio" id="b_1_2" value="010" name="b_1_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_1_3" class="pfm-ts-label pre-form-task-label">schlecht</label>
                                <input type="radio" id="b_1_3" value="001" name="b_1_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Lagerbestand Rohware
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Lagerbestand Rohware</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_2_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_2_1" value="100" name="b_2_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_2_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_2_2" value="010" name="b_2_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_2_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_2_3" value="001" name="b_2_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <!--
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Lagerbestand Fertigware
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Lagerbestand Fertigware</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_3_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_3_1" value="100" name="b_3_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_3_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_3_2" value="010" name="b_3_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_3_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_3_3" value="001" name="b_3_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Auftragsbestand
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Auftragsbestand</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_4_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_4_1" value="100" name="b_4_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_4_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_4_2" value="010" name="b_4_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_4_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_4_3" value="001" name="b_4_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <!--
                -->
            </section>
            <!--
            -->
            <section class="pfm-section pre-form-team">
                <header>
                    <h3>Einkaufspreis</h3>
                </header>
                <div class="pfm-wp-group pfm-t-medium pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-t-row pre-flex-gap-item w100-centerBox">
                        <legend class="pfm-tt-center">
                            Fi/Ta-Rohware KD
                        </legend>
                        <aside class="pfm-ts-headline">
                            <h4>Einkaufspreis von Fi/Ta-Rohware KD</h4>
                            <ul class="pfm-ts-headline-list">
                                <li>in Euro/m³</li>
                                <li>für NSi - Qualitäten</li>
                                <li>fakturierte Preise im Monatsmittel</li>
                                <li>franko</li>
                            </ul>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_1" class="pfm-ts-label pre-form-task-label">6er</label>
                                <input type="text" id="b_5_1" name="b_5_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_2" class="pfm-ts-label pre-form-task-label">8er</label>
                                <input type="text" id="b_5_2" name="b_5_n_2" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_3" class="pfm-ts-label pre-form-task-label">10er</label>
                                <input type="text" id="b_5_3" name="b_5_n_3" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_4" class="pfm-ts-label pre-form-task-label">12er</label>
                                <input type="text" id="b_5_4" name="b_5_n_4" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_5" class="pfm-ts-label pre-form-task-label">14er</label>
                                <input type="text" id="b_5_5" name="b_5_n_5" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </section>
            <!--
            -->
            <section class="pfm-section pre-form-team">
                <header>
                    <h3>Verkaufspreis</h3>
                </header>
                <div class="pfm-wp-group pfm-t-medium pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-t-row pre-flex-gap-item w100-centerBox">
                        <legend class="pfm-tt-center">
                            Fi/Ta NSi-KVH an den Handel
                        </legend>
                        <aside class="pfm-ts-headline">
                            <h4>Verkaufspreise Fi/Ta NSi-KVH an den Handel</h4>
                            <ul class="pfm-ts-headline-list">
                                <li>in Euro/m³</li>
                                <li>ladungsweise bzw. min. 10m³</li>
                                <li>fakturierte Preise im Monatsmittel</li>
                                <li>franko</li>
                                <li>vor Abzug von Skonto, Boni etc...</li>
                            </ul>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_1" class="pfm-ts-label pre-form-task-label">6er</label>
                                <input type="text" id="b_6_1" name="b_6_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_2" class="pfm-ts-label pre-form-task-label">8er</label>
                                <input type="text" id="b_6_2" name="b_6_n_2" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_3" class="pfm-ts-label pre-form-task-label">10er</label>
                                <input type="text" id="b_6_3" name="b_6_n_3" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_4" class="pfm-ts-label pre-form-task-label">12er</label>
                                <input type="text" id="b_6_4" name="b_6_n_4" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_5" class="pfm-ts-label pre-form-task-label">14er</label>
                                <input type="text" id="b_6_5" name="b_6_n_5" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_6" class="pfm-ts-label-long pre-form-task-label">Liste (3-5m³)</label>
                                <input type="text" id="b_6_6" name="b_6_n_6" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_7" class="pfm-ts-label-long pre-form-task-label">Liste (5-10m³)</label>
                                <input type="text" id="b_6_7" name="b_6_n_7" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_8" class="pfm-ts-label-long pre-form-task-label">Liste (>10m³)</label>
                                <input type="text" id="b_6_8" name="b_6_n_8" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pfm-t-row pre-flex-gap-item w100-centerBox">
                        <legend class="pfm-tt-center">
                            Fi/Ta NSi-KVH an die Direktabnehmer
                        </legend>
                        <aside class="pfm-ts-headline">
                            <h4>Verkaufspreise Fi/Ta NSi-KVH an die Direktabnehmer</h4>
                            <ul class="pfm-ts-headline-list">
                                <li>in Euro/m³</li>
                                <li>ladungsweise bzw. min. 10m³</li>
                                <li>fakturierte Preise im Monatsmittel</li>
                                <li>franko</li>
                                <li>vor Abzug von Skonto, Boni etc...</li>
                            </ul>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_1" class="pfm-ts-label pre-form-task-label">6er</label>
                                <input type="text" id="b_7_1" name="b_7_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_2" class="pfm-ts-label pre-form-task-label">8er</label>
                                <input type="text" id="b_7_2" name="b_7_n_2" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_3" class="pfm-ts-label pre-form-task-label">10er</label>
                                <input type="text" id="b_7_3" name="b_7_n_3" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_4" class="pfm-ts-label pre-form-task-label">12er</label>
                                <input type="text" id="b_7_4" name="b_7_n_4" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_5" class="pfm-ts-label pre-form-task-label">14er</label>
                                <input type="text" id="b_7_5" name="b_7_n_5" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_6" class="pfm-ts-label-long pre-form-task-label">Liste (3-5m³)</label>
                                <input type="text" id="b_7_6" name="b_7_n_6" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_7" class="pfm-ts-label-long pre-form-task-label">Liste (5-10m³)</label>
                                <input type="text" id="b_7_7" name="b_7_n_7" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_8" class="pfm-ts-label-long pre-form-task-label">Liste (>10m³)</label>
                                <input type="text" id="b_7_8" name="b_7_n_8" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </section>
            <!--
            -->
            <section class="pfm-section pre-form-team">
                <header>
                    <h3>Produktionsmenge</h3>
                </header>
                <div class="pfm-wp-group pfm-t-medium pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-t-row pre-flex-gap-item w100-centerBox">
                        <legend class="pfm-tt-center" style="white-space: normal">
                            Produktionsmenge Fi/Ta NSi-KVH {$escapedDisplayDate}
                        </legend>
                        <ul class="pfm-task-list pre-form-task-list" style="margin-top: 16px; margin-bottom: 16px;">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_1" class="pfm-ts-label pre-form-task-label">in m³</label>
                                <input type="text" id="b_8_1" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </section>
        </article>
        
HTML;
    }
}
