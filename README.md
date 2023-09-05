# Freight Microservice

This repository contains a microservice that integrates with Freight, a provider of digital payment methods. The microservice allows developers to seamlessly integrate Freight's payment functionality into their applications.

## Integrations

(1) GET /fretes

Padrão null
Urgente 0
Expresso 1
Programado 2

## Prerequisites

Before using this microservice, ensure that you have the following prerequisites:

- Freight Merchant Account: Obtain a merchant account with Freight and ensure that you have the necessary credentials and API keys.
- Development Environment: Set up a development environment with Node.js and npm installed.
- Database: Configure a database system (e.g., MySQL, PostgreSQL) and provide the necessary connection details.

## Installation

Follow these steps to install and run the Freight Microservice:

1. Clone the repository:

   ```shell
   git clone git@bitbucket.org:mobiup1/mobiq_int_freight.git
   ```

2. Navigate to the project directory:

   ```shell
   cd mobiq_int_freight
   ```

3. Configure the environment variables:

   - Create a `.env` file in the root of the project.
   - create your `.env` file based on the `.env.example` file because it contains all the variables available in the project.
   - Put your Freight credentials in the `storage/app/certs`.

4. Run as Docker

   ```shell
   docker-compose up -d
   ```

5. The microservice should now be up and running on `http://localhost:8000`.

## Usage

 Command can easily provide an overview of all of the routes that are defined by your application:

  ```shell
  php artisan route:list
  ```

 Access the application swagger at the following url:

  `http://localhost:8000/api/documentation`

Ensure that you provide the necessary input parameters and include the required headers when making API requests.

## Documentation

For detailed documentation on how to use the Freight Microservice and the available API endpoints, refer to the [API Documentation](https://devportal.itau.com.br).

## Contributing

Contributions to this microservice are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request. Make sure to adhere to the established coding conventions and provide detailed information about your changes.

## License

This project is licensed under the [MIT License](LICENSE).

## Contact

If you have any questions or need further assistance, please contact [administrativo@mobiup.com.br](mailto:administrativo@mobiup.com.br).


