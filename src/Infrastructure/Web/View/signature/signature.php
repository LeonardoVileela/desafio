<?php require_once __DIR__ . '/../default/head_initial.php'; ?>

    <!-- DataTables -->
    <link rel="stylesheet"
          href="<?php echo BASE_URL_PUBLIC; ?>plugins/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
          href="<?php echo BASE_URL_PUBLIC; ?>plugins/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
          href="<?php echo BASE_URL_PUBLIC; ?>plugins/buttons.bootstrap4.min.css">

<?php
$menu = "documents";
$pageName = "Documento";
?>
<?php require_once __DIR__ . '/../default/head_final.php'; ?>

    <a style="font-size: 20px;" href="<?php echo BASE_URL; ?>/assinatura/documentos">
        <i class="fas fa-chevron-left"></i> Voltar
    </a>

    <div class="row">

    <div class="col-md-12">

        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-info">
                <h3 class="widget-user-username"><?php echo $signature->documentTitle; ?></h3>
                <h5 class="widget-user-desc"></h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle elevation-2" src="<?php echo BASE_URL_PUBLIC; ?>images/book.jpg"
                     alt="User Avatar">
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-2 border-right">
                        <div class="description-block">
                            <h5 class="description-header"><?php echo $signature->id; ?></h5>
                            <span class="description-text">ID</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header"><?php echo $signature->chave; ?></h5>
                            <span class="description-text">CHAVE</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 border-right">
                        <div class="description-block">
                            <a href="<?php echo BASE_URL; ?>/assinatura/download/<?php echo $signature->chave; ?>"><h5
                                        class="description-header"><?php echo $signature->documentName; ?></h5></a>
                            <span class="description-text">DOWNLOAD DO ARQUIVO COM ASSINATURAS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 border-right">
                        <div style="display: flex; justify-content: center;" class="description-block">
                            <button onclick="getSignatures('<?php echo $signature->chave; ?>')" style="max-width: 200px"
                                    type="button"
                                    class="btn btn-block btn-success">
                                Assinaturas
                            </button>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2">
                        <div style="display: flex; justify-content: center;" class="description-block">
                            <button data-toggle="modal" data-target="#send_email" style="max-width: 200px"
                                    type="button"
                                    class="btn btn-block btn-secondary">
                                <i class="fas fa-envelope"></i> Reenviar E-mail
                            </button>

                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.widget-user -->
        <div style="padding-right: 0 !important; padding-left: 0 !important;" class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Signatários</h3>
                    <div style="text-align: right;">
                        <a href="<?php echo BASE_URL; ?>/assinatura/adicionar_signatario/<?php echo $signature->id; ?>">
                            <button class="btn btn-primary">Adicionar signatário</button>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>CPF</th>
                            <th>Assinatura</th>
                            <th>Link para assinatura</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($attendees as $attendee) { ?>
                            <tr>
                                <td><?php echo $attendee->signerId; ?></td>
                                <td><?php echo $attendee->name; ?></td>
                                <td><?php echo $attendee->email; ?></td>
                                <td><?php echo $attendee->individualIdentificationCode; ?></td>
                                <td><?php echo $attendee->action; ?></td>
                                <?php if ($attendee->signUrl === "email") { ?>
                                    <td>Link acessado apenas por e-mail</td>
                                <?php } else { ?>
                                    <td style="text-align: center;">

                                        <button class="btn btn-info"
                                                onclick="copyLink('<?php echo $attendee->signUrl; ?>')">
                                            <i class="far fa-copy"></i> Copiar Link
                                        </button>

                                        <a target="_blank" href="<?php echo $attendee->signUrl; ?>">
                                            <button class="btn btn-primary"><i class="fas fa-link"></i> Abrir Link
                                            </button>
                                        </a>
                                    </td>
                                <?php } ?>
                                <td style="text-align: center">
                                    <button onclick="showModalDelete('<?php echo (count($attendees) > 1) ? "#modal_delete" : "#modal_alert";
                                    ?>', '<?php echo $signature->id; ?>', '<?php echo $attendee->signerId; ?>' , '<?php echo $attendee->individualIdentificationCode; ?>' )"
                                            class="btn btn-danger"><i class="fas fa-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>


                        </tbody>

                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="modal fade" id="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Assinaturas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Assinaturas com certificado digital
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" id="digital_signature">
                            <div class="overlay">
                                <i class="fas fa-2x fa-sync fa-spin"></i>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->


                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Assinaturas Eletrônicas
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" id="electronic_signature">
                            <div class="overlay">
                                <i class="fas fa-2x fa-sync fa-spin"></i>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="modal_alert">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Aviso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Não é possível excluir quando há apenas um signatário no documento. Primeiro, adicione um novo
                        signatário; após esse procedimento, será possível excluir o signatário.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="modal_delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Atenção</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="font-size: 16px;">
                        <p>Só é possível excluir signatários que ainda <strong>não assinaram</strong> o documento. Se
                            você deseja verificar quem já assinou antes de prosseguir com a exclusão, por favor, clique
                            no botão <strong>"Assinaturas"</strong>.</p>
                        <p>Caso tenha certeza de que deseja excluir o signatário e entende que esta ação é irreversível
                            para aqueles que ainda não assinaram, clique em <strong>"Sim"</strong> para confirmar a
                            exclusão.</p>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button id="button_delete"
                            onclick="deleteSigner()"
                            type="button" class="btn btn-warning">SIM
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">NÃO</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="send_email">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Atenção</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="font-size: 18px">Um e-mail de lembrete para assinatura do documento será enviado a todos
                        os signatários que ainda não o assinaram. Deseja prosseguir com o envio?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="button_delete"
                            onclick="sendEmail('<?php echo $signature->id; ?>')"
                            type="button" class="btn btn-primary">SIM
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">NÃO</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <?php require_once __DIR__ . '/../default/footer_initial.php'; ?>

    <!-- DataTables  & Plugins -->
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/dataTables.responsive.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/responsive.bootstrap4.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/dataTables.buttons.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/jszip.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/pdfmake.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/vfs_fonts.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/buttons.html5.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/buttons.print.min.js"></script>
    <script src="<?php echo BASE_URL_PUBLIC; ?>plugins/buttons.colVis.min.js"></script>


    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);

            const newSigner = urlParams.get('new_signer');
            if (newSigner === 'true') {
                toastr.success('Novo signatário cadastrado com sucesso.');
            }

            const sendEmail = urlParams.get('send_email');
            if (sendEmail === 'true') {
                toastr.success('E-mail enviado com sucesso.');
            }

            const deleteUrl = urlParams.get('delete');
            if (deleteUrl === 'true') {
                toastr.success('Signatário deletado com sucesso.');
            }

            const newDocument = urlParams.get('new_document');
            if (newDocument === 'true') {
                toastr.success('Documento cadastrado com sucesso.');
            }

            const baseUrl = window.location.pathname + window.location.hash;

            window.history.pushState({path: baseUrl}, '', baseUrl);
        });
    </script>

    <script>
        function copyLink(url) {
            if (navigator.clipboard) { // Verifica se a API de Área de Transferência está disponível
                navigator.clipboard.writeText(url).then(function () {
                    toastr.success('Link copiado para a área de transferência!');
                }).catch(function (error) {
                    console.error('Erro ao copiar o link: ', error);
                });
            } else {
                toastr.warning('A cópia para a área de transferência não é suportada neste navegador.');
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            let electronicSignatureOriginalHTML = $('#electronic_signature').html();
            let digitalSignatureOriginalHTML = $('#digital_signature').html();

            function restoreOriginalHTML() {
                $('#electronic_signature').html(electronicSignatureOriginalHTML);
                $('#digital_signature').html(digitalSignatureOriginalHTML);
            }

            $('#modal-overlay').on('hidden.bs.modal', function () {
                restoreOriginalHTML();
            });
        });

    </script>

    <script>
        function sendEmail(documentId) {

            const formData = {
                documents: [documentId]
            };

            let formDataJson = JSON.stringify(formData);


            let postUrl = `<?php echo BASE_URL;?>/assinatura/enviar_email/${documentId}`;

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

                    window.location.replace(`<?php echo BASE_URL; ?>/assinatura/signatario/${documentId}?send_email=true`);
                })
                .catch(error => {
                    $("#loading").modal('hide');
                    toastr.error('Ocorreu um erro ao enviar o registro: ' + error.message);
                });

        }
    </script>

    <script>
        let documentId = null;
        let signerId = null;
        let individualIdentificationCode = null;

        function showModalDelete(modalDelete, documentIDelete, signerIdDelete, individualIdentificationCodeDelete) {
            if (modalDelete === "#modal_delete") {
                documentId = documentIDelete;
                signerId = signerIdDelete;
                individualIdentificationCode = individualIdentificationCodeDelete;
            }

            $(modalDelete).modal('show');

        }

        function deleteSigner() {
            document.getElementById("button_delete").disabled = true;
            const formData = {
                documentId: documentId,
                stageFlowId: signerId,
                individualIdentificationCode: individualIdentificationCode
            };

            let formDataJson = JSON.stringify(formData);


            let postUrl = `<?php echo BASE_URL;?>/assinatura/deletar_assinatura/${documentId}`;

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
                    throw new Error(`Falha na requisição com status: ${response.status}`);
                })
                .then(data => {
                    window.location.replace(`<?php echo BASE_URL; ?>/assinatura/signatario/${documentId}?delete=true`);
                })
                .catch(error => {
                    toastr.error('Ocorreu um erro ao enviar o registro: ' + error.message);
                    document.getElementById("button_delete").disabled = false;
                });

        }
    </script>


    <script>
        function getSignatures(chave) {
            $("#modal-overlay").modal('show');
            let electronicSignatureDiv = document.getElementById('electronic_signature');
            let digitalSignatureDiv = document.getElementById('digital_signature');


            fetch(`<?php echo BASE_URL; ?>/assinatura/assinaturas/${chave}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    let electronicSignatures = data.electronicSignatures;

                    let electronicSignaturesString = '';

                    if (electronicSignatures.length === 0) {
                        electronicSignaturesString = `<dt>Nenhuma assinatura eletrônica encontrada</dt>`
                    } else {

                        electronicSignaturesString = electronicSignatures.map(signature =>
                            `<dt>${signature.user}</dt>
                        <dd>
                            Data: ${formatBrazilianDate(signature.date)}
                            <br>
                            CPF: ${signature.identifier}
                        </dd>
                        `
                        ).join('');
                    }

                    electronicSignatureDiv.innerHTML = `
                        <dl>
                            ${electronicSignaturesString}
                        </dl>
                    `;

                    let digitalSignatures = data.digitalSignatures;

                    let digitalSignaturesString = '';

                    if (digitalSignatures.length === 0) {
                        digitalSignaturesString = `<dt>Nenhuma assinatura digital encontrada</dt>`
                    } else {
                        digitalSignaturesString = digitalSignatures.map(signature =>
                            `<dt>${signature.user}</dt>
                        <dd>
                            Data: ${formatBrazilianDate(signature.date)}
                            <br>
                            CPF: ${signature.identifier}
                        </dd>
                        `
                        ).join('');
                    }

                    digitalSignatureDiv.innerHTML = `
                        <dl>
                            ${digitalSignaturesString}
                        </dl>
                    `;

                })
                .catch(error => console.error('Erro ao buscar dados:', error));

        }
    </script>


    <script>
        function formatBrazilianDate(dateString) {
            const date = new Date(dateString);

            const months = ["janeiro", "fevereiro", "março", "abril", "maio", "junho",
                "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"];

            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const seconds = date.getSeconds().toString().padStart(2, '0');

            return `${day} de ${month} de ${year}, às ${hours}:${minutes}:${seconds}`;
        }
    </script>

<?php require_once __DIR__ . '/../default/footer_final.php'; ?>