<?php

use App\Article;
use Illuminate\Support\Collection;

if (! function_exists('fake_hierarchical_slug')) {
    /**
     * Fakes hierarchical slugs, such as movies/my-short/movie.
     *
     * @param int $numberOfLevels 1 for a slug like my-movie, 2 for movies/my-movie. Levels are separated by '/'.
     *
     * @return string
     */
    function fake_hierarchical_slug(int $numberOfLevels = 1): string
    {
        if ($numberOfLevels < 1) {
            throw new InvalidArgumentException('numberOfLevels must be at least 1');
        }
        $faker = app('Faker\Generator');

        return Collection::times(
            // create a collection of 1 to 4 elements
            $numberOfLevels,
            // by calling this function as many times as given above
            function () use ($faker) {
                // each element representing a slug of max 3 words
                return $faker->slug(3, true);
            }
        )
        // and join them with /
        ->implode('/');
    }
}

if (! function_exists('make_fake_article')) {
    /**
     * new up a fake article (doesn't persist).
     *
     * @param array $attributeOverride assoc array with overrides. E.g. ['markdown' => '# Heading']
     *
     * @return Article
     */
    function make_fake_article(array $attributeOverride = []): Article
    {
        $faker = app('Faker\Generator');
        $attributes = array_merge([
            'path' => fake_hierarchical_slug($faker->numberBetween(1, 4)),
            'markdown' => $faker->paragraph,
            'frontmatter' => [
                'title' => $faker->sentence,
            ],
        ], $attributeOverride);

        return new \App\Article($attributes);
    }
}

if (! function_exists('create_fake_article')) {
    /**
     * new up a fake article and persist it.
     *
     * @param array $attributeOverride assoc array with overrides. E.g. ['markdown' => '# Heading']
     *
     * @return Article
     */
    function create_fake_article(array $attributeOverride = []): Article
    {
        $article = make_fake_article($attributeOverride);
        $article->save();

        return $article;
    }
}
