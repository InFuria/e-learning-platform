<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialAccount extends Model
{

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
