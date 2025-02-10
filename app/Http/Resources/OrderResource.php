<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'Order_ID' => $this->id,
            'User_ID' => $this->user_id,
            'Total_Price' => $this->total_price,
            'Status' => $this->status,
            'Payment_Status' => $this->payment_status,
            'State' => $this->state,
            'Created_At' => $this->created_at,
            'Updated_At' => $this->updated_at,
            'User' => new UserResource($this->whenLoaded('user')),
            'Products' => ProductResource::collection($this->whenLoaded('products')),
            'Payment' => new PaymentResource($this->whenLoaded('payment')),
        ];
        return parent::toArray($request);
    }
}
