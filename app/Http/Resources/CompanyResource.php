<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'edrpou' => $this->edrpou,
            'address' => $this->address,
            'current_version' => $this->current_version ?? 1,
        ];

        if ($request->isMethod('post')) {
            $data['status'] = $this->resource->getOperationStatus();
        }

        return $data;
    }
}
