<?php

namespace App\Core\Routing\Annotation;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Route
{
    private string $path;
    private array $methods;
    private bool $isPublic;

    public function __construct(
        string $path,
        array  $methods = ['get'],
        bool   $isPublic = true
    )
    {
        $this->path = $path;
        $this->methods = $methods;
        $this->isPublic = $isPublic;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }
}