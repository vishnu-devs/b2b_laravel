<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorRequest extends Model
{
    protected $table = 'vendor_requests';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'gst_number',
        'pan_number',
        'address',
        'city',
        'state',
        'pincode',
        'bank_name',
        'account_number',
        'ifsc_code',
        'status',
        'contact_person_name',
        'contact_person_phone',
        'alternate_phone',
        'branch_name',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'pending';
            case 1:
                return 'approved';
            case 2:
                return 'rejected';
            default:
                return 'unknown';
        }
    }
}