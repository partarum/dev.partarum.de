<?php
declare(strict_types=1);

namespace app\views\Befragung\forms;

use app\core\Contracts\RenderableInterface;

class LSHFormView implements RenderableInterface {

    public function __construct(
        private readonly string $displayDate = ""
    ) {}

    public function render(): string {

        return <<<'HTML'
                <article class="pfm-area pre-form-area">
            <header>
                <h2>Marktverlauf</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <header>
                    <h3>Marktverlauf im Berichtsmonat</h3>
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
                    <fieldset class="pfm-worker pre-flex-gap-item">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Marktverlauf
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_0_1" class="pfm-ts-label pre-form-task-label">lebhaft</label>
                                        <input type="radio" id="b_2_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_0_2" class="pfm-ts-label pre-form-task-label">rege</label>
                                        <input type="radio" id="b_2_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_0_3" class="pfm-ts-label pre-form-task-label">gemäßigt</label>
                                        <input type="radio" id="b_2_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_0_4" class="pfm-ts-label pre-form-task-label">langsam</label>
                                        <input type="radio" id="b_2_0_4" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_0_5" class="pfm-ts-label pre-form-task-label">schleppend</label>
                                        <input type="radio" id="b_2_0_5" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Inland</legend>
                                <aside class="ie-legend">
                                    <span>Inland</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_1_1" aria-label="lebhaft" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_1_1" value="10000" name="b_2_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_1_2" aria-label="rege" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_1_2" value="01000" name="b_2_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_1_3" aria-label="gemäßigt" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_1_3" value="00100" name="b_2_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_1_4" aria-label="langsam" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_1_4" value="00010" name="b_2_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_1_5" aria-label="schleppend" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_1_5" value="00001" name="b_2_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Ausland</legend>
                                <aside class="ie-legend">
                                    <span>Ausland</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_2_1" value="10000" name="b_2_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_2_2" value="01000" name="b_2_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_2_3" value="00100" name="b_2_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_2_4" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_2_4" value="00010" name="b_2_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_2_2_5" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_2_2_5" value="00001" name="b_2_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
                <!--
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Schnittholzproduktion
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Schnittholzproduktion</h4>
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
                            Lagerbestand unverkaufter Schnittware
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Lagerbestand unverkaufter Schnittware</h4>
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
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-flex-gap-item">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Auftragseingang
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Auftragseingang</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_5_1" value="100" name="b_5_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_5_2" value="010" name="b_5_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_5_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_5_3" value="001" name="b_5_n_1" class="pfm-ts-input pre-form-task-input">
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
                                <label for="b_6_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_6_1" value="100" name="b_6_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_6_2" value="010" name="b_6_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_6_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_6_3" value="001" name="b_6_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <!--
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-flex-gap-item">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Erzeugerpreise
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Erzeugerpreise</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_7_1" value="100" name="b_7_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_7_2" value="010" name="b_7_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_7_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_7_3" value="001" name="b_7_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Konkurrenzsituation
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Konkurrenzsituation</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_1" class="pfm-ts-label pre-form-task-label">verschärft</label>
                                <input type="radio" id="b_8_1" value="100" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_8_2" value="010" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_3" class="pfm-ts-label pre-form-task-label">abgeschwächt</label>
                                <input type="radio" id="b_8_3" value="001" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <!--
                -->

                <!--
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker form-task-column pre-form-worker w100-centerBox">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Marktlage in
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktlage in</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_1" class="pfm-ts-label pre-form-task-label">gut</label>
                                        <input type="radio" id="b_9_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_2" class="pfm-ts-label pre-form-task-label">befriedigend</label>
                                        <input type="radio" id="b_9_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_3" class="pfm-ts-label pre-form-task-label">schlecht</label>
                                        <input type="radio" id="b_9_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Europa</legend>
                                <aside class="ie-legend">
                                    <span>Europa</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_1" value="100" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_2" value="010" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_3" value="001" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">USA</legend>
                                <aside class="ie-legend">
                                    <span>USA</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_1" value="100" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_2" value="010" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_3" value="001" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Asien</legend>
                                <aside class="ie-legend">
                                    <span>Asien</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_3_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_3_1" value="100" name="b_9_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_3_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_3_2" value="010" name="b_9_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_3_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_3_3" value="001" name="b_9_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Geschäftslage an den Nebenproduktmärkten
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Geschäftslage an den Nebenproduktmärkten</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_10_1" class="pfm-ts-label pre-form-task-label">gut</label>
                                <input type="radio" id="b_10_1" value="100" name="b_10_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_10_2" class="pfm-ts-label pre-form-task-label">befriedigend</label>
                                <input type="radio" id="b_10_2" value="010" name="b_10_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_10_3" class="pfm-ts-label pre-form-task-label">schlecht</label>
                                <input type="radio" id="b_10_3" value="001" name="b_10_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </section>
        </article>

        <!--
        /**
         *
         *
         */
        -->

        <article class="pfm-area pre-form-area">
            <header>
                <h2>Marktentwicklung</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <header>
                    <h3>Künftige Marktentwicklung (Prognosehorizont ca. 3 Monate)</h3>
                </header>
                <div class="pfm-wp-single pfm-t-medium pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker w100-centerBox">
                        <legend class="pfm-tt-center">
                            Rundholzpreis wird ...
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Rundholzpreis wird ...</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_11_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_11_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_11_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Buche</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_1_1" value="100" name="b_11_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_1_2" value="010" name="b_11_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_1_3" value="001" name="b_11_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Eiche</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_2_1" value="100" name="b_11_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_2_2" value="010" name="b_11_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_2_3" value="001" name="b_11_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">sLbh</legend>
                                <aside class="ie-legend">
                                    <span>6</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_3_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_3_1" value="100" name="b_11_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_3_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_3_2" value="010" name="b_11_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_3_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_11_3_3" value="001" name="b_11_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Schnittholznachfrage wird ...
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Schnittholznachfrage wird ...</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_12_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_12_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_12_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Buche</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_1_1" value="100" name="b_12_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_1_2" value="010" name="b_12_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_1_3" value="001" name="b_12_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Eiche</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_2_1" value="100" name="b_12_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_2_2" value="010" name="b_12_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_2_3" value="001" name="b_12_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">sLbh</legend>
                                <aside class="ie-legend">
                                    <span>6</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_3_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_3_1" value="100" name="b_12_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_3_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_3_2" value="010" name="b_12_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_12_3_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_12_3_3" value="001" name="b_12_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Schnittholzpreis wird ...
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Schnittholzpreis wird ...</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_13_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_13_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_13_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Buche</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_1_1" value="100" name="b_13_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_1_2" value="010" name="b_13_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_1_3" value="001" name="b_13_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Eiche</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_2_1" value="100" name="b_13_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_2_2" value="010" name="b_13_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_2_3" value="001" name="b_13_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">sLbh</legend>
                                <aside class="ie-legend">
                                    <span>6</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_3_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_3_1" value="100" name="b_13_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_3_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_3_2" value="010" name="b_13_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_13_3_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_13_3_3" value="001" name="b_13_3_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
            </section>
        </article>
        
HTML;
    }
}
