# MyOwnFreeHost API Client

A PSR-compliant client for interacting with the MyOwnFreeHost (MOFH) API. This library provides a structured, type-safe way to manage hosting accounts, domains, and support tickets.

## Features

- **PSR-18 Compliant**: Use any compatible HTTP client (Guzzle, Symfony, etc.).
- **PSR-17 Compliant**: Decoupled request factories.
- **PSR-3 Logging**: Built-in support for logging request/response cycles.
- **Domain Driven Design**: Separated repositories for Accounts, Domains, Support, and System.
- **Strictly Typed**: Utilizes DTOs (Data Transfer Objects) for all API interactions.

## Installation

```bash
composer require fyennyi/mofh-api-client
```

## Basic Usage

### Initialization

```php
use Fyennyi\MofhApi\Client;
use Fyennyi\MofhApi\Connection;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;

// Initialize dependencies
$httpClient = new GuzzleClient(['verify' => false]); // MOFH certificates are often expired
$requestFactory = new HttpFactory();
$connection = new Connection('your_api_username', 'your_api_password');

// Create the client
$mofh = new Client($connection, $httpClient, $requestFactory);
```

### Managing Accounts

```php
use Fyennyi\MofhApi\DTO\Account\CreateAccountRequest;

// Create a new hosting account
try {
    $request = new CreateAccountRequest(
        username: 'exampleuser',
        password: 'secure_password',
        contactEmail: 'user@example.com',
        domain: 'user.yourdomain.com',
        plan: 'MyPlan'
    );

    $response = $mofh->account->create($request);
    echo "Account created: " . $response->vPanelUsername;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Suspend an account
$mofh->account->suspend('hname_12345678', 'Policy violation.');
```

### Domain & System Information

```php
// Check if a domain is available
$isAvailable = $mofh->domain->checkAvailability('test.example.com');

// List available hosting packages
$packages = $mofh->system->getPackages();
foreach ($packages as $package) {
    echo "Plan: {$package->name} | Quota: {$package->diskQuota}MB\n";
}
```

## Error Handling

The library throws `Fyennyi\MofhApi\Exception\MofhException` for API-level errors or transport issues.

```php
try {
    $mofh->account->remove('hname_12345678');
} catch (\Fyennyi\MofhApi\Exception\MofhException $e) {
    // Handle specific API error
}
```

## Requirements

- PHP 8.1 or higher.
- `ext-json` and `ext-simplexml` extensions.

## Contributing

Contributions are welcome and appreciated! Here's how you can contribute:

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please make sure to update tests as appropriate and adhere to the existing coding style.

## License

This library is licensed under the CSSM Unlimited License v2.0 (CSSM-ULv2). See the [LICENSE](LICENSE) file for details.
