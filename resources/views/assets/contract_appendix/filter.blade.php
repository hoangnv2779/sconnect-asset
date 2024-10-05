<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                    <div class="form-group col-3">
                        <label class="tw-font-bold">Tên phụ lục</label>
                        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Nhập tên/mã phụ lục">
                    </div>
                    <div class="form-group col-3">
                        <label class="tw-font-bold">Hợp đồng</label>
                        <select class="form-control select2" multiple="multiple" id="filterContract" data-placeholder="Chọn hợp đồng">
                            <template x-for="contract in listContract">
                                <option :value="contract.id" x-text="contract.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group col-2">
                        <label class="tw-font-bold">Trạng thái</label>
                        <select class="form-control select2" multiple="multiple" id="filterStatusContract" data-placeholder="Chọn trạng thái">
                            <template x-for="(value, key) in listStatus">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label class="tw-font-bold">Ngày ký</label>
                        <input type="text" class="form-control datepicker" id="filterSigningDate" placeholder="Ngày ký" autocomplete="off">
                    </div>
                    <div class="form-group col-3">
                        <label class="tw-font-bold">Ngày hiệu lực</label>
                        <input type="text" class="form-control datepicker" id="filterFrom" placeholder="Ngày hiệu lực" autocomplete="off">
                    </div>
                    <div>
                        <button @click="getList(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>