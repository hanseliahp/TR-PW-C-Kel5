{{--
  Komponen Reusable Modal Konfirmasi Hapus (Bootstrap 5)
  Mencegah aksi penghapusan tidak disengaja sesuai standar keamanan & UX (BR-08)
--}}

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg p-2" style="border-radius: var(--radius-xl);">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="brand-icon-box" style="background-color: #FEE2E2; color: #DC2626; width: 36px; height: 36px; border-radius: 10px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h5 class="modal-title fs-6 fw-bold text-dark" id="deleteConfirmModalLabel">Konfirmasi Penghapusan</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Batal"></button>
            </div>
            <div class="modal-body py-3">
                <p class="text-secondary mb-1" id="deleteModalMessage">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini bersifat permanen dan data yang telah dihapus tidak dapat dipulihkan.
                </p>
                <div id="deleteModalExtraWarning" class="alert alert-warning py-2 px-3 small mt-3 mb-0 d-none" style="border-radius: var(--radius-md);">
                    <i class="bi bi-info-circle me-1"></i> Catatan: Pastikan data tidak memiliki relasi yang sedang aktif.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="deleteConfirmForm" method="POST" action="" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-trash3-fill"></i>
                        <span>Ya, Hapus Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const actionUrl = button.getAttribute('data-action');
                const itemName = button.getAttribute('data-name');
                const warning = button.getAttribute('data-warning');

                const form = deleteModal.querySelector('#deleteConfirmForm');
                const msg = deleteModal.querySelector('#deleteModalMessage');
                const warnBox = deleteModal.querySelector('#deleteModalExtraWarning');

                if (actionUrl) {
                    form.setAttribute('action', actionUrl);
                }

                if (itemName) {
                    msg.innerHTML = `Apakah Anda yakin ingin menghapus <strong>"${itemName}"</strong>? Tindakan ini bersifat permanen dan tidak dapat dipulihkan.`;
                } else {
                    msg.innerHTML = `Apakah Anda yakin ingin menghapus data ini? Tindakan ini bersifat permanen dan tidak dapat dipulihkan.`;
                }

                if (warning) {
                    warnBox.innerHTML = `<i class="bi bi-info-circle me-1"></i> ${warning}`;
                    warnBox.classList.remove('d-none');
                } else {
                    warnBox.classList.add('d-none');
                }
            });
        }
    });
</script>
