<?php namespace Media24si\SimpleOAuth2\Console;

use Illuminate\Console\Command;
use Media24si\SimpleOAuth2\Entities\Client;
use Symfony\Component\Console\Helper\Table;

class CreateClient extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'oauth2:create-client';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new client';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$name = $this->ask('Client name:', '');
		$return_uris = $this->ask('Return url (if more, separate by ","):', '');
		$allowed_grant_types = $this->ask('Allowed grant types (default: authorization_code, token, password, client_credentials, refresh_token):', 'authorization_code, token, password, client_credentials, refresh_token');

		if ( $return_uris !== "" ) {
			$return_uris = explode(',', $return_uris);
			array_walk($return_uris, 'trim');
		}
		else {
			$return_uris = null;
		}

		$allowed_grant_types = array_map('trim', explode(',', $allowed_grant_types));

		$client = new Client();
		$client->name = trim($name);
		$client->client_id = str_random(32);
		$client->client_secret = str_random(32);
		$client->redirect_uris = $return_uris;
		$client->allowed_grant_types = $allowed_grant_types;
		$client->save();

		$table = new Table($this->getOutput());
		$table->setHeaders(array('Client name', 'Client id', 'Client secret', 'Redirect uris', 'Allowed grant types'));
		$table->addRow([
			$client->name,
			$client->client_id,
			$client->client_secret,
			$client->redirect_uris == null ? '' : implode(PHP_EOL, $client->redirect_uris),
			$client->allowed_grant_types == null ? '' : implode(PHP_EOL, $client->allowed_grant_types),
		]);
		$table->render();
	}

}