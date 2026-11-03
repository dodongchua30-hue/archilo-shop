<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    public function variants() { return $this->hasMany(Variant::class); }
    public function inStock(): bool { return $this->variants->sum('stock') > 0; }
}
