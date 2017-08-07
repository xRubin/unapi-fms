<?php
namespace unapi\fms\common;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\AnticaptchaInterface;

use function GuzzleHttp\Promise\unwrap;

class AudioAnticaptcha implements AnticaptchaInterface
{
    protected const DIGITS = array(
        '669cf3532495afd40304eab33106bc94' => 1,
        'e9d71c9ff6d2f5de0ba6b50633947d16' => 2,
        'c66bb3ca19adff2903fa8731291d6b6b' => 3,
        'ea77c2f378cdc327f07b84c311b88782' => 4,
        '1e44921e41455c19ef3dbbe442798aba' => 5,
        'c948e5bd536655405b3a5abd47f964c3' => 6,
        'f50c5a35d604e18c2d47e34383b782b7' => 7,
        '51282edec22d43b8e0681f2ddd7d8e78' => 8,
        'fbd9f8f9fed8274cd18d59e4697e5359' => 9,
    );

    /**
     * @param ClientInterface $client
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function getAnticaptchaPromise(ClientInterface $client, ResponseInterface $response): PromiseInterface
    {
        return $client->requestAsync('HEAD', '/services/captcha.jpg')->then(function() use ($client, $response) {
            preg_match_all("/services\/captcha-audio\/.{28}\/\d\.mp3\?timestamp=(\d*)/", $response->getBody()->getContents(), $output_array);

            $code = str_repeat('-', count($output_array[0]));

            /** @var ResponseInterface[] $results */
            $results = unwrap(array_map(function ($path) use ($client) {
                return $client->sendAsync(new Request('GET', $path));
            }, $output_array[0]));

            foreach ($results as $key => $response) {
                $md5 = md5($response->getBody()->getContents());
                if (array_key_exists($md5, static::DIGITS))
                    $code[$key] = static::DIGITS[$md5];
            }

            if (strpos($code, '-') !== false)
                return new RejectedPromise('Captcha not solved');

            return new FulfilledPromise($code);
        });
    }
}