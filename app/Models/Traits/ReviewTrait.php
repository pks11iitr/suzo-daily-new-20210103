<?php

namespace App\Models\Traits;
use DB;

trait ReviewTrait {

    public function reviews(){
        return $this->morphMany('App\Models\Review', 'entity');
    }

    public function avgreviews(){
        return $this->reviews()
            ->selectRaw('entity_id, Format(avg(rating), 1) as rating, count(*) as reviews')
            ->where('isactive', true)
            ->groupBy('entity_id');
    }

    public function commentscount(){
        return $this->reviews()->where('reviews.description', '!=', null)
            ->selectRaw('entity_id, count(*) as comments')
            ->where('isactive', true)
            ->groupBy('entity_id');;
    }

    public function comments(){
        return $this->reviews()->where('reviews.description', '!=', null);
    }







}
