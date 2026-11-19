<?php
declare(strict_types=1);

namespace app\views\Befragung\forms;

use app\core\Contracts\RenderableInterface;

class RHBFormView implements RenderableInterface {

    public function __construct(
        private readonly string $displayDate = ""
    ) {}

    public function render(): string {

        return <<<'HTML'
                <article class="pfm-area pre-form-area">
            <header>
                <h2>Teil 1/6 - Marktanalyse</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <h3>Allgemeine Informationen</h3>
                <div class="pfm-wp-single pfm-t-medium pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Allgemeine Geschäftslage</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Allgemeine Geschäftslage</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_1_1" class="pfm-ts-label pre-form-task-label">gut</label>
                                <input id="b_1_1" name="b_1_n_1" value="100" type="radio" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_1_2" class="pfm-ts-label pre-form-task-label">befriedigend</label>
                                <input id="b_1_2" name="b_1_n_1" value="010" type="radio" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_1_3" class="pfm-ts-label pre-form-task-label">schlecht</label>
                                <input id="b_1_3" name="b_1_n_1" value="001" type="radio" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </section>
        </article>
        <article class="pfm-area pre-form-area">
            <header>
                <h2>Teil 2/6 - Marktanalyse Fichte</h2>
            </header>
            <section id="b_2" class="pfm-section pre-form-team">
                <h3>Fichte</h3>
                <div class="pfm-table pre-flex-gap-item">
                    <fieldset id="b_2_1" class="pfm-worker pfm-table-content">
                        <legend>Marktverlauf in der Berichtszeit</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf in der Berichtszeit</h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1">
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li class="pfm-col-2">
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li class="pfm-col-3">
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1">
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz lang</li>
                                    <li>LAS/Abschnitte</li>
                                    <li>Palettenholz</li>
                                    <li>Papierholz (IN)</li>
                                    <li>Industrieholz (IF/IK)</li>
                                </ul>
                            </fieldset>
                            <fieldset id="b_2_1_a" class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_2_1_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_1_a_1_1" name="b_2_1_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_1_2" name="b_2_1_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_1_3" name="b_2_1_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_1_a_2_1" name="b_2_1_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_2_2" name="b_2_1_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_2_3" name="b_2_1_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_1_a_3_1" name="b_2_1_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_3_2" name="b_2_1_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_3_3" name="b_2_1_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_a_4" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_1_a_4_1" name="b_2_1_a_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_4_2" name="b_2_1_a_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_4_3" name="b_2_1_a_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_a_5" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_1_a_5_1" name="b_2_1_a_5_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_5_2" name="b_2_1_a_5_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_a_5_3" name="b_2_1_a_5_n_1" value="001" type="radio">
                                            </label>

                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset id="b_2_1_b"  class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_2_1_b_1" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_1_1" name="b_2_1_b_1_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_1_2" name="b_2_1_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_1_3" name="b_2_1_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_b_2" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_2_1" name="b_2_1_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_2_2" name="b_2_1_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_2_3" name="b_2_1_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_b_3" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_3_1" name="b_2_1_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_3_2" name="b_2_1_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_3_3" name="b_2_1_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_b_4" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_4_1" name="b_2_1_b_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_4_2" name="b_2_1_b_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_4_3" name="b_2_1_b_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_1_b_5" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_5_1" name="b_2_1_b_5_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_5_2" name="b_2_1_b_5_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_1_b_5_3" name="b_2_1_b_5_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset id="b_2_2" class="pfm-worker pfm-table-content">
                        <legend>Künftige Marktentwicklung (ca. 3 Monate) </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Künftige Marktentwicklung (ca. 3 Monate) </h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz lang</li>
                                    <li>LAS/Abschnitte</li>
                                    <li>Palettenholz</li>
                                    <li>Papierholz (IN)</li>
                                    <li>Industrieholz (IF/IK)</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_2_2_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_2_a_1_1" name="b_2_2_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_1_2" name="b_2_2_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_1_3" name="b_2_2_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_2_a_2_1" name="b_2_2_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_2_2" name="b_2_2_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_2_3" name="b_2_2_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_2_a_3_1" name="b_2_2_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_3_2" name="b_2_2_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_3_3" name="b_2_2_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_a_4" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_2_a_4_1" name="b_2_2_a_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_4_2" name="b_2_2_a_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_4_3" name="b_2_2_a_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_a_5" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_2_2_a_5_1" name="b_2_2_a_5_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_5_2" name="b_2_2_a_5_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_a_5_3" name="b_2_2_a_5_n_1" value="001" type="radio">
                                            </label>

                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_2_2_b_1" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_1_1" name="b_2_2_b_1_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_1_2" name="b_2_2_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_1_3" name="b_2_2_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_b_2" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_2_1" name="b_2_2_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_2_2" name="b_2_2_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_2_3" name="b_2_2_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_b_3" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_3_1" name="b_2_2_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_3_2" name="b_2_2_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_3_3" name="b_2_2_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_b_4" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_4_1" name="b_2_2_b_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_4_2" name="b_2_2_b_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_4_3" name="b_2_2_b_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_2_2_b_5" class="pfm-table-task-list-column">
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_5_1" name="b_2_2_b_5_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_5_2" name="b_2_2_b_5_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_2_2_b_5_3" name="b_2_2_b_5_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
            </section>
        </article>
        <article class="pfm-area pre-form-area">
            <header>
                <h2>Teil 3/6 - Marktanalyse Douglasie</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <h3>Douglasie</h3>
                <div class="pfm-table pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Marktverlauf in der Berichtszeit</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf in der Berichtszeit</h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz lang</li>
                                    <li>LAS/Abschnitte</li>
                                    <li>Industrieholz</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_3_1_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_1_a_1_1" name="b_3_1_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_a_1_2" name="b_3_1_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_a_1_3" name="b_3_1_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_1_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_1_a_2_1" name="b_3_1_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_a_2_2" name="b_3_1_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_a_2_3" name="b_3_1_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_1_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_1_a_3_1" name="b_3_1_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_a_3_2" name="b_3_1_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_a_3_3" name="b_3_1_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_3_1_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_1_b_1_1" name="b_3_1_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_b_1_2" name="b_3_1_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_b_1_3" name="b_3_1_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_1_b_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_1_b_2_1" name="b_3_1_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_b_2_2" name="b_3_1_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_b_2_3" name="b_3_1_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_1_b_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_1_b_3_1" name="b_3_1_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_b_3_2" name="b_3_1_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_1_b_3_3" name="b_3_1_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Künftige Marktentwicklung (ca. 3 Monate) </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Künftige Marktentwicklung (ca. 3 Monate) </h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz lang</li>
                                    <li>LAS/Abschnitte</li>
                                    <li>Industrieholz</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_3_2_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_2_a_1_1" name="b_3_2_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_a_1_2" name="b_3_2_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_a_1_3" name="b_3_2_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_2_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_2_a_2_1" name="b_3_2_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_a_2_2" name="b_3_2_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_a_2_3" name="b_3_2_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_2_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_2_a_3_1" name="b_3_2_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_a_3_2" name="b_3_2_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_a_3_3" name="b_3_2_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_3_2_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_2_b_1_1" name="b_3_2_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_b_1_2" name="b_3_2_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_b_1_3" name="b_3_2_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_2_b_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_2_b_2_1" name="b_3_2_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_b_2_2" name="b_3_2_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_b_2_3" name="b_3_2_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_3_2_b_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_3_2_b_3_1" name="b_3_2_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_b_3_2" name="b_3_2_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_3_2_b_3_3" name="b_3_2_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
            </section>
        </article>
        <article class="pfm-area pre-form-area">
            <header>
                <h2>Teil 4/6 - Marktanalyse Kiefer</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <h3>Kiefer</h3>
                <div class="pfm-table pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Marktverlauf in der Berichtszeit</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf in der Berichtszeit</h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz lang</li>
                                    <li>LAS/Abschnitte</li>
                                    <li>Industrieholz</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_4_1_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_1_a_1_1" name="b_4_1_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_a_1_2" name="b_4_1_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_a_1_3" name="b_4_1_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_1_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_1_a_2_1" name="b_4_1_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_a_2_2" name="b_4_1_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_a_2_3" name="b_4_1_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_1_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_1_a_3_1" name="b_4_1_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_a_3_2" name="b_4_1_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_a_3_3" name="b_4_1_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_4_1_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_1_b_1_1" name="b_4_1_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_b_1_2" name="b_4_1_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_b_1_3" name="b_4_1_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_1_b_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_1_b_2_1" name="b_4_1_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_b_2_2" name="b_4_1_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_b_2_3" name="b_4_1_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_1_b_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_1_b_3_1" name="b_4_1_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_b_3_2" name="b_4_1_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_1_b_3_3" name="b_4_1_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Künftige Marktentwicklung (ca. 3 Monate) </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Künftige Marktentwicklung (ca. 3 Monate) </h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz lang</li>
                                    <li>LAS/Abschnitte</li>
                                    <li>Industrieholz</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_4_2_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_2_a_1_1" name="b_4_2_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_a_1_2" name="b_4_2_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_a_1_3" name="b_4_2_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_2_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_2_a_2_1" name="b_4_2_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_a_2_2" name="b_4_2_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_a_2_3" name="b_4_2_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_2_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_2_a_3_1" name="b_4_2_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_a_3_2" name="b_4_2_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_a_3_3" name="b_4_2_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_4_2_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_2_b_1_1" name="b_4_2_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_b_1_2" name="b_4_2_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_b_1_3" name="b_4_2_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_2_b_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_2_b_2_1" name="b_4_2_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_b_2_2" name="b_4_2_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_b_2_3" name="b_4_2_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_4_2_b_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_4_2_b_3_1" name="b_4_2_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_b_3_2" name="b_4_2_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_4_2_b_3_3" name="b_4_2_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
            </section>
        </article>
        <article class="pfm-area pre-form-area">
            <header>
                <h2>Teil 5/6 - Marktanalyse Buche</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <h3>Buche</h3>
                <div class="pfm-table pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Marktverlauf in der Berichtszeit</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf in der Berichtszeit</h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz</li>
                                    <li>Industrieholz</li>
                                    <li>Faserholz</li>
                                    <li>Brennholz</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_5_1_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_a_1_1" name="b_5_1_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_1_2" name="b_5_1_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_1_3" name="b_5_1_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_1_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_a_2_1" name="b_5_1_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_2_2" name="b_5_1_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_2_3" name="b_5_1_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_1_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_a_3_1" name="b_5_1_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_3_2" name="b_5_1_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_3_3" name="b_5_1_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_1_a_4" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_a_4_1" name="b_5_1_a_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_4_2" name="b_5_1_a_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_a_4_3" name="b_5_1_a_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_5_1_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_b_1_1" name="b_5_1_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_1_2" name="b_5_1_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_1_3" name="b_5_1_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_1_b_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_b_2_1" name="b_5_1_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_2_2" name="b_5_1_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_2_3" name="b_5_1_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_1_b_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_b_3_1" name="b_5_1_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_3_2" name="b_5_1_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_3_3" name="b_5_1_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_1_a_4" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_1_b_4_1" name="b_5_1_b_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_4_2" name="b_5_1_b_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_1_b_4_3" name="b_5_1_b_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Künftige Marktentwicklung (ca. 3 Monate) </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Künftige Marktentwicklung (ca. 3 Monate) </h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Stammholz</li>
                                    <li>Industrieholz</li>
                                    <li>Faserholz</li>
                                    <li>Brennholz</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_5_2_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_a_1_1" name="b_5_2_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_1_2" name="b_5_2_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_1_3" name="b_5_2_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_2_a_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_a_2_1" name="b_5_2_a_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_2_2" name="b_5_2_a_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_2_3" name="b_5_2_a_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_2_a_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_a_3_1" name="b_5_2_a_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_3_2" name="b_5_2_a_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_3_3" name="b_5_2_a_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_2_a_4" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_a_4_1" name="b_5_2_a_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_4_2" name="b_5_2_a_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_a_4_3" name="b_5_2_a_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_5_2_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_b_1_1" name="b_5_2_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_1_2" name="b_5_2_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_1_3" name="b_5_2_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_2_b_2" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_b_2_1" name="b_5_2_b_2_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_2_2" name="b_5_2_b_2_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_2_3" name="b_5_2_b_2_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_2_b_3" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_b_3_1" name="b_5_2_b_3_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_3_2" name="b_5_2_b_3_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_3_3" name="b_5_2_b_3_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                    <ul id="b_5_2_a_4" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_5_2_b_4_1" name="b_5_2_b_4_n_1" value="100" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_4_2" name="b_5_2_b_4_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_5_2_b_4_3" name="b_5_2_b_4_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
            </section>
        </article>
        <article class="pfm-area pre-form-area">
            <header>
                <h2>Teil 6/6 - Marktanalyse Diverse Holzarten</h2>
            </header>
            <section class="pfm-section pre-form-team">
                <h3>Diverse Holzarten</h3>
                <div class="pfm-table pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Marktverlauf in der Berichtszeit</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf in der Berichtszeit</h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis war ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis war ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Waldhackschnitzel</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_6_1_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_6_1_a_1_1" name="b_6_1_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_1_a_1_2" name="b_6_1_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_1_a_1_3" name="b_6_1_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_6_1_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_6_1_b_1_1" name="b_6_1_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_1_b_1_2" name="b_6_1_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_1_b_1_3" name="b_6_1_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Künftige Marktentwicklung (ca. 3 Monate) </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Künftige Marktentwicklung (ca. 3 Monate) </h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend>Nachfrage wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Nachfrage wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend>Preis wird ...</legend>
                                <aside class="w100-center">
                                    <span class="ie-legend">Preis wird ...</span>
                                </aside>
                                <ul class="pfm-table-task-list-column">
                                    <li>
                                        <i class="fa-regular fa-arrow-up-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrows-left-right"></i>
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-arrow-down-right"></i>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul>
                                    <li>Waldhackschnitzel</li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <div>
                                    <ul id="b_6_2_a_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_6_2_a_1_1" name="b_6_2_a_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_2_a_1_2" name="b_6_2_a_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_2_a_1_3" name="b_6_2_a_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-3">
                                <legend></legend>
                                <div>
                                    <ul id="b_6_2_b_1" class="pfm-table-task-list-column">
                                        <li class="pfm-col-1">
                                            <label>
                                                <input id="b_6_2_b_1_1" name="b_6_2_b_1_n_1" value="100" type="radio">
                                            </label>

                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_2_b_1_2" name="b_6_2_b_1_n_1" value="010" type="radio">
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input id="b_6_2_b_1_3" name="b_6_2_b_1_n_1" value="001" type="radio">
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
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
        
HTML;
    }
}
