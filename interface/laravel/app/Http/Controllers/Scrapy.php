<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Scrapy\Builders\ScrapyBuilder;
use Scrapy\Parsers\Parser;
use Scrapy\Crawlers\Crawly;
use Scrapy\Readers\IReader;

class Scrapy extends Controller
{
    //Function used to scrape sales data
    public static function scrapeCarSales($make, $model, $year) {
        $response = ScrapyBuilder::make()
            ->reader(new CarSalesReader(
                "https://www.autotrader.com/cars-for-sale/all-cars/"
                . "$year/" . strtolower($make) . "/" . strtolower($model)
                . "?searchRadius=500&numRecords=100&sortBy=distanceASC"
            ))
            ->parser(new AutoTraderParser())
            ->build()
            ->scrape();
        return $response;
	}
}

//Class for parsing sales data from AutoTrader
class AutoTraderParser extends Parser
{
     public function process(Crawly $crawly, array $output): array
     {
        //Auto Trader - Grabs price via explicit element and class
        //Converts returned values to string and removes commas
        $output = $crawly->filter('span[class="first-price"]')->map(function (Crawly $crawly, int $index) {
            return str_replace(',', '', $crawly->string());
        });

        return $output;
     }
}

class CustomReader implements IReader
{
    public function read(): string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                'Referer' => 'http://www.google.com'
            //Test Site
            //])->get('https://webscraper.io/test-sites/e-commerce/ajax');
            //AutoTrader
            ])->get('https://www.autotrader.com/cars-for-sale/all-cars/2021/subaru/crosstrek/sarasota-fl-34235?searchRadius=200&numRecords=100');

            return (string) $response->getBody();
        } catch (ClientException|ServerException $e) {
            throw new ScrapeException("Url '$this->url' could not be read.", $this);
        }
    }
}

//Class for requesting a page from car sales sites
class CarSalesReader implements IReader
{
    //Variable to instantiate the url varialbe
    protected $url;

    //Constructor to allow passing a variable in
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    //Function to read a page from car sales sites
    public function read(): string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
                'Referer' => 'http://www.google.com'
            ])->get($this->url);

            return (string) $response->getBody();
        } catch (ClientException|ServerException $e) {
            throw new ScrapeException("Url '$this->url' could not be read.", $this);
        }
    }
}