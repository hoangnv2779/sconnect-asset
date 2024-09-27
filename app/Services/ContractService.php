<?php

namespace App\Services;

use App\Repositories\ContractMonitorRepository;
use App\Repositories\ContractRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractService
{
    public function __construct(
        protected ContractRepository $contractRepository,
        protected ContractMonitorRepository $contractMonitorRepository
    )
    {

    }

    public function createContract($data)
    {
        $contract = $this->contractRepository->getFirst(['code' => $data['code']]);
        if (!empty($contract)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2023,
            ];
        }

        $data['created_by'] = Auth::id();
        DB::beginTransaction();
        try {
            $contract = $this->contractRepository->create($data);
            $dataCreateContractMonitor = [];
            foreach ($data['user_ids'] as $userId) {
                $dataCreateContractMonitor[] = [
                    'contract_id' => $contract->id,
                    'user_id' => $userId,
                ];
            }
            $insertContractMonitor = $this->contractMonitorRepository->insert($dataCreateContractMonitor);
            if (!$insertContractMonitor) {
                DB::rollBack();
                return [
                    'success' => false,
                    'error_code' => AppErrorCode::CODE_2025,
                ];
            }
            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2024,
            ];
        }

        return [
           'success' => true,
        ];
    }
}
