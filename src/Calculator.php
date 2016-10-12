<?php namespace djekl\Currency;

ini_set("xdebug.cli_color", 0);
ini_set("xdebug.remote_autostart", false);
ini_set("xdebug.remote_enable", false);
ini_set("xdebug.profiler_enable", false);

use Carbon\Carbon;

class Calculator
{
    protected $request;
    protected $request_uri = "http://www.xe.com/currencyconverter/convert/?From={fromCurrencyCode}&To={toCurrencyCode}";
    protected $base_dir;
    protected $currencies = [
        "AED" => "Emirat",
        "AFN" => "Afghan Afghani",
        "ALL" => "Albanian Lek",
        "AMD" => "Armenian Dram",
        "ANG" => "Dutch Guilder",
        "AOA" => "Angolan Kwanza",
        "ARS" => "Argentine Peso",
        "AUD" => "Australian Dollar",
        "AWG" => "Aruban or Dutch Guilder",
        "AZN" => "Azerbaijani New Manat",
        "BAM" => "Bosnian Convertible Marka",
        "BBD" => "Barbadian or Bajan Dollar",
        "BDT" => "Bangladeshi Taka",
        "BGN" => "Bulgarian Lev",
        "BHD" => "Bahraini Dinar",
        "BIF" => "Burundian Franc",
        "BMD" => "Bermudian Dollar",
        "BND" => "Bruneian Dollar",
        "BOB" => "Bolivian Boliviano",
        "BRL" => "Brazilian Real",
        "BSD" => "Bahamian Dollar",
        "BTN" => "Bhutanese Ngultrum",
        "BWP" => "Botswana Pula",
        "BYR" => "Belarusian Ruble",
        "BZD" => "Belizean Dollar",
        "CAD" => "Canadian Dollar",
        "CDF" => "Congolese Franc",
        "CHF" => "Swiss Franc",
        "CLP" => "Chilean Peso",
        "CNY" => "Chinese Yuan Renminbi",
        "COP" => "Colombian Peso",
        "CRC" => "Costa Rican Colon",
        "CUC" => "Cuban Convertible Peso",
        "CUP" => "Cuban Peso",
        "CVE" => "Cape Verdean Escudo",
        "CZK" => "Czech Koruna",
        "DJF" => "Djiboutian Franc",
        "DKK" => "Danish Krone",
        "DOP" => "Dominican Peso",
        "DZD" => "Algerian Dinar",
        "EGP" => "Egyptian Pound",
        "ERN" => "Eritrean Nakfa",
        "ETB" => "Ethiopian Birr",
        "EUR" => "Euro",
        "FJD" => "Fijian Dollar",
        "FKP" => "Falkland Island Pound",
        "GBP" => "British Pound",
        "GEL" => "Georgian Lari",
        "GGP" => "Guernsey Pound",
        "GHS" => "Ghanaian Cedi",
        "GIP" => "Gibraltar Pound",
        "GMD" => "Gambian Dalasi",
        "GNF" => "Guinean Franc",
        "GTQ" => "Guatemalan Quetzal",
        "GYD" => "Guyanese Dollar",
        "HKD" => "Hong Kong Dollar",
        "HNL" => "Honduran Lempira",
        "HRK" => "Croatian Kuna",
        "HTG" => "Haitian Gourde",
        "HUF" => "Hungarian Forint",
        "IDR" => "Indonesian Rupiah",
        "ILS" => "Israeli Shekel",
        "IMP" => "Isle of Man Pound",
        "INR" => "Indian Rupee",
        "IQD" => "Iraqi Dinar",
        "IRR" => "Iranian Rial",
        "ISK" => "Icelandic Krona",
        "JEP" => "Jersey Pound",
        "JMD" => "Jamaican Dollar",
        "JOD" => "Jordanian Dinar",
        "JPY" => "Japanese Yen",
        "KES" => "Kenyan Shilling",
        "KGS" => "Kyrgyzstani Som",
        "KHR" => "Cambodian Riel",
        "KMF" => "Comoran Franc",
        "KPW" => "North Korean Won",
        "KRW" => "South Korean Won",
        "KWD" => "Kuwaiti Dinar",
        "KYD" => "Caymanian Dollar",
        "KZT" => "Kazakhstani Tenge",
        "LAK" => "Lao or Laotian Kip",
        "LBP" => "Lebanese Pound",
        "LKR" => "Sri Lankan Rupee",
        "LRD" => "Liberian Dollar",
        "LSL" => "Basotho Loti",
        "LYD" => "Libyan Dinar",
        "MAD" => "Moroccan Dirham",
        "MDL" => "Moldovan Leu",
        "MGA" => "Malagasy Ariary",
        "MKD" => "Macedonian Denar",
        "MMK" => "Burmese Kyat",
        "MNT" => "Mongolian Tughrik",
        "MOP" => "Macau Pataca",
        "MRO" => "Mauritanian Ouguiya",
        "MUR" => "Mauritian Rupee",
        "MVR" => "Maldivian Rufiyaa",
        "MWK" => "Malawian Kwacha",
        "MXN" => "Mexican Peso",
        "MYR" => "Malaysian Ringgit",
        "MZN" => "Mozambican Metical",
        "NAD" => "Namibian Dollar",
        "NGN" => "Nigerian Naira",
        "NIO" => "Nicaraguan Cordoba",
        "NOK" => "Norwegian Krone",
        "NPR" => "Nepalese Rupee",
        "NZD" => "New Zealand Dollar",
        "OMR" => "Omani Rial",
        "PAB" => "Panamanian Balboa",
        "PEN" => "Peruvian Nuevo Sol",
        "PGK" => "Papua New Guinean Kina",
        "PHP" => "Philippine Peso",
        "PKR" => "Pakistani Rupee",
        "PLN" => "Polish Zloty",
        "PYG" => "Paraguayan Guarani",
        "QAR" => "Qatari Riyal",
        "RON" => "Romanian New Leu",
        "RSD" => "Serbian Dinar",
        "RUB" => "Russian Ruble",
        "RWF" => "Rwandan Franc",
        "SAR" => "Saudi Arabian Riyal",
        "SBD" => "Solomon Islander Dollar",
        "SCR" => "Seychellois Rupee",
        "SDG" => "Sudanese Pound",
        "SEK" => "Swedish Krona",
        "SGD" => "Singapore Dollar",
        "SHP" => "Saint Helenian Pound",
        "SLL" => "Sierra Leonean Leone",
        "SOS" => "Somali Shilling",
        "SPL" => "Seborgan Luigino",
        "SRD" => "Surinamese Dollar",
        "STD" => "Sao Tomean Dobra",
        "SVC" => "Salvadoran Colon",
        "SYP" => "Syrian Pound",
        "SZL" => "Swazi Lilangeni",
        "THB" => "Thai Baht",
        "TJS" => "Tajikistani Somoni",
        "TMT" => "Turkmenistani Manat",
        "TND" => "Tunisian Dinar",
        "TOP" => "Tongan Pa'anga",
        "TRY" => "Turkish Lira",
        "TTD" => "Trinidadian Dollar",
        "TVD" => "Tuvaluan Dollar",
        "TWD" => "Taiwan New Dollar",
        "TZS" => "Tanzanian Shilling",
        "UAH" => "Ukrainian Hryvnia",
        "UGX" => "Ugandan Shilling",
        "USD" => "US Dollar",
        "UYU" => "Uruguayan Peso",
        "UZS" => "Uzbekistani Som",
        "VEF" => "Venezuelan Bolivar",
        "VND" => "Vietnamese Dong",
        "VUV" => "Ni-Vanuatu Vatu",
        "WST" => "Samoan Tala",
        "XAF" => "Central African CFA Franc BEAC",
        "XAG" => "Silver Ounce",
        "XAU" => "Gold Ounce",
        "XBT" => "Bitcoin",
        "XCD" => "East Caribbean Dollar",
        "XDR" => "IMF Special Drawing Rights",
        "XOF" => "CFA Franc",
        "XPD" => "Palladium Ounce",
        "XPF" => "CFP Franc",
        "XPT" => "Platinum Ounce",
        "YER" => "Yemeni Rial",
        "ZAR" => "South African Rand",
        "ZMW" => "Zambian Kwacha",
        "ZWD" => "Zimbabwean Dollar",
    ];

    public function __construct($base_dir = __DIR__)
    {
        $this->request = new Network\Request;
        $this->base_dir = $base_dir;

        // are both the tokens and cookies folders there and writeable
        $folders = [
            $this->base_dir . "/cookies",
        ];

        foreach ($folders as $folder) {
            if (!is_dir($folder) || !is_writeable($folder)) {
                if (mkdir($folder, 0777, true) === false) {
                    die("the folder \"" . realpath($folder) . "\" is not writeable or does not exist");
                }
            }
        }

        $this->request->base_dir = $this->base_dir;
    }

    public function currencies()
    {
        print json_encode($this->currencies, JSON_PRETTY_PRINT);
    }

    public function convert($fromCurrencyCode, $toCurrencyCode)
    {
        $exchange_rate = $this->fetchConversion($fromCurrencyCode, $toCurrencyCode);

        return [
            $fromCurrencyCode => 1,
            $toCurrencyCode => $exchange_rate,
        ];
    }

    public function massConvert($base_dir = __DIR__, $currencies = [])
    {
        $save = true;

        if (is_array($base_dir)) {
            $currencies = $base_dir;
            $base_dir = __DIR__;
            $save = false;
        } else if (!is_array($base_dir) && empty($currencies)) {
            $currencies = $this->conversions_we_care_about;
        }

        $dir = "{$base_dir}/conversions";
        $currencies = array_keys($currencies);
        $out = [];

        foreach ($currencies as $fromCurrencyCode) {
            $output_dir = "{$dir}/{$fromCurrencyCode}";

            if ($save && !file_exists($output_dir)) {
                mkdir($output_dir, 0777, true);
            }

            foreach ($currencies as $toCurrencyCode) {
                if ($fromCurrencyCode == $toCurrencyCode) {
                    continue;
                }

                $exchange_rate = $this->fetchConversion($fromCurrencyCode, $toCurrencyCode);
                $out[$fromCurrencyCode][$toCurrencyCode] = $exchange_rate;

                if ($save) {
                    file_put_contents("{$output_dir}/{$toCurrencyCode}.json", json_encode([
                        $fromCurrencyCode => 1,
                        $toCurrencyCode => $exchange_rate,
                    ], JSON_PRETTY_PRINT));
                }
            }
        }

        return $out;
    }

    protected function fetchConversion($fromCurrencyCode, $toCurrencyCode)
    {
        $url = str_replace([
            "{fromCurrencyCode}",
            "{toCurrencyCode}",
        ], [
            $fromCurrencyCode,
            $toCurrencyCode,
        ], $this->request_uri);

        $request = $this->request->fetch($url);

        return $this->extractCurrency($request["response"]["data"]);
    }

    protected function extractCurrency($input)
    {
        $output = Str::find($input, "class='uccResultAmount'>", "<");

        return $output;
    }
}
