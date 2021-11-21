<?php
declare(strict_types=1);

namespace App;

class ShippingRestriction
{
    private string $title;
    private ?int $parcelsTotalMaxWeight = null;
    private ?int $maxParcelSize = null;
    private ?int $maxParcelCount = null;

    /**
     * @param string $title
     * @param int|null $parcelsTotalMaxWeight
     * @param int|null $maxParcelSize
     * @param int|null $maxParcelCount
     */
    public function __construct(string $title, ?int $parcelsTotalMaxWeight, ?int $maxParcelSize, ?int $maxParcelCount)
    {
        $this->title = $title;
        $this->parcelsTotalMaxWeight = $parcelsTotalMaxWeight;
        $this->maxParcelSize = $maxParcelSize;
        $this->maxParcelCount = $maxParcelCount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParcelsTotalMaxWeight(): ?int
    {
        return $this->parcelsTotalMaxWeight;
    }

    public function getMaxParcelSize(): ?int
    {
        return $this->maxParcelSize;
    }

    public function getMaxParcelCount(): ?int
    {
        return $this->maxParcelCount;
    }
}
