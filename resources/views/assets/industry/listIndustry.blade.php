@extends('layouts.app',[
    'title' => 'Ngành hàng'
])

@section('content')
    <div x-data="industry">
        <div class="tw-mb-3 d-flex tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handShowModalIndustryUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('assets.industry.filterIndustry')
        </div>

        <div
            @edit="handShowModalIndustryUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div>
            <div
                @save-industry="handleIndustryUI">
                @include('assets.industry.modalIndustryUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa ngành hàng này không ?'
            }"
                @ok="removeIndustry"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/api/apiIndustry.js"></script>
    <script src="/assets/js/listIndustry.js"></script>
@endsection
