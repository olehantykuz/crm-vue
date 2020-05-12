<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount / 100,
            'description' => $this->description,
            'currency' => $this->currency->code,
            'categoryId' => $this->category_id,
            'userId' => $this->user_id,
            'amountByCurrency' => $this->denormalized ? $this->denormalized->formatted_currencies_amount : null,
            'date' => $this->created_at->timestamp,
            'category' => new Category($this->whenLoaded('category')),
        ];
    }
}
