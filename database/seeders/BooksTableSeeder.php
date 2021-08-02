<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
	public function run(): void
	{
        Book::factory(10)->create()->each(function(Book $book) {
			$book->children()->saveMany(Book::factory(2)->create()->each(function(Book $book) {
				$book->children()->saveMany(Book::factory(3)->create()->each(function (Book $book){}));
			}));
		});
	}

}

