<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'Payment';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'InvoiceId',
        'Amount',
        'Method',
        'TransactionRef',
        'Status',
        'IsActive',
        'Notes',
        'PaymentDate',
    ];

    public function GetAllPayments()
    {
        return DB::select('CALL sp_GetAllPayments()');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'InvoiceId', 'Id');
    }
}