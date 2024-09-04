<?php

namespace App\Console\Commands;

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Illuminate\Console\Command;
use JsonSerializable;
use Olifanton\Interop\Units;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\ContractAwaiter;
use Olifanton\Ton\Contracts\Exceptions\ContractException;
use Olifanton\Ton\Contracts\Nft\MintOptions;
use Olifanton\Ton\Contracts\Nft\NftAttributesCollection;
use Olifanton\Ton\Contracts\Nft\NftCollection;
use Olifanton\Ton\Contracts\Nft\NftCollectionMetadata;
use Olifanton\Ton\Contracts\Nft\NftCollectionOptions;
use Olifanton\Ton\Contracts\Nft\NftItem;
use Olifanton\Ton\Contracts\Nft\NftItemMetadata;
use Olifanton\Ton\Contracts\Nft\NftTrait;
use Olifanton\Ton\Contracts\Wallets\Transfer;
use Olifanton\Ton\Contracts\Wallets\TransferOptions;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4Options;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4R2;
use Olifanton\Ton\Deployer;
use Olifanton\Ton\DeployOptions;
use Olifanton\Ton\Exceptions\AwaiterMaxTimeException;
use Olifanton\Ton\Exceptions\DeployerException;
use Olifanton\Ton\Exceptions\TransportException;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;

class RarityTrait extends NftTrait
{
    public const COMMON = "Common";

    public const RARE = "Rare";

    public const LEGENDARY = "Legendary";

    public function __construct()
    {
        parent::__construct(
            "Rarity",
            self::COMMON,
        );
    }
}

class BoolAsStringTrait extends NftTrait
{
    public function __construct(string $traitType, bool $value)
    {
        parent::__construct($traitType, $value ? "Yes" : "No");
    }

    public function valued(bool|int|string|null|float $value): array
    {
        $v = (bool) $value;

        return [
            "type" => $this->traitType,
            "value" => $v ? "Yes" : "No",
        ];
    }
}

class MintNFTCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nft:mint-collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy NFT Collection and mint NFT items';

    protected bool $isMainnet = true;

    protected ?string $baseUri = null;

    public function __construct()
    {
        $this->isMainnet = env('TON_IS_MAINNET', false);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws ContractException
     * @throws AwaiterMaxTimeException
     * @throws TransportException
     */
    public function handle(): void
    {
        $this->info("[START NFT COLLECTION DEPLOY]");

        if ($this->isMainnet) {
            $this->baseUri = ClientOptions::MAIN_BASE_URL;

            $this->warn("Using mainnet");

            if (!$this->confirm('Do you wish to continue?')) {
                exit(1);
            }
        } else {
            $this->baseUri = ClientOptions::TEST_BASE_URL;

            $this->warn("Using testnet");
        }

        $words = explode(" ", env('TON_WALLET_MNEMONICS'));
        $kp = TonMnemonic::mnemonicToKeyPair($words);

        $httpClient = new HttpMethodsClient(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
        );

        $toncenter = new ToncenterHttpV2Client(
            $httpClient,
            new ClientOptions(
                $this->baseUri,
                env('TON_CENTER_API_KEY')
            ),
        );
        $transport = new ToncenterTransport($toncenter);

        $ownerWallet = new WalletV4R2(
            new WalletV4Options(
                $kp->publicKey,
            ),
        );

        $deployer = new Deployer($transport);
        $awaiter = new ContractAwaiter($transport);

        // Set metadata urls for collection
        $collectionMetadataUrl = "https://api.disclosuregame.thebatclaud.io/nft/specimens.json";

        // Set metadata urls for items
        $itemsMetadataUrls = [
            'https://api.disclosuregame.thebatclaud.io/nft/specimens/doctor.json',
        ];

        $collectionContract = new NftCollection(
            new NftCollectionOptions(
                $ownerWallet->getAddress(),
                $collectionMetadataUrl,
                "",
                NftItem::getDefaultCode(),
            )
        );

        // Upload collection contract
        try {
            $deployer->deploy(
                new DeployOptions(
                    $ownerWallet,
                    $kp->secretKey,
                    Units::toNano(0.005),
                ),
                $collectionContract,
            );
        } catch (DeployerException|TransportException $e) {
            dd($e->getMessage());
        }

        $this->info("Collection deployed, address: {$collectionContract->getAddress()}");
        $this->info("Getgems: https://testnet.getgems.io/collection/{$collectionContract->getAddress()}");
        $this->info("Tonscan: https://testnet.tonscan.org/address/{$collectionContract->getAddress()}");

        $awaiter->waitForActive($collectionContract->getAddress());

        foreach ($itemsMetadataUrls as $i => $itemsMetadataUrl) {
            $external = $ownerWallet->createTransferMessage(
                [
                    new Transfer(
                        $collectionContract->getAddress(),
                        Units::toNano(1),
                        NftCollection::createMintBody(
                            new MintOptions(
                                $i + 3,
                                Units::toNano(1),
                                $ownerWallet->getAddress(),
                                $itemsMetadataUrl,
                            )
                        ),
                    ),
                ],
                new TransferOptions(
                    (int) $ownerWallet->seqno($transport),
                )
            );

            $transport->sendMessage($external, $kp->secretKey);
            $itemAddress = $collectionContract->getNftItemAddress($transport, $i);
            $awaiter->waitForActive($itemAddress);

            $itemAddressString = $itemAddress->toString(true, true, true);

            $this->info("Item with index $i minted, address: {$itemAddressString}");
            $this->info("Getgems: https://testnet.getgems.io/collection/{$itemAddressString}");
            $this->info("Tonscan: https://testnet.tonscan.org/address/{$itemAddressString}");
        }
    }
}
