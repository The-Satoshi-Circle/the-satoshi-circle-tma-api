<?php

namespace Database\Seeders;

use App\Models\NFTCollection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class NFTCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collection = NFTCollection::query()->create([
            'name' => 'Satoshi\'s Ark',
            'description' => 'A collection of 100 NFTs made by The Satoshi\'s Circle, a community involved in enhancing people\'s knowledge regarding crypto and blockchain. Satoshi\'s Ark reimagine Noah\'s Ark in a cyberpunk future. Digital animals with unique powers travel on a high-tech ark to rebuild the planet after the collapse of Web 2.0. Powered by the strength of Web 3.0, they are committed to restoring a decentralized world where blockchain is the key to the future.',
            'image' => 'https://amethyst-magnificent-cephalopod-681.mypinata.cloud/ipfs/QmX4P8cYun2hx8NsiMxBgX32MKxdQvcWpHa1X1B78c6xa7',
        ]);

        foreach (scandir(app_path('../database/seeders/nft_metadata')) as $metadatafile) {
            if ($metadatafile === '.' || $metadatafile === '..') {
                continue;
            }

            $nftMetadata[] = json_decode(file_get_contents(app_path("../database/seeders/nft_metadata/") . $metadatafile));
        }

        $nftMetadata = Arr::map($nftMetadata, function ($item) {
            return [
                'name' => $item->name,
                'description' => $item->description,
                'image' => $item->image,
            ];
        });

        $collection->items()->createMany($nftMetadata);
    }
}
