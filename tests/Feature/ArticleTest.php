<?php

namespace Tests\Feature;

use App\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group article
 */
class ArticleTest extends TestCase
{
    use WithFaker;

    public function setUp()
    {
        parent::setUp();
        // storage filesystem_driver is configured in phpunit.xml.dist as 'testing', which maps to the disk at
        // framework/testing/disks/testing anyways. However, when using Storage::fake(), the disk is
        // cleared, which helps preventing disk pollution.
        Storage::fake();
    }

    /**
     * @test
     */
    public function it_creates_a_flatfile_when_it_is_created()
    {
        // Given we have an article
        $article = make_fake_article();
        // and this article is not yet persisted
        Storage::assertMissing($article->getPath());

        // When we persist the article
        $article->save();

        // Then we expect a flatfile to be created according to the article's path
        Storage::assertExists($article->getPath());
    }

    /**
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function it_moves_a_flatfile_when_its_path_is_updated()
    {
        // Given we have a persisted article
        $article = create_fake_article();

        // And this article has an associated flat file
        $oldPath = $article->getPath();
        Storage::assertExists($oldPath);

        // When we update the article path
        $article->setPath(fake_hierarchical_slug($this->faker->numberBetween(1, 4)));
        $article->save();

        // Then we expect a flatfile to be created according to the article's path
        Storage::assertExists($article->getPath());
        // And we expect the old path to be gone
        Storage::assertMissing($oldPath);
    }

    /**
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function it_loads_the_content_of_an_article_when_requested()
    {
        // Given we create an article with arbitrary content
        $article = create_fake_article();
        // When we put defined content into the linked file
        $expectedMarkdown = '# Heading';
        $content = $expectedMarkdown;
        Storage::put($article->getPath(), $content);
        // And refresh the article
        $article = (new Article())->load($article->getPath());
        // Then we expect the article markdown to match the content of the file
        $this->assertEquals($expectedMarkdown, $article->getMarkdown());
    }

    /**
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function it_creates_a_flatfile_with_the_article_content_when_the_article_is_created()
    {
        $article = create_fake_article();
        $expected = $article->serialize();
        $actual = Storage::get($article->getPath());
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function it_updates_the_content_when_the_article_is_updated()
    {
        $article = create_fake_article();
        $expectedFrontmatter = $article->serializeFrontmatter();
        $expectedMarkdown = $this->faker->paragraph;
        $article->setMarkdown($expectedMarkdown);
        $article->save();
        $actual = Storage::get($article->getPath());
        $this->assertEquals("---\n".$expectedFrontmatter."---\n".$expectedMarkdown, $actual);
    }

    /**
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function it_serializes_frontmatter()
    {
        $article = create_fake_article();
        $expectedTitle = $this->faker->sentence;
        $article->updateFrontmatter(['title' => $expectedTitle]);
        $article->save();
        $article = $article->fresh();
        $this->assertEquals($expectedTitle, $article->getFrontmatter('title'));
    }

    /**
     * @test
     */
    public function it_deletes_the_flatfile()
    {
        $article = create_fake_article();
        Storage::assertExists($article->getPath());
        $article->delete();
        Storage::assertMissing($article->getPath());
    }
}
