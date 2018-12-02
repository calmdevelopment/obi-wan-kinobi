<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class ArticleFileSaver
{
    /**
     * @var array
     */
    protected $original;
    /**
     * @var array
     */
    protected $current;

    /**
     * ArticleFileSaver constructor.
     *
     * @param array $original
     * @param array $current
     */
    public function __construct(array $original, array $current)
    {
        $this->original = $original;
        $this->current = $current;
    }

    public function save()
    {
        if ($this->shouldBeCreated()) {
            $this->saveToFile($this->current['path']);

            return;
        }

        if ($this->shouldBeMoved()) {
            $this->moveFile();
        }

        if ($this->hasFileContentChanged()) {
            $this->saveToFile($this->current['path']);
        }
    }

    private function saveToFile(string $path)
    {
        Storage::put($path, $this->serialize());
    }

    public function serialize(): string
    {
        return "---\n".$this->serializeFrontmatter()."---\n".$this->current['markdown'];
    }

    public function serializeFrontmatter()
    {
        return Yaml::dump($this->current['frontmatter']);
    }

    private function shouldBeCreated(): bool
    {
        return null === $this->original['path'] && null !== $this->current['path'];
    }

    private function shouldBeMoved(): bool
    {
        return $this->original['path'] !== $this->current['path'] && Storage::exists($this->original['path']);
    }

    private function moveFile()
    {
        Storage::move($this->original['path'], $this->current['path']);
    }

    private function hasFileContentChanged(): bool
    {
        return $this->original['frontmatter'] !== $this->current['frontmatter'] || $this->original['markdown'] !== $this->current['markdown'];
    }
}
