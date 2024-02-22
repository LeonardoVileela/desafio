<?php require_once __DIR__ . '/../default/head_initial.php'; ?>

<?php
$menu = "documents";
$pageName = "Adicionar Signatários";
?>
<?php require_once __DIR__ . '/../default/head_final.php'; ?>
    <div class="row">

        <div class="col-md-12">

            <div class="card card-primary">
                <div class="card-body" id="signatoriesSection">
                    <!-- Signatários serão adicionados aqui dinamicamente -->
                </div>
                <div class="card-footer">
                    <a href="<?php echo BASE_URL; ?>/assinatura/signatario/<?php echo $signatureId; ?>">
                        <button type="button" class="btn btn-secondary">Voltar</button>
                    </a>

                    <button type="button" class="btn btn-primary" onclick="addSignatory()">Adicionar
                        Signatário
                    </button>
                    <button type="button" class="btn btn-primary" onclick="validateSignatories()">
                        Finalizar
                    </button>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="loading">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">CADASTRANDO SIGNATÁRIOS</h4>

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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            addSignatory(); // Adiciona o primeiro signatário ao carregar a página
        });


        function validateSignatories() {
            $("#loading").modal({
                backdrop: 'static',
                keyboard: false
            });


            var signatories = document.querySelectorAll('.signatory');
            var signers = [];
            var electronicSigners = [];
            var isValid = true;

            signatories.forEach(function (signatory) {
                var name = signatory.querySelector('.signatoryName').value;
                var email = signatory.querySelector('.signatoryEmail').value;
                var cpf = signatory.querySelector('.signatoryCPF').value || null;
                var type = signatory.querySelector('.signatoryType').value;

                if (!name || !email || !cpf) {
                    isValid = false;
                } else {
                    var signatoryData = {
                        step: 1,
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
                }
            });


            if (isValid && (signers.length > 0 || electronicSigners.length > 0)) {
                var formData = {
                    signers: signers,
                    electronicSigners: electronicSigners
                };

                var formDataJson = JSON.stringify(formData);


                var postUrl = '<?php echo BASE_URL;?>/assinatura/adicionar_signatario/<?php echo $signatureId; ?>';

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
                        window.location.replace(`<?php echo BASE_URL; ?>/assinatura/signatario/${data.id}?new_signer=true`);
                    })
                    .catch(error => {
                        $("#loading").on('shown.bs.modal', function () {
                            $(this).modal('hide');
                        });
                        toastr.error('Ocorreu um erro ao enviar o registro: ' + error.message);
                    });


            } else {
                $("#loading").on('shown.bs.modal', function () {
                    $(this).modal('hide');
                });
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
                <label>Tipo de Assinatura  <span style="color: red">*</span></label>
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


    </script>


<?php require_once __DIR__ . '/../default/footer_final.php'; ?>