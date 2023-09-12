<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiungi un insegnamento</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="" value="<?= set_value('nome') ?>"
                    required="">
                <div class="invalid-feedback">
                    Nome obbligatorio.
                </div>
            </div>

            <div class="col-sm-6">
                <label for="tipo" class="form-label">Semestre</label>
                <select
                    class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="semestre" required="" value="<?= set_value('semestre') ?>">
                        Seleziona un semestre
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>

            <div class="col-sm-6">
                <label for="anno" class="form-label">Anno</label>
                <input type="number" class="form-control" name="anno" placeholder="" value="<?= set_value('anno') ?>"
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
                    data-bs-theme="light" name="id_cdl" required="" value="<?= set_value('id_cdl') ?>">
                        <option value="">
                        Seleziona un corso di laurea
                        </option>
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
                        <option value="">
                            Seleziona un docente
                        </option>
                    <?php foreach ($docenti as $docente): ?>
                        <option value="<?= esc($docente->id_docente) ?>"><?= esc($docente->nome) ?>     <?= esc($docente->cognome) ?></option>
                    <?php endforeach ?>
                </select>
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