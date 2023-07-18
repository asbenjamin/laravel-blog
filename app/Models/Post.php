<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $with = ['category', 'author'];

    // queryscopes
    // public function scopeFilter($query, array $filters) // allows Post::newQuery()->filter()
    // {
    //     if ($filters['search'] ?? false){
    //         $query
    //             ->where('title', 'like', '%' . request('search') . '%')
    //             ->orWhere('body', 'like', '%' . request('search') . '%');
    //     }
    // }

    // with query builder
    public function scopeFilter ($query, array $filters)
    {
        // note the arrow function, read more about this query->when thing, how the $search comes in?
        $query->when($filters['search'] ?? false, fn ($query, $search) => 
            $query->where(fn($query) => 
                $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
            )
            );


        $query->when($filters['category'] ?? false, fn ($query, $category) => 
            $query->whereHas('category', fn($query) => 
                    $query->where('slug', $category))
            );

        $query->when($filters['author'] ?? false, fn ($query, $author) => 
            $query->whereHas('author', fn($query) => 
                    $query->where('username', $author))
            );
    }


    protected $fillable = ['title', 'excerpt', 'body', 'slug', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
