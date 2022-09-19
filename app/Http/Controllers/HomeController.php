<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helpers\UserInfo;

use App\Models\VerificationCode;
use App\Publisher;
use App\RelatedProduct;
use App\SimilarProduct;
use App\StockRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\SubCategory;
use App\SubSubCategory;
use App\Product;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\User;
use App\Seller;
use App\Shop;
use App\Color;
use App\Order;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;

class HomeController extends Controller
{

    public function index()
    {
        return view('frontend.index');
    }

}
