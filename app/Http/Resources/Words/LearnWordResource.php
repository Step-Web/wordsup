<?php

namespace App\Http\Resources\Words;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearnWordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = null;
    public function toArray(Request $request): array
    {
        return [
             'id'=>$this->id,
             'word'=>$this->word,
             'ts'=>$this->ts,
             'translate'=>$this->translate,
             'progress'=>$this->progress,
             'audio'=>$this->audio
        ];
    }
}
