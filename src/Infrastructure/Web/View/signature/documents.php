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
$pageName = "Documentos";
?>

<?php require_once __DIR__ . '/../default/head_final.php'; ?>
    <div class="row">

        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Documentos Disponíveis</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Título</th>
                            <th>Nome</th>
                            <th>Chave</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($documents)) { ?>
                            <?php foreach ($documents as $document) { ?>
                                <tr>
                                    <td><?php echo $document->id; ?></td>
                                    <td><?php echo $document->title; ?></td>
                                    <td><?php echo $document->name; ?></td>
                                    <td><?php echo $document->chave; ?></td>
                                    <td style="text-align: center;">
                                        <a href="<?php echo BASE_URL; ?>/assinatura/signatario/<?php echo $document->signatureId; ?>">
                                            <button class="btn btn-primary"><i class="fas fa-eye"></i> Detalhes</button>
                                        </a>

                                        <button onclick="showModalDelete('<?php echo $document->signatureId; ?>' )"
                                                class="btn btn-danger"><i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>

                        </tbody>

                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>


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
                    <p style="font-size: 20px">Você realmente deseja excluir este documento?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="button_delete"
                            onclick="deleteDocument()"
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

            const deleteDocument = urlParams.get('deleteDocument');
            if (deleteDocument === 'true') {
                toastr.success('Documento deletado com sucesso.');
            }

            const baseUrl = window.location.pathname + window.location.hash;

            window.history.pushState({path: baseUrl}, '', baseUrl);
        });
    </script>

    <script>
        let signatureId = null;

        function showModalDelete(signatureIdDelete) {
            signatureId = signatureIdDelete;
            $("#modal_delete").modal('show');
        }
    </script>

    <script>
        function deleteDocument() {
            document.getElementById("button_delete").disabled = true;

            let postUrl = `<?php echo BASE_URL;?>/assinatura/documento/${signatureId}`;

            fetch(postUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Falha na requisição: ' + response.statusText);
                })
                .then(data => {
                    window.location.replace(`<?php echo BASE_URL; ?>/assinatura/documentos?deleteDocument=true`);
                })
                .catch(error => {
                    toastr.error('Ocorreu um erro ao enviar o registro');
                    document.getElementById("button_delete").disabled = false;
                });
        }

    </script>

<?php require_once __DIR__ . '/../default/footer_final.php'; ?>