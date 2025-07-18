<?php

namespace App\Models\Bde;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Entity representing a Cart (or a container of single item orders)
 *
 * @property int $id Identifier of the cart
 * @property Date $created_at Date representation of when the cart was submitted
 * @property Date $updated_at Date representation of when the cart was validated
 * @property float $price Total price of the Cart
 * @property idk $status One of the following: "DRAFT" | "VALIDATED"
 */
class Cart extends Model
{
    use HasFactory;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'bde_bdd';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['price', 'member_id'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'price' => 0,
        'status' => 'waiting',
    ];

    /**
     * Get the orders lines for the cart
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the client for the cart
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
