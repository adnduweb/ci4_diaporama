<div class="parent-container-row_diaporama">
    <?php //print_r($slide->id_field); echo $id_field; exit; 
    ?>
    <?php $id_field = $id_field ?? $slide->id_field ?? '__i__'; //var_dump($id_field); exit; 
    ?>
    <?php $id_slide = $id_slide ?? $slide->id_slide ?? '__i__'; //var_dump($id_slide); exit; 
    ?>
    <?php $order    = ((isset($order)) ? $order : ($slide->id_field != '')) ? $slide->order : '__n__'; ?>
    <?php $media = ($slide->options) ? $slide->getAttrOptionsImage() : 'admin/bundle/images/medias/no-image.png'; ?>
    <div class="kt-portlet kt-portlet--height-fluid kt-portlet--collapse" id="kt_portlet_tools<?= $id_field; ?>">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    <span class="image_une">
                        <img src="<?= base_url(); ?>/<?= $media; ?>" alt="image">
                    </span>
                    <?= lang('Core.slide'); ?> <span class="numberSlide"><?= $order; ?></span>
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-group">
                    <a href="javascript:;" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-brand btn-icon-md"><i class="la la-angle-down"></i></a>
                    <a href="javascript:;" data-id-slide="<?= $id_slide; ?>" data-ktportlet-tool="remove" class="btn btn-sm btn-icon btn-warning btn-icon-md removePortlet"><i class="la la-close"></i></a>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body" style="display: none;overflow: hidden;padding-top: 0px;padding-bottom: 0px;">
            <div class="kt-portlet__content">
                <div class="row_diaporama kt-shape-bg-color-1">
                    <div class="form-group row fileImageUpload">
                        <label for="picture_one" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.Slide ')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <div>
                                <?php $dimensions = explode('|', $form->dimensions); ?>
                                <?php $optionsPicture_one = [
                                    'acceptedFiles' => '.jpg, .jpeg, .png, .svg, .webp',
                                    'maxFiles' => 1,
                                    'maxFilesize' => 5,
                                    'uploadMultiple' => false,
                                    'crop' => true,
                                    'crop_width' => $dimensions[0],
                                    'crop_height' => $dimensions[1],
                                    'type' => 'image',
                                    'field' => $id_field,
                                    'builder' => (isset($slide)) ? $slide : null,
                                    'input' => 'slide',
                                    'only' => 0
                                ]; ?>
                                <?= view('/admin/themes/metronic/controllers/medias/bundleUploadCrop', $optionsPicture_one) ?>
                                <?= form_hidden('slide[' . $id_field . '][options]', $slide->options); ?>


                                <!-- <div id="slide_<?= $id_field; ?>_image" class="slide_<?= $id_field; ?>_image" data-field="slide_<?= $id_field; ?>_image">
                                    <div class="kt-section__content_media">

                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group row kt-shape-bg-color-1">
                        <label for="name_one" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.titre')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <?= form_input_spread([$id_field, 'name_one'], $slide->_prepareLang(), 'id="name_one" class="form-control lang"', 'text', false, 'slide'); ?>
                        </div>
                    </div>

                    <div class="form-group row kt-shape-bg-color-1">
                        <label for="name_two" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.sous-titre')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <?= form_input_spread([$id_field, 'name_two'], $slide->_prepareLang(), 'id="name_two" class="form-control lang"', 'text', false, 'slide'); ?>
                        </div>
                    </div>

                    <div class="form-group row kt-shape-bg-color-1">
                        <label for="description_one" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description_one')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <?= form_textarea_spread([$id_field, 'description_one'], $slide->_prepareLang(), 'class="form-control lang"', false, '', 'slide'); ?>
                        </div>
                    </div>
                    <div class="form-group row kt-shape-bg-color-1">
                        <label for="description_two" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.description_two')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <?= form_textarea_spread([$id_field, 'description_two'], $slide->_prepareLang(), 'class="form-control lang"', false, '', 'slide'); ?>
                        </div>
                    </div>

                    <div class="form-group row kt-shape-bg-color-1">
                        <label for="bouton" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.bouton')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <?= form_input_spread([$id_field, 'bouton'], $slide->_prepareLang(), 'id="bouton" class="form-control lang"', 'text', false, 'slide'); ?>
                        </div>
                    </div>

                    <div class="form-group row kt-shape-bg-color-1">
                        <label for="slug" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.slug')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <?= form_input_spread([$id_field, 'slug'], $slide->_prepareLang(), 'id="slug" class="form-control lang"', 'text', false, 'slide'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="color_bg" class="col-xl-3 col-lg-3 col-form-label"><?= ucfirst(lang('Core.color_bg')); ?>* : </label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="color" name="slide[<?= $id_field; ?>][color_bg]" value="<?= isset($slide->color_bg) ? $slide->color_bg : ""; ?>" id="color_bg" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php if ($slide->id_field == null) { ?>
            __script__
        <?php } ?>
    </div>
</div>