<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Serializer
    |--------------------------------------------------------------------------
    |
    | The serializer will be used for the conversion of abilities to their
    | array representation, when no serializer is explicitly defined for an
    | ability resource this serializer will be used.
    |
    */

    'serializer' => AgilePixels\ResourceAbilities\Serializers\AbilitySerializer::class,
];
