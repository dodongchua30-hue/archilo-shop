<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $guarded = [];
    public function product() { return $this->belongsTo(Product::class); }

    /** "M" for a plain item, "M · Black" when the product has a named colour. */
    public function label(): string
    {
        return $this->color && $this->color !== 'Classic' ? "{$this->size} · {$this->color}" : $this->size;
    }
}
