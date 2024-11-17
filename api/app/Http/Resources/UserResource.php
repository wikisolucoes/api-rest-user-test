<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_type' => new UserTypeResource($this->whenLoaded('userType')),
            'name' => $this->name,
            'email' => $this->email,
            'document_type' => $this->document_type,
            'document' => $this->document,
            'telephone' => $this->telephone,
            'cellphone' => $this->cellphone,
            'business' => $this->business,
            'status' => $this->status,
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
