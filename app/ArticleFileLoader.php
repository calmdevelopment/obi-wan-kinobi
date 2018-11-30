<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class ArticleFileLoader
{
    /**
     * @var string
     */
    protected $markdown;

    /**
     * @var array
     */
    protected $frontmatter;

    /**
     * @param string $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return ArticleFileLoader
     */
    public function load(string $path): ArticleFileLoader
    {
        if (Storage::exists($path)) {
            $rawFileContent = Storage::get($path);
            $parsed = $this->parseFrontmatterAndMarkdown($rawFileContent);
            $this->markdown = $parsed['markdown'];
            $this->frontmatter = $parsed['frontmatter'];
        }

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

    public function markdown(): string
    {
        return $this->markdown;
    }

    public function frontmatter(): array
    {
        return $this->frontmatter;
    }
}
