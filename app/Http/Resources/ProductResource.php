<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'Product_ID' => $this->id,
            'Product_Name' => $this->name,
            'Product_Description' => $this->description,
            'Category_ID' => $this->category_id,
            'Price' => $this->price,
            'Discount' => $this->discount,
            'Stock_Quantity' => $this->stock_quantity,
            'Size_Options' => $this->size_options,
            'Color_Options' => $this->color_options,
            'Image_URL' => $this->image_url,
            'Product_State' => $this->state,
            'Created_At' => $this->created_at->toDateTimeString(),  // Formatting timestamp
            'Updated_At' => $this->updated_at->toDateTimeString(),
        ];
    }
}
