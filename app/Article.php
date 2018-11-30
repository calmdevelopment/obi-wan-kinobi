<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ReflectionObject;
use Symfony\Component\Yaml\Yaml;

class Article implements Arrayable
{
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
     * Values when this model was initiated. Each attribute in the initial state. Used to determine changes.
     *
     * @var array
     */
    private $original;

    /**
     * Article constructor.
     */
    public function __construct(array $attributes = [])
    {
        // initiate original values array with initial values
        $this->markUnchanged();
        // now initiate attributes (we want to mark them changed)
        $this->setAttributes($attributes);
    }

    protected function setAttributes(array $attributes = [])
    {
        if (! empty($attributes)) {
            $article = new ReflectionObject($this);
            foreach ($attributes as $name=>$value) {
                // only set attribute if it is defined in this class
                if ($article->hasProperty($name)) {
                    $this->$name = $value;
                }
            }
        }
    }

    protected function markUnchanged()
    {
        $this->original = $this->toArray();
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

    /**
     * @param string $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return Article
     */
    public function load(string $path = null): Article
    {
        $this->path = $path ?? $this->path;
        if (Storage::exists($this->path)) {
            $rawFileContent = Storage::get($this->getPath());
            $parsed = $this->parseFrontmatterAndMarkdown($rawFileContent);
            $this->markdown = $parsed['markdown'];
            $this->frontmatter = $parsed['frontmatter'];
        }
        $this->markUnchanged();

        return $this;
    }

    protected function parseFrontmatterAndMarkdown(string $content, array $defaults = []): array
    {
        $token = "---\n";
        $tokenLength = strlen($token);
        $beginIdx = strpos($content, $token) + $tokenLength;
        $frontMatter = null;
        if ($beginIdx === $tokenLength && false !== ($endIdx = strpos($content, $token, $beginIdx + $tokenLength))) {
            $rawFrontMatter = substr($content, $beginIdx, $endIdx - $beginIdx);
            $frontMatter = Yaml::parse($rawFrontMatter, Yaml::PARSE_CUSTOM_TAGS);
            $content = substr($content, $endIdx + $tokenLength);
        }

        return [
            'frontmatter' => array_replace_recursive(
                $defaults,
                $frontMatter ?? []
            ),
            'markdown' => $content,
        ];
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
        if (null === $this->original['path'] && null !== $this->path) {
            // this article has just been created
            $this->saveToFile($this->path);
            $this->markUnchanged();

            return;
        }
        if ($this->original['path'] !== $this->path) {
            if (Storage::exists($this->original['path'])) {
                Storage::move($this->original['path'], $this->path);
            }
        }
        if ($this->original['frontmatter'] !== $this->frontmatter || $this->original['markdown'] !== $this->markdown) {
            $this->saveToFile($this->path);
        }
        $this->markUnchanged();
    }

    public function delete()
    {
        Storage::delete($this->path);
    }

    private function saveToFile(string $path)
    {
        Storage::put($path, $this->serialize());
    }

    public function serialize(): string
    {
        return "---\n".$this->serializeFrontmatter()."---\n".$this->markdown;
    }

    public function serializeFrontmatter()
    {
        return Yaml::dump($this->frontmatter);
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
}
