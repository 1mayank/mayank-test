<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use DB;

class ProductsController extends Controller
{
  
    /**
     * return a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function productsList(Request $request)
    {   
        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 8;
        return Products::offset($offset)->limit($limit)->get();
    }
    
    
    
    /**
     * inserts the data in database
     *
     * @return \Illuminate\Http\Response
     */
    public function cron()
    {
        $fetchAll =false;
        $url ="https://api.packt.com/api/v1/products?token=". env('API_TOKEN');
        $response = $this->apiCall($url);
        $this->insertProducts($response);
        $data = json_decode($response);
        //pages to display
        $total = ($fetchAll)?$data->total:1; //dd($data->total);
        if($total>1){
            for($i=2;$i<=$total;$i++){
                $url ="https://api.packt.com/api/v1/products?page=" . $i . "&token=". env('API_TOKEN');
                $response = $this->apiCall($url); //dd($response);
                $this->insertProducts($response);
            }
        }

        return true;
    }
    
    public function apiCall($url){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
        //   CURLOPT_URL => "https://api.packt.com/api/v1/products/9781801077361/cover/large?token=". env('API_TOKEN'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function insertProducts($response){
        $data = json_decode($response);
        $products = $data->products;
        try {
         
            foreach($products as $product){
                $productId = $product->id;
                $priceAndImage = $this->getPriceAndImage($productId);
                Products::updateOrCreate([
                    'product_id' => $product->id,
                    'isbn13' => $product->isbn13,
                    'title' => $product->title,
                    'publication_date' => $product->publication_date,
                    'authors' => json_encode($product->authors),
                    'category' => json_encode($product->categories),
                    'concept' => $product->concept,
                    'language' => $product->language,
                    'language_version' => isset($product->language_version)?$product->language_version:'',
                    'tool' => $product->tool,
                    'vendor' => $product->vendor,
                    'prices' => json_encode($priceAndImage->prices),
                    'cover_image' => $priceAndImage->image_path
                ]);

            }
        }
        
        catch (customException $e) {
            //display custom message
            echo $e->errorMessage();
        }
    }


    public function getPriceAndImage ($productId){
        $url = 'https://api.packt.com/api/v1/products/'. $productId .'/price?token='. env('API_TOKEN');
        $response = $this->apiCall($url);
        $data = json_decode($response);
        $prices = $data->prices;

        $url = 'https://api.packt.com/api/v1/products/'. $productId .'/cover/small?token='. env('API_TOKEN');
        $response = $this->apiCall($url);
        // $data = base64_decode($response);
        $im = imagecreatefromstring($response);
        $ret = imagepng($im, base_path()."/public/images/products/$productId.png");
        $path = ($ret)? "images/products/$productId.png":'';
        
//         if ($im !== false) {
//             header('Content-Type: image/png');
//             imagepng($im);
//             imagedestroy($im);
//         }
//         else {
// //            echo 'An error occurred.';
//         }
        return (object)['prices'=>$prices, 'image_path'=>$path ];
                // $data = json_decode($response, true);
        
    }
}
