<?php
declare(strict_types=1);

namespace App;

use DVDoug\BoxPacker\Item;
use DVDoug\BoxPacker\NoBoxesAvailableException;
use DVDoug\BoxPacker\Packer;
use DVDoug\BoxPacker\Test\TestBox;

class Resolver
{
    /** @var ShippingRestriction[] */
    private $shippingRestrictions = [];

    /** @var array<array{Item, int}> */
    private $items = [];

    public function addShippingRestriction(ShippingRestriction $shippingRestriction): void
    {
        $this->shippingRestrictions[] = $shippingRestriction;
    }

    public function addItem(Item $item, int $quantity = 1): void
    {
        $this->items[] = [$item, $quantity];
    }

    public function resolve(): array
    {
        $results = [];
        foreach ($this->shippingRestrictions as $shippingRestriction) {
            $result = [
                'title' => $shippingRestriction->getTitle(),
                'result' => true,
                'boxes' => 0,
                'failReason' => '',
            ];

            $weightCheck = $this->checkTotalWeightRestriction($shippingRestriction);
            if(!$weightCheck) {
                $result['result'] = false;
                $result['failReason'] = 'Too heavy';
                $results[] = $result;
                continue;
            }

            $packer = $this->setUpPacker($shippingRestriction);
            try {
                $packed = $packer->doVolumePacking();
                $result['boxes'] = $packed->count();
            } catch (NoBoxesAvailableException $e) {
                $result['result'] = false;
                $result['failReason'] = 'Cannot fit to boxes';
            }

            $results[] = $result;
        }

        return $results;
    }

    private function checkTotalWeightRestriction(ShippingRestriction $shippingRestriction): bool
    {
        $totalWeight = 0;
        foreach ($this->items as $record) {
            /**
             * @var Item $item
             * @var int $quantity
             */
            [$item, $quantity] = $record;
            $totalWeight += $item->getWeight() * $quantity;
        }

        return $totalWeight <= ($shippingRestriction->getParcelsTotalMaxWeight() ?? 100000);
    }

    private function setUpPacker(ShippingRestriction $shippingRestriction): Packer
    {
        $packer = new Packer();
        $maxBoxCount = $shippingRestriction->getMaxParcelCount() ?? 100;
        $maxBoxSize = $shippingRestriction->getMaxParcelSize() ?? 10000;
        for ($i = 0; $i < $maxBoxCount; $i++) {
            $box = new TestBox(
                "Box {$i}",
                $maxBoxSize,
                $maxBoxSize,
                $maxBoxSize,
                1,
                $maxBoxSize - 1,
                $maxBoxSize - 1,
                $maxBoxSize - 1,
                100000);
            $packer->addBox($box);
        }

        foreach ($this->items as $record) {
            /**
             * @var Item $item
             * @var int $quantity
             */
            [$item, $quantity] = $record;
            $packer->addItem($item, $quantity);
        }

        return $packer;
    }
}
