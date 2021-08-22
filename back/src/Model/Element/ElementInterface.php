<?php

declare(strict_types=1);

namespace App\Model\Element;

use DateTime;

interface ElementInterface
{
    /**
     * Get element type.
     */
    public static function getType(): string;

    /**
     * Gives extensions supported by this element.
     *
     * @return string[]
     */
    public static function getSupportedExtensions(): array;

    /**
     * Determinate if this type of element should have his content loaded in response object.
     */
    public static function shouldLoadContent(): bool;

    /**
     * Get content of the file from the element object
     * It can be handled differently by each element typed class.
     */
    public function getContent(): ?string;

    /**
     * Set content of the file in the element object
     * It can be handled differently by each element typed class.
     */
    public function setContent(string $content): void;

    /**
     * Get element name.
     */
    public function getName(): string;

    /**
     * Set element name.
     */
    public function setName(string $name): void;

    /**
     * Get element tags.
     *
     * @return string[]
     */
    public function getTags(): array;

    /**
     * Get element last updated date.
     */
    public function getUpdated(): DateTime;

    /**
     * Get element size.
     */
    public function getSize(): int;

    /**
     * Get element extension.
     */
    public function getExtension(): string;

    /**
     * Get element's Colllection encoded path.
     */
    public function getEncodedColllectionPath(): string;

    /**
     * Set element's Colllection encoded path.
     */
    public function setEncodedColllectionPath(string $encodedColllectionPath): void;

    /**
     * Get encoded element basename.
     */
    public function getEncodedElementBasename(): string;

    /**
     * Set encoded element basename.
     */
    public function setEncodedElementBasename(string $encodedElementBasename): void;

    /**
     * Get element file URL.
     */
    public function getFileUrl(): ?string;

    /**
     * Set element file URL.
     */
    public function setFileUrl(string $fileUrl): void;
}
