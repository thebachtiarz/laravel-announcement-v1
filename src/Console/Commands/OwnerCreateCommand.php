<?php

namespace TheBachtiarz\Announcement\Console\Commands;

use Illuminate\Console\{Command, ConfirmableTrait};
use Illuminate\Support\Facades\{Artisan, Log};
use TheBachtiarz\Announcement\Interfaces\ConfigInterface;
use TheBachtiarz\Announcement\Service\OwnerCurlService;
use TheBachtiarz\Toolkit\Config\Helper\ConfigHelper;
use TheBachtiarz\Toolkit\Config\Interfaces\Data\ToolkitConfigInterface;
use TheBachtiarz\Toolkit\Config\Service\ToolkitConfigService;

class OwnerCreateCommand extends Command
{
    use ConfirmableTrait, ConfigHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thebachtiarz:announcement:owner:create {--force : Force operation to update owner code even owner code exists or in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Announcement: create new owner announcement';

    /**
     * proposed owner code
     *
     * @var string
     */
    private $proposedOwnerCode = "";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $currentOwnerCode = tbannconfig('owner_code');

        try {
            if (iconv_strlen($currentOwnerCode)) {
                throw_if(!($this->hasOption('force') && $this->option('force')), 'Exception', "");

                throw_if(!$this->confirmToProceed(), 'Exception', "");

                $this->updateOwnerCodeAndConfigFile();
            } else {
                $this->updateOwnerCodeAndConfigFile();
            }
        } catch (\Throwable $th) {
            $this->proposedOwnerCode = $currentOwnerCode;
        }

        try {
            $updateConfigData = ToolkitConfigService::name(ConfigInterface::ANNOUNCEMENT_CONFIG_PREFIX_NAME . "/" . ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME)
                ->value($this->proposedOwnerCode)
                ->accessGroup(ToolkitConfigInterface::TOOLKIT_CONFIG_PRIVATE_CODE)
                ->set();

            throw_if(!$updateConfigData, 'Exception', "Failed to update config owner code data");

            Artisan::call('config:cache');

            Log::channel('application')->info("- Successfully set new owner code");

            $this->info('Owner code set successfully.');

            return 1;
        } catch (\Throwable $th) {
            Log::channel('application')->warning("- Failed to set new owner code: {$th->getMessage()}");

            $this->warn('Failed to set owner code.');

            return 0;
        }
    }

    /**
     * update owner code and config file
     *
     * @return void
     */
    private function updateOwnerCodeAndConfigFile(): void
    {
        try {
            $generateOwnerCode = (new OwnerCurlService)->create();

            throw_if(!$generateOwnerCode['status'], 'Exception', $generateOwnerCode['message']);

            $this->proposedOwnerCode = $generateOwnerCode['data']['owner_code'];

            $updateConfigFile = self::setConfigName(ConfigInterface::ANNOUNCEMENT_CONFIG_NAME)
                ->updateConfigFile(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME, $this->proposedOwnerCode);

            throw_if(!$updateConfigFile, 'Exception', "Failed to update config owner code file");
        } catch (\Throwable $th) {
            Log::channel('error')->info($th->getMessage());

            throw $th;
        }
    }
}
