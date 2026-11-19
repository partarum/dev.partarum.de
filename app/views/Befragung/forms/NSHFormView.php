<?php
declare(strict_types=1);

namespace app\views\Befragung\forms;

use app\core\Contracts\RenderableInterface;

class NSHFormView implements RenderableInterface {

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
                <!--
                b_1 - b_2
                -->
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
                            Geschäftslage an den Nebenproduktmärkten
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Geschäftslage an den Nebenproduktmärkten</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_2_1" class="pfm-ts-label pre-form-task-label">gut</label>
                                <input type="radio" id="b_2_1" value="100" name="b_2_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_2_2" class="pfm-ts-label pre-form-task-label">befriedigend</label>
                                <input type="radio" id="b_2_2" value="010" name="b_2_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_2_3" class="pfm-ts-label pre-form-task-label">schlecht</label>
                                <input type="radio" id="b_2_3" value="001" name="b_2_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <!--
                b_3

                TODO: Attribute (for, id, name, value) müssen noch gesetzt werden
                -->

                <div class="pfm-table pfm-simple pre-flex-gap-item">
                    <fieldset class="pfm-worker pfm-table-content">
                        <legend>Marktlage in</legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktlage in</h4>
                        </aside>
                        <div class="pfm-table-headline">
                            <fieldset class="pfm-worker pfm-col-1"></fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <legend></legend>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1">gut</li>
                                    <li class="pfm-col-2">befriedigend</li>
                                    <li class="pfm-col-3">schlecht</li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="pfm-table-body pfm-smooth-border">
                            <fieldset class="pfm-worker pfm-col-1">
                                <legend></legend>
                                <ul class="pfm-tl-radio">
                                    <li class="pfm-task-step">
                                        <label>Deutschland</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>USA</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Österreich</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Frankreich</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Niederlande</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Belgien</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Italien</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Schweiz</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Polen</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Vereinigtes Königreich</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>China</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>Indien</label>
                                        <input type="hidden">
                                    </li>
                                    <li class="pfm-task-step">
                                        <label>MENA</label>
                                        <input type="hidden">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker pfm-col-2">
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_1_1" value="100" name="b_3_1_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_1_2" value="010" name="b_3_1_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_1_3" value="001" name="b_3_1_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_2_1" value="100" name="b_3_2_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_2_2" value="010" name="b_3_2_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_2_3" value="001" name="b_3_2_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_3_1" value="100" name="b_3_3_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_3_2" value="010" name="b_3_3_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_3_3" value="001" name="b_3_3_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_4_1" value="100" name="b_3_4_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_4_2" value="010" name="b_3_4_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_4_3" value="001" name="b_3_4_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_5_1" value="100" name="b_3_5_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_5_2" value="010" name="b_3_5_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_5_3" value="001" name="b_3_5_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_6_1" value="100" name="b_3_6_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_6_2" value="010" name="b_3_6_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_6_3" value="001" name="b_3_6_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_7_1" value="100" name="b_3_7_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_7_2" value="010" name="b_3_7_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_7_3" value="001" name="b_3_7_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_8_1" value="100" name="b_3_8_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_8_2" value="010" name="b_3_8_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_8_3" value="001" name="b_3_8_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_9_1" value="100" name="b_3_9_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_9_2" value="010" name="b_3_9_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_9_3" value="001" name="b_3_9_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_10_1" value="100" name="b_3_10_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_10_2" value="010" name="b_3_10_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_10_3" value="001" name="b_3_10_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_11_1" value="100" name="b_3_11_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_11_2" value="010" name="b_3_11_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_11_3" value="001" name="b_3_11_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_12_1" value="100" name="b_3_12_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_12_2" value="010" name="b_3_12_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_12_3" value="001" name="b_3_12_n_1" type="radio"></label>
                                    </li>
                                </ul>
                                <ul class="pfm-table-task-list-column">
                                    <li class="pfm-col-1 pfm-task-step">
                                        <label><input id="b_3_13_1" value="100" name="b_3_13_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-2 pfm-task-step">
                                        <label><input id="b_3_13_2" value="010" name="b_3_13_n_1" type="radio"></label>
                                    </li>
                                    <li class="pfm-col-3 pfm-task-step">
                                        <label><input id="b_3_13_3" value="001" name="b_3_13_n_1" type="radio"></label>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>

                <!--
                b_4 - b_5
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
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Erzeugerpreise
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Erzeugerpreise</h4>
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
                </div>
                <!--
                b_6 - b_7
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Lagerbestand unverkaufter Schnittware
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Lagerbestand unverkaufter Schnittware</h4>
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
                    <fieldset class="pfm-worker pre-flex-gap-item">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Auftragseingänge
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Auftragseingänge</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_0_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                        <input type="radio" id="b_7_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_0_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                        <input type="radio" id="b_7_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_0_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                        <input type="radio" id="b_7_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Nahzone (bis 100km)</legend>
                                <aside class="ie-legend">
                                    <span>Nahzone (bis 100km)</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_7_1_1" value="100" name="b_7_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_7_1_2" value="010" name="b_7_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_7_1_3" value="001" name="b_7_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Fernzone</legend>
                                <aside class="ie-legend">
                                    <span>Fernzone</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_7_2_1" value="100" name="b_7_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_7_2_2" value="010" name="b_7_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_7_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_7_2_3" value="001" name="b_7_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
                <!--
                b_8 - b_9
                -->

                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Auftragsbestand
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Auftragsbestand</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_1" class="pfm-ts-label pre-form-task-label">gestiegen</label>
                                <input type="radio" id="b_8_1" value="100" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                <input type="radio" id="b_8_2" value="010" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_8_3" class="pfm-ts-label pre-form-task-label">gefallen</label>
                                <input type="radio" id="b_8_3" value="001" name="b_8_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker w100-centerBox">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Marktverlauf
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Marktverlauf</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_1" class="pfm-ts-label pre-form-task-label">lebhaft</label>
                                        <input type="radio" id="b_9_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_2" class="pfm-ts-label pre-form-task-label">rege</label>
                                        <input type="radio" id="b_9_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_3" class="pfm-ts-label pre-form-task-label">gemäßigt</label>
                                        <input type="radio" id="b_9_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_4" class="pfm-ts-label pre-form-task-label">langsam</label>
                                        <input type="radio" id="b_9_0_4" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_0_5" class="pfm-ts-label pre-form-task-label">schleppend</label>
                                        <input type="radio" id="b_9_0_5" hidden class="pfm-ts-input pre-form-task-input">
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
                                        <label for="b_9_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_1" value="10000" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_2" value="01000" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_3" value="00100" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_4" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_4" value="00010" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_1_5" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_1_5" value="00001" name="b_9_1_n_1" class="pfm-ts-input pre-form-task-input">
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
                                        <label for="b_9_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_1" value="10000" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_2" value="01000" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_3" value="00100" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_4" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_4" value="00010" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_9_2_5" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_9_2_5" value="00001" name="b_9_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>

                <!--
                b_10 - b_11
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker form-task-column pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            <b>Lieferzeit</b>
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Lieferzeit</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-task-title pfm-text-hidden">Zeitraum</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zeitraum</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_0_1" class="pfm-ts-label pre-form-task-label">1 Woche</label>
                                        <input type="radio" id="b_10_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_0_2" class="pfm-ts-label pre-form-task-label">2 Wochen</label>
                                        <input type="radio" id="b_10_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_0_3" class="pfm-ts-label pre-form-task-label">3+ Wochen</label>
                                        <input type="radio" id="b_10_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Bauholz</legend>
                                <aside class="ie-legend">
                                    <span>Bauholz</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_10_1_1" value="100" name="b_10_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_10_1_2" value="010" name="b_10_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_10_1_3" value="001" name="b_10_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">Industrieware</legend>
                                <aside class="ie-legend">
                                    <span>Industrieware</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_10_2_1" value="100" name="b_10_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_10_2_2" value="010" name="b_10_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_10_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_10_2_3" value="001" name="b_10_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <!--
                        -->
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Konkurrenzsituation
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Konkurrenzsituation</h4>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_0_1" class="pfm-ts-label pre-form-task-label">verschärft</label>
                                        <input type="radio" id="b_11_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_0_2" class="pfm-ts-label pre-form-task-label">unverändert</label>
                                        <input type="radio" id="b_11_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_11_0_3" class="pfm-ts-label pre-form-task-label">abgeschwächt</label>
                                        <input type="radio" id="b_11_0_3" hidden class="pfm-ts-input pre-form-task-input">
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
                                <legend class="pfm-tt-center">Ausland</legend>
                                <aside class="ie-legend">
                                    <span>Ausland</span>
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
                        </div>
                    </fieldset>
                </div>
                <!--
                b_12 - b_13
                -->
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Arbeitszeit in der Produktion
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Arbeitszeit in der Produktion</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_12_1" class="pfm-ts-label pre-form-task-label">Überstunden</label>
                                <input type="radio" id="b_12_1" value="100" name="b_12_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_12_2" class="pfm-ts-label pre-form-task-label">Normalarbeit</label>
                                <input type="radio" id="b_12_2" value="010" name="b_12_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_12_3" class="pfm-ts-label pre-form-task-label">Kurzarbeit</label>
                                <input type="radio" id="b_12_3" value="001" name="b_12_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Rundholzbevorratung für
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Rundholzbevorratung für</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_13_1" class="pfm-ts-label pre-form-task-label">1 Monat</label>
                                <input type="radio" id="b_13_1" value="1000" name="b_13_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_13_2" class="pfm-ts-label pre-form-task-label">2 Monate</label>
                                <input type="radio" id="b_13_2" value="0100" name="b_13_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_13_3" class="pfm-ts-label pre-form-task-label">3 Monate</label>
                                <input type="radio" id="b_13_3" value="0010" name="b_13_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_13_4" class="pfm-ts-label pre-form-task-label">4+ Monate</label>
                                <input type="radio" id="b_13_4" value="0001" name="b_13_n_1" class="pfm-ts-input pre-form-task-input">
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
                    <h3>Künftige Marktentwicklung</h3>
                </header>
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center pre-form-task-title">
                            Rundholzbevorratung geplant für
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Rundholzbevorratung geplant für</h4>
                        </aside>
                        <ul class="pfm-task-list pre-form-task-list">
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_14_1" class="pfm-ts-label pre-form-task-label">1 Monat</label>
                                <input type="radio" id="b_14_1" value="1000" name="b_14_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_14_2" class="pfm-ts-label pre-form-task-label">2 Monate</label>
                                <input type="radio" id="b_14_2" value="0100" name="b_14_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_14_3" class="pfm-ts-label pre-form-task-label">3 Monate</label>
                                <input type="radio" id="b_14_3" value="0010" name="b_14_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                            <li class="pfm-task-step pre-form-task-step">
                                <label for="b_14_4" class="pfm-ts-label pre-form-task-label">4+ Monat</label>
                                <input type="radio" id="b_14_4" value="0001" name="b_14_n_1" class="pfm-ts-input pre-form-task-input">
                            </li>
                        </ul>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Rundholzpreis wird
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Rundholzpreis wird</h4>
                            <span> i.d. nächsten Monaten ...</span>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_15_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_15_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_15_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">1</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_15_1_1" value="100" name="b_15_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_15_1_2" value="010" name="b_15_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_15_1_3" value="001" name="b_15_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">3</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_15_2_1" value="100" name="b_15_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_15_2_2" value="010" name="b_15_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_15_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_15_2_3" value="001" name="b_15_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
                <div class="pfm-wp-group pfm-t-small pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Nachfrage Bauware wird
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Nachfrage Bauware wird</h4>
                            <span> i.d. nächsten Monaten ....</span>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_16_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_16_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_16_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">1</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_16_1_1" value="100" name="b_16_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_16_1_2" value="010" name="b_16_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_16_1_3" value="001" name="b_16_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">3</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_16_2_1" value="100" name="b_16_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_16_2_2" value="010" name="b_16_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_16_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_16_2_3" value="001" name="b_16_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                    <fieldset class="pfm-worker pre-form-worker">
                        <legend class="pfm-tt-center">
                            Nachfrage Industrieware wird
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Nachfrage Industrieware wird</h4>
                            <span> i.d. nächsten Monaten ....</span>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_17_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_17_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_17_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">1</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_17_1_1" value="100" name="b_17_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_17_1_2" value="010" name="b_17_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_17_1_3" value="001" name="b_17_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">3</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_17_2_1" value="100" name="b_17_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_17_2_2" value="010" name="b_17_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_17_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_17_2_3" value="001" name="b_17_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                    </fieldset>
                </div>
                <div class="pfm-wp-single pfm-t-medium pre-form-workplace pre-flex-gap-item">
                    <fieldset class="pfm-worker pre-form-worker w100-centerBox">
                        <legend class="pfm-tt-center">
                            Schnittholzpreis wird
                        </legend>
                        <aside class="w100-center">
                            <h4 class="ie-legend">Schnittholzpreis wird</h4>
                            <span> i.d. nächsten Monaten ....</span>
                        </aside>
                        <div class="pfm-wp-sub-group pfm-flex-row pfm-smooth-border">
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="form-task-title pfm-text-hidden">Zustand</legend>
                                <aside class="ie-legend flex-start">
                                    <span class="pfm-text-hidden">Zustand</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_0_1" class="pfm-ts-label pre-form-task-label">steigen</label>
                                        <input type="radio" id="b_18_0_1" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_0_2" class="pfm-ts-label pre-form-task-label">stabil sein</label>
                                        <input type="radio" id="b_18_0_2" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_0_3" class="pfm-ts-label pre-form-task-label">fallen</label>
                                        <input type="radio" id="b_18_0_3" hidden class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">1</legend>
                                <aside class="ie-legend">
                                    <span>1</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_1_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_18_1_1" value="100" name="b_18_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_1_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_18_1_2" value="010" name="b_18_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_1_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_18_1_3" value="001" name="b_18_1_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                </ul>
                            </fieldset>
                            <fieldset class="pfm-worker form-task-column">
                                <legend class="pfm-tt-center">3</legend>
                                <aside class="ie-legend">
                                    <span>3</span>
                                </aside>
                                <ul class="pfm-tl-radio pre-form-task-list w100-center">
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_2_1" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_18_2_1" value="100" name="b_18_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_2_2" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_18_2_2" value="010" name="b_18_2_n_1" class="pfm-ts-input pre-form-task-input">
                                    </li>
                                    <li class="pfm-task-step pre-form-task-step">
                                        <label for="b_18_2_3" class="pfm-ts-label pre-form-task-label"></label>
                                        <input type="radio" id="b_18_2_3" value="001" name="b_18_2_n_1" class="pfm-ts-input pre-form-task-input">
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
