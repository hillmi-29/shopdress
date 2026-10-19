<?php

namespace App\Observers;

use App\Models\Category;
use App\Support\StoredFile;

class CategoryObserver
{
    public function deleting(Category $category): void
    {
        StoredFile::delete($category->image);
    }
}
