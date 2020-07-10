<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use PDF;

// invoice2
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class ProductController extends Controller
{
    private $addressPart1 = 'PO Box 34567';
    private $addressPart2 = 'Devan Plaza, Crossway Road, Nairoby';
    private $address;

    public function __construct() {
        $this->address = $this->addressPart1 . ', ' . $this->addressPart2;
    }

    public function index()
    {

        $products = Product::all();
        return view('welcome', compact('products'));
    }

    public function store(Request $request)
    {
       
        $this->validate($request,[
            'product' => 'required|string',
            'customer' => 'required|string',
            'price' => 'required',
            'quantity' => 'required'
        ]);
       
        $product = new Product();
        $product->customer = $request->customer;
        $product->product = $request->product;
        $product->quantity = $request->quantity;
        $product->price = $request->price;

        if($product->save()){
            return redirect()->route('product.index');
        }else{
            return $request;
        }
       
     
    }

    public function generateInvoice($id)
    {
        $product = Product::findorFail($id);

        $address = $this->address;
   
        $pdf = PDF::loadView('invoice', compact('address','product'))->setPaper('a5', 'landscape');
        $customer = $product->customer;
        return $pdf->stream( $customer.'.pdf');
    }


    public function generateInvoiceTwo($id)
    {
        $product = Product::findorFail($id);

        $client = new Party([
            'name'          => 'Your Name',
            'address'       => $this->address,
            'phone'         => '254 4567890',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Party([
            'name'          => $product->customer,
            'address'       => 'Address',
            'code'          => '#22663214',
            'custom_fields' => [
                'order number' => '> 654321 <',
            ],
        ]);

        $items = [
            (new InvoiceItem())->title($product->product)->pricePerUnit($product->price)->quantity($product->quantity)->discount(0),
           
        ];

        $notes = [
            $this->addressPart1,
            $this->addressPart2,
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make()
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($product->product)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('/images.png'));
            // You can additionally save generated invoice to configured disk
            
            $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();

       
    }
}
