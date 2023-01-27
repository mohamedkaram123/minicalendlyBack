<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTree extends Model
{

    protected $table = "accounts_tree";

    /**
     * Get the accountTree that owns the AccountTree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountType()
    {
        return $this->belongsTo(TypeAccount::class, 'type_id');
    }
}
