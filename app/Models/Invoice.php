<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'Invoice';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'InvoiceNumber',
        'ClientId',
        'InstructorId',
        'IssueDate',
        'DueDate',
        'Subtotal',
        'VATRate',
        'VATAmount',
        'TotalAmount',
        'Status',
        'IsActive',
        'Notes',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'InvoiceId', 'Id');
    }
}