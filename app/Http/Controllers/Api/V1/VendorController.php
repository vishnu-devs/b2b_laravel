<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\VendorRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function submitVendorRequest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'business_name' => 'required|string',
                'business_type' => 'required|string',
                'gst_number' => 'required|string',
                'pan_number' => 'required|string',
                'business_address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'required|string',
                'contact_person_name' => 'required|string',
                'contact_person_phone' => 'required|string',
                'alternate_phone' => 'nullable|string',
                'bank_name' => 'required|string',
                'account_number' => 'required|string',
                'ifsc_code' => 'required|string',
                'branch_name' => 'required|string',
                'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create vendor request
            $vendorRequest = VendorRequest::create([
                'user_id' => auth()->id(),
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'gst_number' => $request->gst_number,
                'pan_number' => $request->pan_number,
                'address' => $request->business_address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'alternate_phone' => $request->alternate_phone,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'branch_name' => $request->branch_name,
                'status' => 0
            ]);

            // Upload documents and associate with vendor request
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = $document->store('vendor_documents', 'public');
                    $vendorRequest->documents()->create([
                        'document_type' => $document->getClientOriginalName(), // You might want a more specific type here
                        'path' => $path,
                    ]);
                }
            }



            return response()->json([
                'status' => 'success',
                'message' => 'Vendor request submitted successfully',
                'data' => $vendorRequest
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Vendor request submission failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit vendor request'
            ], 500);
        }
    }

    public function updateVendorRequestStatus(Request $request, VendorRequest $vendorRequest)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:approved,rejected',
                'rejection_reason' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendorRequest->status = $request->status === 'approved' ? 1 : 2; // 1 for approved, 2 for rejected
            $vendorRequest->rejection_reason = $request->rejection_reason;
            $vendorRequest->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Vendor request status updated successfully',
                'data' => $vendorRequest
            ]);

        } catch (\Exception $e) {
            \Log::error('Vendor request status update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update vendor request status'
            ], 500);
        }
    }

}