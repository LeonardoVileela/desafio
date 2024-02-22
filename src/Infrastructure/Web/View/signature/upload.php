<?php require_once __DIR__ . '/../default/head_initial.php'; ?>

<?php
$menu = "create_document";
$pageName = "Upload do documento";
?>
<?php require_once __DIR__ . '/../default/head_final.php'; ?>
    <div class="row">

        <div class="col-md-12">
            <?php if (isset($errorMsg)) { ?>
                <p style="color: red"><?php echo $errorMsg; ?></p>
            <?php } ?>
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Adicionar Documento</h3>
                </div>

                <form action="<?php echo BASE_URL; ?>/assinatura/documento" method="post" enctype="multipart/form-data"
                      id="quickForm">
                    <div style="padding-bottom: 0;" class="card-body">
                        <div class="form-group">
                            <label for="document">Documento</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input name="document" type="file" class="custom-file-input" id="document">
                                    <label class="custom-file-label" for="document">Escolha um
                                        documento</label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div style="padding-bottom: 20px;padding-right: 20px;padding-left: 20px; font-family: Arial, sans-serif; font-size: 14px;">
                        <strong>Importante:</strong> Só aceitamos arquivos em formato <strong>PDF</strong>.
                        Certifique-se de
                        que seu documento atende a esse requisito antes de fazer o upload.
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/../default/footer_initial.php'; ?>

    <!-- bs-custom-file-input -->
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/bs-custom-file-input.min.js"></script>

    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>

    <!-- jquery-validation -->
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/jquery.validate.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/additional-methods.min.js"></script>


    <script>
        $(function () {

            $('#quickForm').validate({
                rules: {
                    document: {
                        required: true,
                        extension: "pdf"
                    }
                },
                messages: {
                    document: {
                        required: "Por favor escolha um documento",
                        extension: "O documento deve ser em formato PDF"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>

<?php require_once __DIR__ . '/../default/footer_final.php'; ?>