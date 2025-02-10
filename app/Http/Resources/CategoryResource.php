<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'Category_ID' => $this->id,
            'Category_Name' => $this->name,
            'Category_Description' => $this->description,
            'Category_State' => $this->state, // Added state column
            'Created_At' => $this->created_at, // Optional
            'Updated_At' => $this->updated_at, // Optional
        ];
        return parent::toArray($request);
    }
}
