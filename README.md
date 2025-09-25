HorizonWeb Task - Laravel Project
================================

This project is a Laravel-based API service that provides:
- Country data with languages and categories
- Integration with NewsData.io to fetch news by country, language, and category
- Ability to add/remove categories for each country
- Redis caching for performance
- Full test coverage with Laravel's testing tools

----------------------------------------
Requirements
----------------------------------------
- PHP >= 8.1
- Composer
- SQLite (used as default database)
- Redis (for caching)
- Git (for cloning repository)

----------------------------------------
Installation
----------------------------------------
1. Clone the repository
   git clone <YOUR_REPO_URL>
   cd horizonweb-task

2. Install dependencies
   composer install

3. Copy the environment file
   cp .env.example .env

4. Generate application key
   php artisan key:generate

5. Configure the database
   - Open .env
   - Set the SQLite database path to an **absolute path**, for example:
     DB_CONNECTION=sqlite
     DB_DATABASE=/absolute/path/to/project/database/database.sqlite
   - Make sure the file exists:
     touch database/database.sqlite

6. (Optional) Configure Redis for caching
   - Ensure Redis is installed and running
   - In .env file:
     CACHE_DRIVER=redis

7. Set your NewsData API key (optional if using free key)
   - Add to .env:
     NEWSDATA_API_KEY=your_api_key_here

----------------------------------------
Database Migrations & Seeders
----------------------------------------
Run migrations:
   php artisan migrate

Seed initial data (countries and languages):
   php artisan db:seed --class=LanguageSeeder
   php artisan db:seed --class=CountrySeeder

----------------------------------------
Key Artisan Commands
----------------------------------------
Start the local development server:
   php artisan serve

Import categories from NewsData API:
   php artisan newsdata:import-categories

Run the full test suite:
   php artisan test

----------------------------------------
API Endpoints
----------------------------------------
GET   /countries
      Returns all countries with their languages and categories.

GET   /country/{code}
      Returns details for a single country by its 2-letter code.

DELETE /country/{code}/category/{categoryName}
      Removes the specified category from the country.

GET   /news/{country}/{page?}
      Retrieves paginated news articles for a given country.
      Pagination uses the 'nextPage' token from NewsData.

GET   /country/{code}/{category}
      Fetches news articles for a specific country and category.

GET   /api/categories/import/{country}/{language}
      Imports categories for the given country and language.

----------------------------------------
Testing
----------------------------------------
Run all tests:
   php artisan test

Run a specific test class:
   php artisan test --filter=FullApiTest

The tests use RefreshDatabase to reset the database and Http::fake()
to mock external API responses.

----------------------------------------
Notes
----------------------------------------
- Make sure to set an absolute path for the SQLite database in .env.
- The free NewsData API key expires after 30 days. Replace it with your own key in .env when needed.
- Redis caching is used for storing NewsData pagination tokens and for caching country listings.
