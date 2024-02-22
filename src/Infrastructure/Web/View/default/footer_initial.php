<div class="modal fade" id="modal_logout">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Atenção</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="font-size: 20px"><strong>Tem certeza que deseja sair?</strong></p>
                <p style="16px">Sua sessão será encerrada. Certifique-se de salvar todas as alterações antes de
                    sair.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <form action="<?php echo BASE_URL; ?>/logout" method="post">
                    <button type="submit" class="btn btn-warning">SIM</button>
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">NÃO</button>
            </div>
        </div>
    </div>
</div>

</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Leonardo Vilela</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->



<!-- jQuery -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/adminlte.min.js"></script>

<!-- Toastr -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/toastr.min.js"></script>




