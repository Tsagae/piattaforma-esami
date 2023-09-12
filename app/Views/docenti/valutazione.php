<?= session()->getFlashdata('error') ?>
<?php
$voto = null;
if (isset($valutazione->voto)) {
    $voto = $valutazione->voto;
} ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiorna valutazione</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_docente" value="<?= esc($valutazione->id_docente) ?>">
        <input type="hidden" name="id_esame" value="<?= esc($valutazione->id_esame) ?>">
        <input type="hidden" name="matricola" value="<?= esc($valutazione->matricola) ?>">


        <div class="row g-3">
            <div class="col-sm-6">
                <p>
                    <?= esc($valutazione->nome_studente) ?>
                    <?= esc($valutazione->cognome_studente) ?>
                </p>
            </div>
            <div class="col-sm-6">
                <p>
                    <?= esc($valutazione->nome_insegnamento) ?>
                    <?= esc($valutazione->id_cdl) ?>
                </p>

            </div>
        </div>
        <div class="row g-3">
            <div class="col-sm-6">
                <p>
                    Data Esame:
                    <?= esc($valutazione->data_esame) ?>
                </p>
            </div>
            <div class="col-sm-6">
                <p>
                    <label for="voto" class="form-label">Voto</label>
                    <input type="number" class="form-control" min="0" max="30" name="voto" placeholder=""
                        value="<?= esc($voto) ?>" required="">
                <div class="invalid-feedback">
                    Voto obbligatorio.
                </div>
                </p>
            </div>
        </div>
        <p>
            <?= validation_list_errors() ?>
        </p>
        <?php if (isset($queryError)): ?>
            <hr class="my-4">
            <div class="form-check">
                <p>
                    <?= esc($queryError) ?>
                </p>
            </div>
            <hr class="my-4">
        <?php endif; ?>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiorna Valutazione</button>
    </form>
</div>