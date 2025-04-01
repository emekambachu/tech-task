<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    private function getSelfieImage(): ?string
    {
        $selfie = $this->getSelfie();
        if (!empty($selfie) && !str_starts_with($selfie, 'http')) {
            $selfie = Storage::url($selfie);
        }
        return $selfie;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'country' => $this->getCountry(),
            'gender' => $this->getGender(),
            'selfie' => $this->getSelfieImage(),
            'introduction' => $this->getIntroduction(),
        ];
    }
}
