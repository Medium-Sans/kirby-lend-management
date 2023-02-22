<?php

namespace Kirby\LendManagement;

use Beebmx\KirbyDb\DB;
use Illuminate\Support\Collection;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\V;
use chillerlan\QRCode\{QRCode, QROptions};
use Kirby\Exception\NotFoundException;
use stdClass;

require_once __DIR__.'/../vendor/autoload.php';

class Item
{
    public static string $tableName = "items";

    /**
     * Creates a new item with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        return self::update(uuid(), $input);
    }

    /**
     * Deletes an item by item id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return DB::table(self::$tableName)->where('kirby_uuid', $id)->delete();
    }

    /**
     * Finds an item by id and throws an exception
     * if the item cannot be found
     *
     * @param string $id
     * @return string return the item found or null
     */
    public static function find(string $id): Collection
    {
        return DB::table(self::$tableName)->where('id', '=', $id)->get();
    }

    /**
     * Lists all items from the items.json
     *
     * @return array
     */
    public static function list(): array
    {
        if(!Database::hasTable(self::$tableName)) {
            Database::init();
        };

        return DB::table(self::$tableName)->get()->toArray();
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function count(): int
    {
        $items = self::list();

        return count($items);
    }

    /**
     * Return the number of categories from in categories.json
     *
     * @return int
     */
    public static function getNumberOfItemsLended(): int
    {
        $items = self::list();
        $numberOfItemsLended = 0;

        foreach($items as $item) {
            $numberOfItemsLended += ($item->quantity - $item->current_quantity);
        }

        return $numberOfItemsLended;
    }

    /**
     * Updates an item by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     * @throws NotFoundException
     */
    public static function update(string $id, array $input): bool
    {
        $QrCode = new QRCode;

        $input['kirby_uuid'] = $id;
        $input['qr_code'] = $QrCode->render($id);

        // The end date must be greater than the start date
        if (V::required($input['title']) === false) {
            throw new InvalidArgumentException('A title must be set');
        }

        // if there is already an item with this same uuid we update it
        // otherwise we create it
        return DB::table(self::$tableName)->updateOrInsert(
            ['kirby_uuid' => $id],
            $input);
    }

    /**
     * Return a collection of items from in items.json
     *
     * @return array
     * @throws NotFoundException
     */
    public static function collection(): array
    {

        $items = self::list();
        $collection = [];

        foreach ($items as $item) {

            $category = '';
            if($item->category_id) {
                $category = Category::find($item->category_id);
                $item->category = $category->title;
            }

            $collection[] = [
                'text' => $item->title,
                'link' => 'lendmanagement/inventory/item/' . $item->id,
                'info' => $category ?? '',
                'image' => [
                    'icon' => 'tag',
                    'back' => 'purple-400'
                ]
            ];
        }
        return $collection;
    }

    public static function getTotalItemsByCategoryId(string $categoryId): int
    {
        $items = self::list();
        $ttl = 0;

        foreach ($items as $item) {
            if($item->category_id === $categoryId) {
                $ttl++;
            }
        }

        return $ttl;
    }

    public static function getItemsByCategory(string $categoryId): array
    {
        $items = self::list();
        $collection = [];

        foreach ($items as $item) {
            if($item->category_id === $categoryId) {
                $collection[] = [
                    'text' => $item->title,
                    'link' => 'lendmanagement/inventory/item/' . $item->id,
                    'info' => $item->quantity. " pcs",
                    'image' => [
                        'icon' => 'tag',
                        'back' => 'purple-400'
                    ]
                ];
            }
        }

        return $collection;
    }

    /**
     *
     * @return array
     */
    public static function getOptions(): array
    {
        $items = self::list();
        $options = [];
        foreach ($items as $item) {
            $options[] = [
                'text' => $item->title,
                'value' => $item->id,
            ];
        }
        return $options;
    }

    public static function getItemsByIds(mixed $itemIds)
    {
        $items = self::list();
        $collection = [];

        foreach ($items as $item) {
            if(in_array($item->id, $itemIds)) {
                $collection[] = $item;
            }
        }

        return $collection;
    }

    public static function getLabelFromItemId(string $id): string
    {
        $item = self::find($id);

        $xml = '<?xml version="1.0" encoding="utf-8"?>
<DieCutLabel Version="8.0" Units="twips" MediaType="Default">
  <PaperOrientation>Landscape</PaperOrientation>
  <Id>LargeAddress</Id>
  <PaperName>30321 Large Address</PaperName>
  <DrawCommands>
    <RoundRectangle X="0" Y="0" Width="2025" Height="5020" Rx="270" Ry="270"/>
  </DrawCommands>
  <ObjectInfo>
    <TextObject>
      <Name>Text</Name>
      <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>True</IsVariable>
      <HorizontalAlignment>Center</HorizontalAlignment>
      <VerticalAlignment>Middle</VerticalAlignment>
      <TextFitMode>ShrinkToFit</TextFitMode>
      <UseFullFontHeight>True</UseFullFontHeight>
      <Verticalized>False</Verticalized>
      <StyledText>
        <Element>
          <String>Pool Image-Son</String>
          <Attributes>
            <Font Family="Arial" Size="24" Bold="True" Italic="False" Underline="False" Strikeout="False"/>
            <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
          </Attributes>
        </Element>
      </StyledText>
    </TextObject>
    <Bounds X="2179.191" Y="191.9601" Width="2480.073" Height="744.3416"/>
  </ObjectInfo>
  <ObjectInfo>
    <BarcodeObject>
      <Name>Barcode</Name>
      <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>False</IsVariable>
      <Text>'. $item[0]->id .'</Text>
      <Type>QRCode</Type>
      <Size>Large</Size>
      <TextPosition>None</TextPosition>
      <TextFont Family="Arial" Size="72" Bold="False" Italic="False" Underline="False" Strikeout="False"/>
      <CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False"/>
      <TextEmbedding>None</TextEmbedding>
      <ECLevel>0</ECLevel>
      <HorizontalAlignment>Center</HorizontalAlignment>
      <QuietZonesPadding Left="0" Right="0" Top="0" Bottom="0"/>
    </BarcodeObject>
    <Bounds X="321.5997" Y="57.59995" Width="1634.643" Height="1881.6"/>
  </ObjectInfo>
  <ObjectInfo>
    <DateTimeObject>
      <Name>DATE-TIME</Name>
      <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>False</IsVariable>
      <HorizontalAlignment>Center</HorizontalAlignment>
      <VerticalAlignment>Middle</VerticalAlignment>
      <TextFitMode>ShrinkToFit</TextFitMode>
      <UseFullFontHeight>True</UseFullFontHeight>
      <Verticalized>False</Verticalized>
      <DateTimeFormat>MonthDayYear</DateTimeFormat>
      <Font Family="Arial" Size="11" Bold="False" Italic="False" Underline="False" Strikeout="False"/>
      <PreText></PreText>
      <PostText></PostText>
      <IncludeTime>True</IncludeTime>
      <Use24HourFormat>True</Use24HourFormat>
    </DateTimeObject>
    <Bounds X="2328.374" Y="1172.909" Width="2228.75" Height="600"/>
  </ObjectInfo>
  <ObjectInfo>
    <TextObject>
      <Name>TEXT</Name>
      <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>False</IsVariable>
      <HorizontalAlignment>Left</HorizontalAlignment>
      <VerticalAlignment>Middle</VerticalAlignment>
      <TextFitMode>ShrinkToFit</TextFitMode>
      <UseFullFontHeight>True</UseFullFontHeight>
      <Verticalized>False</Verticalized>
      <StyledText>
        <Element>
          <String>HEAD - Geneve</String>
          <Attributes>
            <Font Family="Arial" Size="24" Bold="False" Italic="False" Underline="False" Strikeout="False"/>
            <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
          </Attributes>
        </Element>
      </StyledText>
    </TextObject>
    <Bounds X="2277.259" Y="673.6916" Width="2540" Height="600"/>
  </ObjectInfo>
</DieCutLabel>';

        return $xml;
    }
}
