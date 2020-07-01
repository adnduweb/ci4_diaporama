<div class="row">
    <label class="col-xl-3"></label>
    <div class="col-lg-9 col-xl-6">
        <h3 class="kt-section__title kt-section__title-sm"><?= lang('Core.info_taxe'); ?>:</h3>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.activation')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->active == true) ? 'checked="checked"' : ''; ?> name="active" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="name" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.name')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_input_spread('name', $form->_prepareLang(), 'id="name" class="form-control lang"', 'text', true); ?>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="sous_name" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.sous_name')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_input_spread('sous_name', $form->_prepareLang(), 'id="sous_name" class="form-control lang"', 'text', true); ?>
    </div>
</div>

<div class="form-group row">
    <label for="handle" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.handle')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <input type="text" name="handle" value="<?= isset($form->handle) ? $form->handle : ""; ?>" id="handles" class="form-control" required="">
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="description_short" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description_short')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_textarea_spread('description_short', $form->_prepareLang(), 'class="form-control lang"', false); ?>
    </div>
</div>
<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit kt-separator--space-lg"></div>

<div class="form-group row">
    <label for="dimensions" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.dimensions')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <input type="text" name="dimensions" value="<?= isset($form->dimensions) ? $form->dimensions : ""; ?>" id="dimensions" class="form-control" required="">
        <span class="form-text text-muted"><?= lang('Core.Ex: 1920x1080'); ?> </span>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.transparent_mask')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->transparent_mask == true) ? 'checked="checked"' : ''; ?> name="transparent_mask" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group row">
    <label for="transparent_mask_color_bg" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.transparent_mask_color_bg')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <input type="color" name="transparent_mask_color_bg" value="<?= isset($form->transparent_mask_color_bg) ? $form->transparent_mask_color_bg : ""; ?>" id="transparent_mask_color_bg" class="form-control">
        <span class="form-text text-muted"><?= lang('Core.Ex: #000000'); ?> </span>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.force_height')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->force_height == true) ? 'checked="checked"' : ''; ?> name="force_height" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.center_img')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->center_img == true) ? 'checked="checked"' : ''; ?> name="center_img" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>




<div class="form-group form-group-sm row">
    <label class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.bouton_diapo')); ?></label>
    <div class="col-lg-9 col-xl-6">
        <span class="kt-switch kt-switch--icon">
            <label>
                <input type="checkbox" <?= ($form->bouton_diapo == true) ? 'checked="checked"' : ''; ?> name="bouton_diapo" value="1">
                <span></span>
            </label>
        </span>
    </div>
</div>

<div class="form-group row kt-shape-bg-color-1">
    <label for="url_bouton_diapo" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.url_bouton_diapo')); ?>* : </label>
    <div class="col-lg-9 col-xl-6">
        <?= form_input_spread('url_bouton_diapo', $form->_prepareLang(), 'id="url_bouton_diapo" class="form-control lang"', 'text', false, 'slide'); ?>
    </div>
</div>

<?php if (!empty($form->id)) { ?> <?= form_hidden('id', $form->id); ?> <?php } ?>