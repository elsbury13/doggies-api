<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DogController extends Controller
{
    private $apiUrl = 'https://dog.ceo/api/breed';

    /**
     * @param string $url
     * @return bool
     */
    private function curlApi($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /**
     * @return array
     */
    private function getRandomDog()
    {
        $response = $this->curlApi($this->apiUrl . 's/image/random');
        $response = json_decode($response, true);

        return $response['message'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $response = $this->curlApi($this->apiUrl . 's/list/all');
        $response = json_decode($response, true);

        return view('home', [
            'dogs' => $this->displayAllDogs(
                $this->formatDogs($response),
                $request->fullUrl()
            ),
            'randomImage' => $this->getRandomDog(),
        ]);
    }

    /**
     * @param Request $request
     * @param string $breed
     * @return array
     */
    public function getBreed(Request $request, $breed)
    {
        $response = $this->curlApi($this->apiUrl . '/' . $breed . '/images');
        $response = json_decode($response, true);

        return view('breeds', [
            'dogImages' => $this->dogImages(
                $this->getImages($response, $breed)
            ),
            'breed' => $breed,
            'url' => $request->fullUrl()
        ]);
    }

    /**
     * @param Request $request
     * @param string $breed
     * @param string $subBreed
     * @return array
     */
    public function getSubBreed(Request $request, $breed, $subBreed)
    {
        $response = $this->curlApi($this->apiUrl . '/' . $breed . '/' . $subBreed . '/images');
        $response = json_decode($response, true);

        return view('breeds', [
            'dogImages' => $this->dogImages(
                $this->getImages($response, $breed)
            ),
            'breed' => $breed,
            'subBreed' => $subBreed,
            'url' => $request->fullUrl()
        ]);
    }

    /**
     * @param array $response
     * @param string $breed
     * @return array
     */
    private function getImages(array $response, $breed)
    {
        $images = [];

        if ($response['status'] == 'success') {
            foreach ($response['message'] as $image) {
                $images[] = $image;
            }
        }

        return $images;
    }

    /**
     * @param array $response
     * @return array
     */
    private function formatDogs($response)
    {
        $dogs = [];

        if ($response['status'] == 'success') {
            foreach ($response['message'] as $breed => $subBreed) {
                $dogs[$breed][] = $breed;
                foreach ($subBreed as $key => $value) {
                    $dogs[$breed][] = $value;
                }
            }
        }

        return $dogs;
    }

    /**
     * @param array $dogs
     * @param string url
     * @return string html
     */
    private function displayAllDogs($dogs, $url)
    {
        $html ='';
        $html .= '<div class="tab">';
        foreach ($dogs as $breed => $breeds) {
            $breedName = "'" . $breed . "'";
            $html .= '<button class="tablinks"  onclick="openBreed(event, ' . $breedName . ')" id="defaultOpen">' . $breed . '</button>';
        }
        $html .= '</div>';

        foreach ($dogs as $breed => $breeds) {
            $html .= '<div id="' . $breed . '" class="tabcontent">';
            foreach ($breeds as $key => $subBreed) {
                if ($key == 0) {
                    $html .= '<h3><a href="' . $url . '/breeds/' . $breed . '">' . ucfirst($breed) . '</a></h3>';
                } else {
                    $html .= '<a href="' . $url . '/breeds/' . $breed . '/' . $subBreed . '">' . ucfirst($subBreed) . '</a><br />';
                }
            }
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * @param array $images
     * @return string html
     */
    private function dogImages($images)
    {
        $html = '<div class="container" id="container">';

        $count = 0;
        $total = count($images);
        foreach ($images as $image) {
            $count = $count +1;
            $html .= '<div class="mySlides">
              <div class="numbertext">' . $count . ' / ' . $total . '</div>
              <img src="' . $image . '" style="width:100%">
            </div>';
        }

        $html .= '<a class="prev" onclick="plusSlides(-1)">❮</a>';
        $html .= '<a class="next" onclick="plusSlides(1)">❯</a>';

        $count = 0;
        foreach ($images as $image) {
            $count = $count + 1;
            $html .= '<div class="column">
                <img class="demo cursor" src="' . $image . '" style="height:20%; width:100%" onclick="currentSlide(' . $count . ')" />
            </div>';
        }
        $html .= '</div>';

        return $html;
    }
}
