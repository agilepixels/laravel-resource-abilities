<?php

namespace AgilePixels\ResourceAbilities\Tests\Fakes;

use AgilePixels\ResourceAbilities\HasRelationships;
use AgilePixels\ResourceAbilities\JsonResource\ProcessesAbilities;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use ProcessesAbilities;
    use HasRelationships;

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
