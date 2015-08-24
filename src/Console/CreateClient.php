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
	protected $signature = 'oauth2:create-client
			{client_name : Name of the client}
			{--return_uri= : Return uri (if more, seperate by comma)}
			{--grant_types=authorization_code,token, password,client_credentials,refresh_token : Allowed grant types (if more, seperate by comma)}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new oauth2 client';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$name = $this->argument('client_name');

		$return_uris = $this->option('return_uri');
		$allowed_grant_types = $this->option('grant_types');

		if ( $return_uris !== "" ) {
			$return_uris = explode(',', $return_uris);
			array_walk($return_uris, 'trim');
		}
		else {
			$return_uris = null;
		}

		$allowed_grant_types = array_map('trim', explode(',', $allowed_grant_types));

		$client_id = str_random(32);
		$client = new Client();
		$client->name = trim($name);
		$client->id = $client_id;
		$client->secret = str_random(32);
		$client->redirect_uris = $return_uris;
		$client->allowed_grant_types = $allowed_grant_types;
		$client->save();

		$table = new Table($this->getOutput());
		$table->setHeaders(array('Client name', 'Client id', 'Client secret', 'Redirect uris', 'Allowed grant types'));
		$table->addRow([
			$client->name,
			$client_id,
			$client->secret,
			$client->redirect_uris == null ? '' : implode(PHP_EOL, $client->redirect_uris),
			$client->allowed_grant_types == null ? '' : implode(PHP_EOL, $client->allowed_grant_types),
		]);
		$table->render();
	}

}
