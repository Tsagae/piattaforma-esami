<?php 
session()->getFlashdata('error');
$formValues = [];
if (isset($insegnamento)) {
    $formValues = [
        'nome' => esc($insegnamento->nome),
        'semestre' => esc($insegnamento->semestre),
        'id_cdl' => esc($insegnamento->id_cdl),
        'id_docente' => esc($insegnamento->id_docente),
        'anno' => esc($insegnamento->anno)
    ];
} else {
    $formValues = [
        'nome' => esc(set_value('nome')),
        'semestre' => esc(set_value('semestre')),
        'id_cdl' => esc(set_value('id_cdl')),
        'id_docente' => esc(set_value('id_docente')),
        'anno' => esc(set_value('anno')),
    ];
} ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Modifica un insegnamento</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_insegnamento" value="<?= $insegnamento->id_insegnamento ?>">
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="" value="<?= $formValues['nome'] ?>"
                    required="">
                <div class="invalid-feedback">
                    Nome obbligatorio.
                </div>
            </div>

            <div class="col-sm-6">
                <label for="tipo" class="form-label">Semestre</label>
                <select
                    class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="semestre" required="" >
                    <option><?= $formValues['semestre'] ?></option>
                    <?php if($formValues['semestre'] == 1): ?>
                        <option value="2">2</option>
                    <?php else: ?>
                        <option value="1">1</option>
                    <?php endif ?>
                </select>
            </div>

            <div class="col-sm-6">
                <label for="anno" class="form-label">Anno</label>
                <input type="number" class="form-control" name="anno" placeholder="" value="<?= $formValues['anno'] ?>"
                    required="">
                <div class="invalid-feedback">
                    Anno obbligatorio.
                </div>
            </div>

        </div>
        <div class="row g-3">
            <div class="row g-3 mb-3">
                <label for="id_cdl" class="form-label">Corsi di laurea</label>
                <select
                    class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="id_cdl" required="">
                    <option value="<?= $formValues['id_cdl'] ?>"><?= $formValues['id_cdl'] ?> <?= $allCdl[$formValues['id_cdl']]->nome ?></option>
                    <?php foreach ($allCdl as $cdl): ?>
                        <option value="<?= esc($cdl->id_cdl) ?>"><?= esc($cdl->id_cdl) ?>     <?= esc($cdl->nome) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <label for="id_docente" class="form-label">Docente</label>
                <select
                    class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="id_docente" required="" value="<?= set_value('id_docente') ?>">
                    <option value="<?= $formValues['id_docente'] ?>"><?= $docenti[$formValues['id_docente']]->nome ?> <?= $docenti[$formValues['id_docente']]->cognome ?></option>
                        <?php foreach ($docenti as $docente): ?>
                        <option value="<?= esc($docente->id_docente) ?>"><?= esc($docente->nome) ?>     <?= esc($docente->cognome) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <hr>
        <div class="row g-3">
            <label for="propedeuticita" class="form-label">Propedeuticità</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="val1" id="flexCheckDefault" name="propedeuticita[]">
                <label class="form-check-label" for="flexCheckDefault">
                    Default checkbox
                </label>
                </div>
                <div class="form-check">
                <input class="form-check-input" type="checkbox" value="val2" id="flexCheckChecked" name="propedeuticita[]">
                <label class="form-check-label" for="flexCheckChecked">
                    Checked checkbox
                </label>
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
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiungi</button>
    </form>
</div>