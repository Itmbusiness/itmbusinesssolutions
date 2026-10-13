<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['consultant_customer_id', 'body'])]
class ConsultantCustomerNote extends Model
{
    /** @return BelongsTo<ConsultantCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(ConsultantCustomer::class, 'consultant_customer_id');
    }
}
