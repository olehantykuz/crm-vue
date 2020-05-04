<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Currency extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $latestConversation = $this->latestConversation();

        return [
            'id' => $this->id,
            'code' => $this->code,
            'rate' => $latestConversation ? $latestConversation->rate : null,
            'date' => $this->updated_at->timestamp ?? Carbon::now()->timestamp,
        ];
    }
}
