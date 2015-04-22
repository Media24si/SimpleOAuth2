<?php namespace Media24si\SimpleOAuth2\Console;

use Illuminate\Console\Command;
use Media24si\SimpleOAuth2\Entities\Client;
use Symfony\Component\Console\Helper\Table;

class ListClients extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'oauth2:list-clients';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'List all clients';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$clients = Client::all();

		$table = new Table($this->getOutput());
		$table->setHeaders(array('Client id', 'Client secret', 'Redirect uris', 'Allowed grant types'));

		foreach ($clients as $client) {
			$table->addRow([
				$client->client_id,
				$client->client_secret,
				$client->redirect_uris == null ? '' : implode(PHP_EOL, $client->redirect_uris),
				$client->allowed_grant_types == null ? '' : implode(PHP_EOL, $client->allowed_grant_types),
			]);
		}
		$table->render();
	}

}