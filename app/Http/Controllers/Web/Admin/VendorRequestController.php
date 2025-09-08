<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class VendorRequestController extends Controller
{
    public function index()
    {
        $vendorRequests = VendorRequest::with('user')->paginate(10);
        return view('admin.vendor-requests.index', compact('vendorRequests'));
    }

    public function show(VendorRequest $vendorRequest)
    {
        return view('admin.vendor-requests.show', compact('vendorRequest'));
    }

    public function update(Request $request, VendorRequest $vendorRequest)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string',
        ]);

        $status = $validatedData['status'] === 'approved' ? 1 : 2;

        $vendorRequest->update([
            'status' => $status,
            'rejection_reason' => $validatedData['rejection_reason'] ?? null,
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        if ($status === 1) {
            $user = User::find($vendorRequest->user_id);
            $vendorRole = Role::where('name', 'vendor')->first();
            $user->assignRole($vendorRole);
        }

        return redirect()->route('admin.vendor-requests.index')
            ->with('success', 'Vendor request ' . $request->status . ' successfully');
    }
}