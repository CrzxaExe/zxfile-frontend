<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $table = 'raks';
    protected $guarded = [];

    public function productionLogs()
    {
        return $this->hasMany(ProductionLog::class);
    }
}