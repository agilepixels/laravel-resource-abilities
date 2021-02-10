<?php

namespace AgilePixels\ResourceAbilities\Tests\Fakes;

use AgilePixels\ResourceAbilities\ProcessesAbilities;
use AgilePixels\ResourceAbilities\HasRelationships;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ProcessesAbilities, HasRelationships;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
