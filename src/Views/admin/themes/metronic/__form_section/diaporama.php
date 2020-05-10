<?php if (empty($form->id_diaporama)) { ?>
    <div class="alert alert-custom alert-outline-primary fade show mb-5" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text"><?= lang("Core.Vous devez enregistrer votre élément avant d'accèder à cette partie"); ?> </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="ki ki-close"></i></span>
            </button>
        </div>
    </div>
<?php } else { ?>
    <div class="parent-container" data-id_diaporama="<?= $form->id_diaporama; ?>">
        <?php if (isset($form->slides)) { ?>
            <?php foreach ($form->slides as $slide) { ?>
                <?= View('\Adnduweb\Ci4_diaporama\Views\admin\themes\metronic\__form_section\slide', ['slide' => $slide]); ?>
            <?php } ?>
        <?php } ?>
    </div>
    <button type='button' class="btn btn-bold btn-sm btn-label-brand" id='add'><i class="la la-plus"></i> <?= lang('Core.add'); ?></button>

<?php } ?>