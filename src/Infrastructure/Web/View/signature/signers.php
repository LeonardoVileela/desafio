<?php require_once __DIR__ . '/../default/head_initial.php'; ?>

<?php
$menu = "create_document";
$pageName = "Cadastrar assinaturas";
?>

<?php require_once __DIR__ . '/../default/head_final.php'; ?>
    <div class="row">

        <div class="col-md-12">
            <div class="card card-success card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-document-tab" data-toggle="pill"
                               href="#custom-tabs-one-document" role="tab" aria-controls="custom-tabs-one-document"
                               aria-selected="true">Documento</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-sender-tab" data-toggle="pill"
                               href="#custom-tabs-one-sender" role="tab" aria-controls="custom-tabs-one-sender"
                               aria-selected="false">Remetente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-signatories-tab" data-toggle="pill"
                               href="#custom-tabs-one-signatories" role="tab"
                               aria-controls="custom-tabs-one-signatories"
                               aria-selected="false">Signatários</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-document" role="tabpanel"
                             aria-labelledby="custom-tabs-one-document-tab">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="documentTitle">Título do Documento <span
                                                style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="documentTitle"
                                           placeholder="Título do Documento">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" onclick="validateDocument()">Continuar
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-sender" role="tabpanel"
                             aria-labelledby="custom-tabs-one-sender-tab">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="senderName">Nome <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="senderName"
                                           placeholder="Nome do Remetente">
                                </div>
                                <div class="form-group">
                                    <label for="senderEmail">Email <span style="color: red">*</span></label>
                                    <input type="email" class="form-control" id="senderEmail"
                                           placeholder="Email do Remetente">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary"
                                        onclick="goBack('custom-tabs-one-sender', 'custom-tabs-one-document')">Voltar
                                </button>
                                <button type="button" class="btn btn-primary" onclick="validateSender()">Continuar
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-signatories" role="tabpanel"
                             aria-labelledby="custom-tabs-one-signatories-tab">
                            <div class="card-body" id="signatoriesSection">
                                <!-- Signatários serão adicionados aqui dinamicamente -->
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary"
                                        onclick="goBack('custom-tabs-one-signatories', 'custom-tabs-one-sender')">Voltar
                                </button>
                                <button type="button" class="btn btn-primary" onclick="addSignatory()">Adicionar
                                    Signatário
                                </button>
                                <button id="finalButton" type="button" class="btn btn-primary"
                                        onclick="validateSignatories()">
                                    Finalizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="modal fade" id="loading">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">CRIANDO DOCUMENTO</h4>

                </div>
                <div style="text-align: center;" class="modal-body">
                    <img style="max-height: 250px" src="<?php echo BASE_URL_PUBLIC; ?>images/loading.gif" alt="">
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<?php require_once __DIR__ . '/../default/footer_initial.php'; ?>


    <!-- jquery-validation -->
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/jquery.validate.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/additional-methods.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            disableTabsClick();
            addSignatory(); // Adiciona o primeiro signatário ao carregar a página
        });

        function disableTabsClick() {
            $('.nav-tabs .nav-link').on('click', function (e) {
                e.preventDefault();
                return false;
            });
        }

        function validateDocument() {
            var title = document.getElementById('documentTitle').value;
            if (title) {
                $('.nav-tabs a[href="#custom-tabs-one-sender"]').tab('show');
            } else {
                toastr.warning('Por favor, preencha o título do documento.');
            }
        }

        function validateSender() {
            var name = document.getElementById('senderName').value;
            var email = document.getElementById('senderEmail').value;
            if (name && email) {
                $('.nav-tabs a[href="#custom-tabs-one-signatories"]').tab('show');
            } else {
                toastr.warning('Por favor, preencha todos os campos do remetente.');
            }
        }

        function validateSignatories() {
            document.getElementById("finalButton").disabled = true;
            $("#loading").modal({
                backdrop: 'static',
                keyboard: false
            });

            var documentTitle = document.getElementById('documentTitle').value;
            var senderName = document.getElementById('senderName').value;
            var senderEmail = document.getElementById('senderEmail').value;

            var pathArray = window.location.pathname.split('/');
            var uploadId = pathArray[pathArray.length - 1];

            var signatories = document.querySelectorAll('.signatory');
            var signers = [];
            var electronicSigners = [];
            var isValid = true;
            let step = 1

            signatories.forEach(function (signatory) {
                var name = signatory.querySelector('.signatoryName').value;
                var email = signatory.querySelector('.signatoryEmail').value;
                var cpf = signatory.querySelector('.signatoryCPF').value || null;
                var type = signatory.querySelector('.signatoryType').value;

                if (!name || !email || !cpf) {
                    isValid = false;
                } else {
                    let signatoryData = {
                        step: step,
                        title: "Signer",
                        name: name,
                        email: email,
                        individualIdentificationCode: cpf
                    };

                    if (type === "Assinatura Digital") {
                        signers.push(signatoryData);
                    } else if (type === "Assinatura Eletrônica") {
                        electronicSigners.push(signatoryData);
                    }
                    step++;
                }
            });

            if (isValid && (signers.length > 0 || electronicSigners.length > 0)) {
                var formData = {
                    documentTitle,
                    sender: {
                        name: senderName,
                        email: senderEmail
                    },
                    signers: signers,
                    electronicSigners: electronicSigners,
                    uploadId: uploadId
                };

                var formDataJson = JSON.stringify(formData);


                var postUrl = '<?php echo BASE_URL;?>/assinatura/signatarios';

                fetch(postUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: formDataJson
                })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Falha na requisição com status: ' + response.status);
                    })
                    .then(data => {
                        window.location.replace(`<?php echo BASE_URL; ?>/assinatura/signatario/${data.id}?new_document=true`);
                    })
                    .catch(error => {
                        $("#loading").on('shown.bs.modal', function () {
                            $(this).modal('hide');
                        });
                        document.getElementById("finalButton").disabled = true;
                        toastr.error('Ocorreu um erro ao enviar o registro');
                    });


            } else {
                $("#loading").on('shown.bs.modal', function () {
                    $(this).modal('hide');
                });
                document.getElementById("finalButton").disabled = true;
                toastr.warning('Por favor, preencha todos os campos obrigatórios e adicione pelo menos um signatário.');
            }
        }


        function addSignatory() {
            var signatoriesSection = document.getElementById('signatoriesSection');
            var index = document.querySelectorAll('.signatory').length;
            var signatoryBlock = `
        <div class="signatory mb-3" id="signatory-${index}">
            <div class="form-group">
                <label>Nome <span style="color: red">*</span></label>
                <input type="text" class="form-control signatoryName" placeholder="Nome do Signatário">
            </div>
            <div class="form-group">
                <label>Email <span style="color: red">*</span></label>
                <input type="email" class="form-control signatoryEmail" placeholder="Email do Signatário">
            </div>
            <div class="form-group">
                <label>CPF (APENAS OS NÚMEROS) <span style="color: red">*</span></label>
                <input type="text" class="form-control signatoryCPF" placeholder="CPF do Signatário">
            </div>
            <div class="form-group">
                <label>Tipo de Assinatura <span style="color: red">*</span></label>
                <select class="form-control signatoryType">
                    <option>Assinatura Digital</option>
                    <option>Assinatura Eletrônica</option>
                </select>
            </div>
            <button type="button" class="btn btn-danger" onclick="removeSignatory('signatory-${index}')">Remover Signatário</button>
        </div>
    `;
            signatoriesSection.insertAdjacentHTML('beforeend', signatoryBlock);
        }

        function removeSignatory(id) {
            var signatory = document.getElementById(id);
            if (signatory) {
                signatory.remove();
            }
        }

        function goBack(fromTab, toTab) {
            $('.nav-tabs a[href="#' + toTab + '"]').tab('show');
        }

    </script>


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