<?php

namespace App;

use App\Traits\TracksAttributeChanges;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Exception\ParseException;

class Article implements Arrayable
{
    use TracksAttributeChanges;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $frontmatter;

    /**
     * @var string
     */
    protected $markdown;

    /**
     * Article constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->constructWithAttributes($attributes);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMarkdown(): string
    {
        return $this->markdown;
    }

    public function setMarkdown(string $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * @param null|string $key
     * @param null        $default
     *
     * @return array|mixed|null
     */
    public function getFrontmatter(?string $key = null, $default = null)
    {
        if (null === $key) {
            return $this->frontmatter;
        }

        return $this->frontmatter[$key] ?? $default;
    }

    public function updateFrontmatter(array $assoc): array
    {
        $this->frontmatter = array_merge($this->frontmatter, $assoc);

        return $this->frontmatter;
    }

    public function setFrontmatter(array $assoc): array
    {
        $this->frontmatter = $assoc;

        return $this->frontmatter;
    }

    /**
     * @param string $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws ParseException                                         when the Yaml is syntactically wrong
     *
     * @return Article
     */
    public function load(string $path = null): Article
    {
        $this->path = $path ?? $this->path;
        $file = (new ArticleFileLoader())->load($this->path);

        $this->markdown = $file->markdown();
        $this->frontmatter = $file->frontmatter();
        $this->markUnchanged();

        return $this;
    }

    /**
     * Reload this article.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return Article
     */
    public function fresh(): Article
    {
        return (new Article())->load($this->path);
    }

    public function save()
    {
        (new ArticleFileSaver($this->original, $this->toArray()))->save();

        $this->markUnchanged();
    }

    public function delete()
    {
        Storage::delete($this->path);
    }

    public function toArray(): array
    {
        $reflection = new \ReflectionClass(get_class($this));
        $array = [];
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            if ('original' !== $propertyName) {
                $property->setAccessible(true);
                $array[''.$property->getName()] = $property->getValue($this);
                $property->setAccessible(false);
            }
        }

        return $array;
    }

    public function serialize()
    {
        return (new ArticleFileSaver($this->original, $this->toArray()))->serialize();
    }

    public function serializeFrontmatter()
    {
        return (new ArticleFileSaver($this->original, $this->toArray()))->serializeFrontmatter();
    }
}
