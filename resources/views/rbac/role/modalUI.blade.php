<div class="modal fade" id="idModalUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleModal + ' vai trò'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên vai trò<label class="tw-text-red-600">*</label></label>
                    <input type="text" class="form-control" x-model="role.name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control tw-h-40" x-model="role.description"></textarea>
                </div>

                <div class="mb-3" x-if="action === 'update'">
                    <div>1111111</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="action === 'create' ? create() : edit()" type="button" class="btn btn-sc">Lưu</button>
            </div>
        </div>
    </div>
</div>

