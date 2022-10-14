<?php

namespace App\Http\Controllers\Admin\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Venue\CancellationPolicyDataRequest;
use App\Models\Venue\CancellationPolicyData;

class CancellationPolicyDataController extends Controller
{
    public function index()
    {
        $cancellationPolicyData = CancellationPolicyData::all();
        return response()->json($cancellationPolicyData, 200);
    }

    public function store(CancellationPolicyDataRequest $request)
    {
        $cancellationPolicyData_data = $request->validated();
        CancellationPolicyData::create($cancellationPolicyData_data);
        return response()->json(['message' => 'Cancellation policy data added.'], 200);
    }

    public function show(CancellationPolicyData $cancellationPolicyDatum)
    {
        return response()->json($cancellationPolicyDatum, 200);
    }

    public function update(CancellationPolicyDataRequest $request, CancellationPolicyData $cancellationPolicyDatum)
    {
        $cancellationPolicyData_data = $request->validated();
        $cancellationPolicyDatum->update($cancellationPolicyData_data);
        return response()->json(['message' => 'Cancellation policy data updated'], 200);
    }

    public function destroy(CancellationPolicyData $cancellationPolicyData)
    {
        if ($cancellationPolicyData->is_used == 1) {
            return response()->json(['message' => 'Can not delete a used cancellation policy data'], 500);
        } else {
            $cancellationPolicyData->delete();
            return response()->json(['message' => 'Cancellation policy data was deleted.'], 200);
        }
    }
}
